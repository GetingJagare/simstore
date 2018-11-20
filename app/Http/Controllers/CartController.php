<?php

namespace App\Http\Controllers;

use App\Number;
use App\Order;
use App\Tariff;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{

    public function __construct()
    {

    }


    public function getCart()
    {
        $numbersCart = collect(session('numbers_cart', []));
        $numberTariffs = collect(session('number_tariffs', []));

        /** @var Collection|null $numbers */
        $numbers = !empty($numbersCart) ? Number::find($numbersCart->keys()) : null;

        $numbers = formatNumbers($numbers);
        $numberTariffs = formatTariffs($numberTariffs);

        /*$tariffsCart = collect(session('tariffs_cart', []));
        $tariffs = !empty($tariffsCart) ? Tariff::find($tariffsCart->keys()) : null;*/

        $price = $numbers->sum('final_price');

        foreach ($numbers as $number) {
            $number->tariff = $numberTariffs[$number->id] ?? null;
            if ($number->tariff) {
                $price += (float)$number->tariff->final_price;
            }
        }

        return response()->json([
            'success' => true,
            'numbers' => $numbers,
            //'tariffs' => $tariffs,
            'price' => $price,
            'count' => cartCount()
        ]);
    }

    public function addNumberToCart(Request $request)
    {
        $numbersCart = session('numbers_cart', []);
        $numberTariffs = session('number_tariffs', []);

        $number = Number::find($request->id);
        $allTariffs = Cache::remember("all_tariffs", 1440, function () {
            return Tariff::query()->orderBy('price')->get();
        });

        $numbersCart[$number->id] = [
            'id' => $number->id,
            'number' => $number->value
        ];

        foreach ($allTariffs as $tariff) {
            $numberPrice = json_decode($tariff->number_prices, true);
            if ($number->price > (float)$numberPrice[0] && $number->price <= (float)$numberPrice[1]) {
                $numberTariffs[$number->id] = $tariff;
                break;
            }
        }

        session(['numbers_cart' => $numbersCart]);
        session(['number_tariffs' => $numberTariffs]);

        return response()->json(['success' => true, 'message' => 'Номер успешно добавлен в корзину.', 'count' => cartCount(), 'numbers' => getNumbersIdsInCart()]);
    }

    public function addTariffToCart(Request $request)
    {
        $tariffsCart = session('tariffs_cart', []);
        $tariff = Tariff::find($request->id);

        $tariffsCart[$tariff->id] = [
            'id' => $tariff->id,
            'name' => $tariff->name
        ];

        session(['tariffs_cart' => $tariffsCart]);

        return response()->json(['success' => true, 'message' => 'Тариф успешно добавлен в корзину.', 'count' => cartCount(), 'tariffs' => getTariffsIdsInCart()]);
    }

    public function removeFromCart(Request $request)
    {
        $type = $request->type == 'tariff' ? 'tariffs_cart' : 'numbers_cart' ;
        $items = session($type, []);

        unset($items[$request->id]);

        $numberTariffs = session('number_tariffs', []);
        if ($request->type == 'number') {
            if (isset($numberTariffs[$request->id])) {
                unset($numberTariffs[$request->id]);
            }
            session(['number_tariffs' => $numberTariffs]);
        }

        session([$type => $items]);

        return $this->getCart();
    }

    public function order(Request $request)
    {
        $order = new Order();
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;

        $numbersToString = [];
        $tariffsToString = $tariffIds = [];

        $numbersCart = collect(session('numbers_cart', []));
        $numbers = !empty($numbersCart) ? Number::find($numbersCart->keys()) : null;

        $order->numbers = implode(',', $numbersCart->keys()->toArray());

        foreach ($numbers as $number) {
            $numbersToString[] = $number->value;
            $number->saled = 1;
            $number->save();

            if (!config('app.debug')) {
                bookNumberInStore($number->value);
            }
        }

        $numbers = formatNumbers($numbers);

        $tariffs = collect(session('number_tariffs', []));
        $tariffs = formatTariffs($tariffs);

        $tariffsPrice = 0;
        foreach ($tariffs as $tariff) {
            $tariffsToString[] = $tariff->name;
            $tariffIds[] = $tariff->id;
            $tariffsPrice += $tariff->final_price;
        }

        $order->tariffs = implode(',', $tariffIds);
        $order->summ = $numbers->sum('final_price') + $tariffsPrice;
        $order->save();

        $crm = sendToCRM([
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'numbers' => implode(', ', $numbersToString),
            'tariffs' => implode(', ', $tariffsToString),
            'summ' => $order->summ
        ]);

        $this->clearCart();

        return $this->getCart();
    }

    public function clearCart()
    {
        session(['numbers_cart' => []]);
        //session(['tariffs_cart' => []]);
        session(['number_tariffs' => []]);
    }

    public function orderOneClick(Request $request)
    {
        $this->clearCart();
        $this->addNumberToCart($request);
        $this->order($request);

        return response()->json(['success' => true]);

    }

    public function changeTariff(Request $request)
    {
        $numberTariffs = session('number_tariffs', []);
        $numberTariffs[(int)$request->numberId] = Tariff::find($request->tariffId);
        session(['number_tariffs' => $numberTariffs]);

        return response()->json(['success' => 1]);
    }

}

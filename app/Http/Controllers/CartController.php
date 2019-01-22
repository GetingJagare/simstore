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
    public function getCart()
    {
        $numbersCart = collect(session('numbers_cart', []));
        $numberTariffs = collect(session('number_tariffs', []));

        /** @var Collection|null $numbers */
        $numbers = !empty($numbersCart) ? Number::find($numbersCart->keys()) : null;

        $numbers = formatNumbers($numbers);
        $numberTariffs = formatTariffs($numberTariffs);

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
            'price' => $price,
            'count' => cartCount()
        ]);
    }

    /**
     * @param Request $request
     * @param null|int $tariffId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNumberToCart(Request $request, $tariffId = null)
    {
        $numbersCart = session('numbers_cart', []);
        $number = Number::find($request->id);

        $numbersCart[$number->id] = [
            'id' => $number->id,
            'number' => $number->value
        ];

        session(['numbers_cart' => $numbersCart]);
        $this->setNumberTariff($number, $tariffId);

        return response()->json([
            'success' => true,
            'message' => 'Номер успешно добавлен в корзину.',
            'count' => cartCount(),
            'numbers' => getNumbersIdsInCart()
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Тариф успешно добавлен в корзину.',
            'count' => cartCount(),
            'tariffs' => getTariffsIdsInCart()
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $type = $request->type == 'tariff' ? 'tariffs_cart' : 'numbers_cart' ;
        $items = session($type, []);

        unset($items[$request->id]);
        session([$type => $items]);

        if ($request->type == 'number') {
            $this->removeNumberTariff($request->id);
        }

        return $this->getCart();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $numbersToString[$number->id] = $number->value;
            $number->saled = 1;
            $number->save();

            if (!config('app.debug')) {
                bookNumberInStore($number->value);
            }
        }

        $numbers = formatNumbers($numbers);

        $tariffsInSession = session('number_tariffs', []);
        $tariffs = formatTariffs(collect($tariffsInSession));

        foreach ($tariffsInSession as $numberId => $tariff) {
            if (isset($numbersToString[$numberId])) {
                $numbersToString[$numberId] .= ', Тариф:' . $tariff->name;
                $tariffIds[] = $tariff->id;
            }
        }

        $order->tariffs = implode(',', $tariffIds);
        $order->summ = $numbers->sum('final_price') + $tariffs->sum('final_price');
        $order->save();

        $data = [
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'numbers' => implode('; ', array_values($numbersToString)),
            'tariffs' => implode(', ', $tariffsToString),
            'summ' => $order->summ
        ];

        if (!empty($request->utm_tags)) {
            foreach ($request->utm_tags as $key => $value) {
                $data[$key] = $value;
            }
        }

        if (!empty($request->ga)) {
            foreach ($request->ga as $key => $value) {
                $data[$key] = $value;
            }
        }

        $crm = sendToCRM($data);

        $this->clearCart();

        return $this->getCart();
    }

    public function clearCart()
    {
        session(['numbers_cart' => []]);
        //session(['tariffs_cart' => []]);
        session(['number_tariffs' => []]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderOneClick(Request $request)
    {
        $this->clearCart();
        $this->addNumberToCart($request, $request->tariffId);
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

    /**
     * @param Number $number
     * @param int|null $tariffId
     *
     * @return void
     */
    public function setNumberTariff($number, $tariffId = null)
    {
        $numberTariffs = session('number_tariffs', []);

        if (!$tariffId) {
            $allTariffs = Cache::remember("all_tariffs", 1440, function () {
                return Tariff::query()->orderBy('price')->get();
            });

            foreach ($allTariffs as $tariff) {
                $numberPrice = json_decode($tariff->number_prices, true);
                if ($number->price > (float)$numberPrice[0] && $number->price <= (float)$numberPrice[1]) {
                    $numberTariffs[$number->id] = $tariff;
                    break;
                }
            }
        } else {
            $numberTariffs[$number->id] = Tariff::find($tariffId);
        }

        session(['number_tariffs' => $numberTariffs]);
    }

    /**
     * @param int $numberId
     *
     * @return void
     */
    public function removeNumberTariff($numberId)
    {
        $numberTariffs = session('number_tariffs', []);
        if (isset($numberTariffs[$numberId])) {
            unset($numberTariffs[$numberId]);
        }
        session(['number_tariffs' => $numberTariffs]);
    }

    /**
     * @param Request $request
     *
     * @return Tariff
     */
    public function getNumberTariff(Request $request)
    {
        $number = Number::find($request->id);
        $this->setNumberTariff($number);

        $tariff = session('number_tariffs')[$request->id];
        $tariff = formatTariffs(collect([$tariff]))[0];
        if ($request->isXmlHttpRequest()) {
            return response()->json(['tariff' => $tariff]);
        }

        return $tariff;
    }
}

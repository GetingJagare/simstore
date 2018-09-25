<?php

namespace App\Http\Controllers;

use App\Number;
use App\Order;
use App\Tariff;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct()
    {

    }


    public function getCart()
    {
        $numbersCart = collect(session('numbers_cart', []));
        $numbers = !empty($numbersCart) ? Number::find($numbersCart->keys()) : null;

        $numbers = formatNumbers($numbers);

        $tariffsCart = collect(session('tariffs_cart', []));
        $tariffs = !empty($tariffsCart) ? Tariff::find($tariffsCart->keys()) : null;

        $tariffs = formatTariffs($tariffs);

        if(isset($numbers) && !$numbers->min('price') > 0) {
            //dd($numbers);
        }

        $price = $numbers->sum('final_price') + $tariffs->sum('final_price');

        return response()->json(['success' => true, 'numbers' => $numbers, 'tariffs' => $tariffs, 'price' => $price, 'count' => cartCount()]);
    }

    public function addNumberToCart(Request $request)
    {
        $numbersCart = session('numbers_cart', []);
        $number = Number::find($request->id);

        $numbersCart[$number->id] = [
            'id' => $number->id,
            'number' => $number->value
        ];

        session(['numbers_cart' => $numbersCart]);

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
        $tariffsToString = [];

        $numbersCart = collect(session('numbers_cart', []));
        $numbers = !empty($numbersCart) ? Number::find($numbersCart->keys()) : null;

        $order->numbers = implode(',', $numbersCart->keys()->toArray());

        foreach ($numbers as $number) {
            $numbersToString[] = $number->value;
            $number->saled = 1;
            $number->save();

            bookNumberInStore($number->value);
        }

        $numbers = formatNumbers($numbers);

        $tariffsCart = collect(session('tariffs_cart', []));
        $tariffs = !empty($tariffsCart) ? Tariff::find($tariffsCart->keys()) : null;
        $tariffs = formatTariffs($tariffs);

        foreach ($tariffs as $tariff) {
            $tariffsToString[] = $tariff->name;
        }

        $order->tariffs = implode(',', $tariffsCart->keys()->toArray());
        $order->summ = $numbers->sum('final_price') + $tariffs->sum('final_price');
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
        session(['tariffs_cart' => []]);
    }

    public function orderOneClick(Request $request)
    {
        $this->clearCart();
        $this->addNumberToCart($request);
        $this->order($request);

        return response()->json(['success' => true]);

    }

}

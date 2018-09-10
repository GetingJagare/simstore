<?php

namespace App\Http\Controllers;

use App\Number;
use App\Order;
use App\Page;
use App\Region;
use App\Setting;
use App\Tariff;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function getLogin()
    {
        return view('admin.login');
    }

    public function getDashboard()
    {
        return view('admin.index');
    }

    /*
     * Добавление пользователя
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Пользователь успешно добавлен']);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function authUser(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Неверный E-mail или пароль'], 400);
    }

    public function addRegion(Request $request)
    {
        if($request->id) {
            $region = Region::find($request->id);
        } else {
            $region = new Region();
        }

        $region->name = $request->name;
        $region->name_pr = $request->name_pr;
        $region->city = $request->city;
        $region->subdomain = $request->subdomain;
        $region->codes = json_encode($request->codes);
        $region->save();

        return response()->json(['success' => true, 'message' => 'Регион успешно добавлен']);
    }

    public function getRegions()
    {
        $regions = Region::all();
        return response()->json(['success' => true, 'regions' => $regions]);
    }

    public function getRegion(Request $request)
    {
        $region = Region::find($request->id);
        $region->codes = empty($region->codes) ? [] : json_decode($region->codes);
        return response()->json(['success' => true, 'region' => $region]);
    }

    public function getPages()
    {
        $pages = Page::all();
        return response()->json(['success' => true, 'pages' => $pages]);
    }

    public function getPage(Request $request)
    {
        $page = Page::find($request->id);
        return response()->json(['success' => true, 'page' => $page]);
    }

    public function addPage(Request $request)
    {
        $page = new Page();
        $page->name = $request->name;
        $page->slug = str_slug($request->name);
        $page->save();

        return response()->json(['success' => true, 'message' => 'Страница успешно добавлена', 'id' => $page->id]);
    }

    public function editPage(Request $request)
    {
        $page = Page::find($request->id);
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->content = request('content');
        $page->save();

        return response()->json(['success' => true, 'message' => 'Страница успешно обновлена']);
    }

    public function getTariffs()
    {
        $tariffs = Tariff::all();
        return response()->json(['success' => true, 'tariffs' => $tariffs]);
    }

    public function getTariff(Request $request)
    {
        $tariff = Tariff::find($request->id);
        return response()->json(['success' => true, 'tariff' => $tariff]);
    }

    public function editTariff(Request $request)
    {
        $tariff = Tariff::find($request->id);
        $tariff->name = $request->name;
        $tariff->minutes = $request->minutes;
        $tariff->sms = $request->sms;
        $tariff->traffic = $request->traffic;
        $tariff->price = $request->price;
        $tariff->socials = $request->socials;
        $tariff->messengers = $request->messengers;
        $tariff->youtube = $request->youtube;
        $tariff->description = $request->description;
        $tariff->promo = $request->promo;
        $tariff->no_limit = $request->no_limit;
        $tariff->no_limit_ru = $request->no_limit_ru;
        $tariff->for_internet = $request->for_internet;
        $tariff->sale = $request->sale;
        $tariff->save();

        return response()->json(['success' => true, 'message' => 'Тариф успешно обновлен']);
    }

    public function addTariff(Request $request)
    {
        $tariff = new Tariff();
        $tariff->name = $request->name;
        $tariff->minutes = $request->minutes;
        $tariff->sms = $request->sms;
        $tariff->traffic = $request->traffic;
        $tariff->price = $request->price;
        $tariff->socials = $request->socials;
        $tariff->messengers = $request->messengers;
        $tariff->youtube = $request->youtube;
        $tariff->description = $request->description;
        $tariff->promo = $request->promo;
        $tariff->no_limit = $request->no_limit;
        $tariff->no_limit_ru = $request->no_limit_ru;
        $tariff->for_internet = $request->for_internet;
        $tariff->save();

        return response()->json(['success' => true, 'message' => 'Тариф успешно добавлен']);
    }

    public function getSettings()
    {
        $result = [];
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $setting;
        }

        return response()->json(['success' => true, 'settings' => $result]);
    }

    public function saveSettings(Request $request)
    {
        $settings = $request->settings;

        foreach ($settings as $setting) {

            $option = Setting::where('setting_key', '=', $setting['setting_key'])->first();
            $option->setting_value = $setting['setting_value'];
            $option->save();

        }

        dd($settings);
    }

    public function getNumbers(Request $request)
    {
        $discount = getSetting('catalog_numbers_discount');
        $numbers = Number::query();

        // По номеру
        if($request->search) {
            $numbers = $numbers->where('value', 'like', "%{$request->search}%");
        }

        // Скидка
        if($request->option == 'discount') {
            $numbers = $numbers->where('discount', '=', 1);
        }

        // На распродаже
        if($request->option == 'sale') {
            $numbers = $numbers->where('on_sale', '=', 1);
        }

        // Проданные
        if($request->option == 'saled') {
            $numbers = $numbers->where('saled', '=', 1);
        }

        // Цена
        $priceColumn = 'price_new';

        if($request->option == 'gold-partner' || $request->option == 'platinum-partner') {
            $priceColumn = 'price';
        }

        if($request->price_from || $request->price_to) {
            if($request->price_from != 'null' && $request->price_to != 'null') {
                $numbers = $numbers->whereBetween($priceColumn, [$request->price_from, $request->price_to]);
            } elseif($request->price_from != 'null') {
                $numbers = $numbers->where($priceColumn, '>=', $request->price_from);
            } elseif($request->price_to != 'null') {
                $numbers = $numbers->where($priceColumn, '<=', $request->price_to);
            }
        }

        // Пагинация
        $numbers = $numbers->paginate(25);

        // Добавляем скидку
        foreach ($numbers->items() as $number) {

            $number->discount_price = 0;

            if($number->discount) {
                $number->discount_price = abs(($number->price_new * $discount / 100) - $number->price_new);
            }
        }

        $numbers->appends(['search' => $request->search, 'option' => $request->option, 'price_from' => $request->price_from, 'price_to' => $request->price_to]);

        return response()->json(['success' => true, 'numbers' => $numbers]);
    }

    public function editNumber(Request $request)
    {
        $number = Number::find($request->id);
        $number->price_new = $request->price;
        $number->save();

        return response()->json(['success' => true, 'message' => 'Номер успешно изменен']);
    }

    public function gerOrders()
    {
        $orders = Order::orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'orders' => $orders]);
    }

    public function gerOrder(Request $request)
    {
        $order = Order::find($request->id);
        $order->numbers = Number::find(explode(',', $order->numbers));
        $order->tariffs = Tariff::find(explode(',', $order->tariffs));

        return response()->json(['success' => true, 'order' => $order]);
    }

    public function massEditNumbersPrice(Request $request)
    {
        $ids = $this->getNumberIds($request->numbers);

        $numbers = Number::find($ids);

        foreach ($numbers as $number) {
            $number->price_new = $request->price;
            $number->save();
        }

        return response()->json(['success' => true]);

    }

    public function setDiscountToNumbers(Request $request) {

        $ids = $this->getNumberIds($request->numbers);

        $numbers = Number::find($ids);

        foreach ($numbers as $number) {

            if($request->action == 'add-discount') {
                $number->discount = 1;
            } elseif($request->action == 'remove-discount') {
                $number->discount = 0;
            }

            $number->save();
        }

        $numbers = addDiscountPriceToNumbers($numbers);

        return response()->json(['success' => true, 'numbers' => $numbers->keyBy('id')]);

    }

    public function getNumberIds($arr) {

        $ids = [];

        foreach ($arr as $number) {
            $ids[] = $number['id'];
        }

        return $ids;
    }

    public function addToSale(Request $request) {

        $ids = $this->getNumberIds($request->numbers);
        $numbers = Number::find($ids);

        foreach ($numbers as $number) {

            if($request->action == 'sale-add') {
                $number->on_sale = 1;
            } elseif($request->action == 'sale-remove') {
                $number->on_sale = 0;
            }

            $number->save();
        }

        $numbers = addDiscountPriceToNumbers($numbers);

        return response()->json(['success' => true, 'numbers' => $numbers->keyBy('id')]);

    }

    public function setSaledNumbers(Request $request) {

        $ids = $this->getNumberIds($request->numbers);
        $numbers = Number::find($ids);

        foreach ($numbers as $number) {

            if($request->action == 'saled-add') {
                $number->saled = 1;
            } elseif($request->action == 'saled-remove') {
                $number->saled = 0;
            }

            $number->save();
        }

        $numbers = addDiscountPriceToNumbers($numbers);

        return response()->json(['success' => true, 'numbers' => $numbers->keyBy('id')]);
    }

    public function sendToCRM(Request $request) {

        return sendToCRM($request->fields);

    }

    public function testPoint() {

        $numbers = Number::query();
        $numbers = $numbers->where('value', 'LIKE', '%19__');

        dd($numbers->get());

    }
}

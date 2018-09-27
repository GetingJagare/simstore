<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test-point', 'AdminController@testPoint');

/*
Route::get('test', function () {

    $promos = [];
    $numbers = \App\Number::where(['block_po' => 0, 'saled' => 0])->where('price', '>', '0')->get()->random(5);

    foreach ($numbers as $number) {
        array_push($promos, $number->id);
    }

    $setting = \App\Setting::where('setting_key', 'promo_numbers')->first();
    $setting->setting_value = implode(',', $promos);
    $setting->save();

    dd($setting);

});

Route::get('test2', function () {

    $tariff = \App\Tariff::get()->random(1)->first();

    $setting = \App\Setting::where('setting_key', 'promo_tariffs')->first();
    $setting->setting_value = $tariff->id;
    $setting->save();

    dd($setting);

});*/

$httpHost = app('request')->getHttpHost();
$httpHostWithoutSubDomain = preg_replace('/^([^\.]+\.)([^\.]+\..*)$/', '$2', $httpHost);

function commonRoutes() {
    Route::get('numbers-promo', 'NumbersController@getPromo');
    Route::get('tariffs-promo', 'TariffsController@getPromo');
    Route::post('cart', 'CartController@getCart');
    Route::post('cart/order/one-click', 'CartController@orderOneClick');
    Route::post('cart/order', 'CartController@order');
    Route::post('cart/remove', 'CartController@removeFromCart');
    Route::post('profile', 'PageController@getProfile');
    Route::post('pass', 'PageController@getPass');
    Route::post('cart/add-number', 'CartController@addNumberToCart');
    Route::post('cart/add-tariff', 'CartController@addTariffToCart');
    Route::get('regions', 'AdminController@getRegions');
    Route::post('tariffs', 'TariffsController@search');
    Route::get('numbers', 'NumbersController@index');
    Route::post('numbers', 'NumbersController@search');

    Route::post('crm', 'AdminController@sendToCRM');

    Route::get('numbers/gold', 'PageController@getGoldNumbersPage');
    Route::get('numbers/platinum', 'PageController@getPlatinumNumbersPage');
    Route::get('numbers/promo', 'PageController@getPromoNumbersPage');
    Route::get('tarifs/unlimited', 'PageController@getUnlimitedTariffsPage');
    Route::get('tarifs/unlimited-ru', 'PageController@getUnlimitedRuTariffsPage');
    Route::get('tarifs/internet', 'PageController@getInternetTariffsPage');
    Route::get('{slug?}', 'PageController@get')->where('slug', '(.*)?')->name('page');
}

Route::domain("{region}.$httpHostWithoutSubDomain")->group(function () {
    commonRoutes();
});

/*
Route::domain('{region}.sim-store.syplex.ru')->group(function () {
    Route::get('{slug?}', function ($region, $slug = null) {
        return app()->make('App\Http\Controllers\PageController')->get($slug);
    })->name('page');
});*/


Route::get('admin/login', 'AdminController@getLogin')->name('login');
Route::post('admin/login', 'AdminController@authUser');

Route::middleware('auth')->group(function () {

    Route::get('admin', 'AdminController@getDashboard');
    Route::get('admin/users', 'AdminController@getUsers');
    Route::post('admin/users/add', 'AdminController@addUser');

    Route::get('admin/regions', 'AdminController@getRegions');
    Route::get('admin/regions/get', 'AdminController@getRegion');
    Route::post('admin/regions/add', 'AdminController@addRegion');

    Route::get('admin/pages', 'AdminController@getPages');
    Route::get('admin/pages/get', 'AdminController@getPage');
    Route::post('admin/pages/add', 'AdminController@addPage');
    Route::post('admin/pages/edit', 'AdminController@editPage');

    Route::get('admin/tariffs', 'AdminController@getTariffs');
    Route::get('admin/tariffs/get', 'AdminController@getTariff');
    Route::post('admin/tariffs/add', 'AdminController@addTariff');
    Route::post('admin/tariffs/edit', 'AdminController@editTariff');

    Route::get('admin/settings', 'AdminController@getSettings');
    Route::post('admin/settings/save', 'AdminController@saveSettings');

    Route::get('admin/numbers', 'AdminController@getNumbers');
    Route::post('admin/numbers/edit', 'AdminController@editNumber');
    Route::post('admin/numbers/edit-rental-price', 'AdminController@editNumberRentalPrice');
    Route::post('admin/numbers/mass-price-edit', 'AdminController@massEditNumbersPrice');
    Route::post('admin/numbers/discount', 'AdminController@setDiscountToNumbers');
    Route::post('admin/numbers/saled', 'AdminController@setSaledNumbers');
    Route::post('admin/numbers/sale', 'AdminController@addToSale');

    Route::get('admin/orders', 'AdminController@gerOrders');
    Route::get('admin/orders/get', 'AdminController@gerOrder');
});

commonRoutes();
Route::get('{slug?}', function ($slug) {
    return app()->make('\App\Http\Controllers\PageController')->redirectToRegionSubdomain($slug, 'moscow');
})->where('slug', '(.*)?');



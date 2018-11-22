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

$httpHost = app('request')->getHttpHost();
$httpHostWithoutSubDomain = preg_replace('/^([^\.]+\.)([^\.]+\..*)$/', '$2', $httpHost);

function commonRoutes() {
    Route::get('numbers-promo', 'NumbersController@getPromo');
    Route::get('tariffs-promo', 'TariffsController@getPromo');
    Route::post('cart', 'CartController@getCart');
    Route::post('cart/order/one-click', 'CartController@orderOneClick');
    Route::post('cart/order', 'CartController@order');
    Route::post('cart/remove', 'CartController@removeFromCart');
    Route::post('cart/add-number', 'CartController@addNumberToCart');
    Route::post('cart/add-tariff', 'CartController@addTariffToCart');
    Route::post('cart/change-tariff', 'CartController@changeTariff');
    Route::get('cart/get-number-tariff', 'CartController@getNumberTariff');

    Route::get('regions', 'AdminController@getRegions');
    Route::post('tariffs', 'TariffsController@search');
    Route::get('tariffs/other', 'TariffsController@otherTariffs');
    Route::get('nomera', 'NumbersController@index');
    Route::post('numbers', 'NumbersController@search');

    Route::post('crm', 'AdminController@sendToCRM');
    Route::post('upload', 'UploadController@index');

    Route::post('login', 'Auth\LoginController@index');
    Route::post('lostpass', 'Auth\LoginController@lostpass');
}

Route::domain("{region}.$httpHostWithoutSubDomain")->group(function () {
    commonRoutes();
    Route::get('tarify/dlja-zvonkov-po-{region_slug_pr}', function ($region, $region_slug_dat) {
        return app()->make('\App\Http\Controllers\PageController')->getRegionTariffsPage($region_slug_dat, $region);
    })->where('region_slug_pr', '((?!rossii).)*');
    Route::get('{slug?}', 'PageController@get')->where('slug', '(.*)?')->name('page');
});

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
    Route::post('admin/tariffs/delete', 'AdminController@deleteTariffs');

    Route::get('admin/settings', 'AdminController@getSettings');
    Route::post('admin/settings/save', 'AdminController@saveSettings');

    Route::get('admin/numbers', 'AdminController@getNumbers');
    Route::post('admin/numbers/edit', 'AdminController@editNumber');
    Route::post('admin/numbers/edit-rental-price', 'AdminController@editNumberRentalPrice');
    Route::post('admin/numbers/mass-price-edit', 'AdminController@massEditNumbersPrice');
    Route::post('admin/numbers/discount', 'AdminController@setDiscountToNumbers');
    Route::post('admin/numbers/saled', 'AdminController@setSaledNumbers');
    Route::post('admin/numbers/sale', 'AdminController@addToSale');
    Route::post('admin/numbers/import', 'AdminController@importNumbers');

    Route::get('admin/orders', 'AdminController@gerOrders');
    Route::get('admin/orders/get', 'AdminController@gerOrder');

	Route::post('admin/cache/clear', 'CacheController@index');
	Route::get('admin/cache/get-items', 'CacheController@getItems');
});

commonRoutes();
Route::get('tarify/dlja-zvonkov-po-{region_slug_pr}', function ($region_slug_dat) {
    return app()->make('\App\Http\Controllers\PageController')->getRegionTariffsPage($region_slug_dat);
})->where('region_slug_pr', '((?!rossii).)*');
Route::get('{slug?}', function ($slug = null) {
    return app()->make('\App\Http\Controllers\PageController')->get('moscow', $slug);
})->where('slug', '(.*)?');



<?php

namespace App\Http\Controllers;

use App\Page;
use App\Region;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class PageController extends Controller
{
    use SEOToolsTrait;

    public function get($region, $slug = null, $params = [])
    {
        $currentRegion = session('region', null);

        if(!$currentRegion || $region != $currentRegion['subdomain']) {
            $currentRegion = $this->setRegion($region);
        }

        $page = Page::where('slug', $slug ? $slug : '')->firstOrFail();

        // SEO
        $this->seo()->setTitle($page->seo_title ? str_replace('{region}', $currentRegion['name_pr'], $page->seo_title) : $page->name);

        return view($page->template ? $page->template : 'frontend.static', ['page' => $page, 'region' => $currentRegion, 'params' => $params]);
    }

    public function getProfile($region)
    {
        return $this->get($region, 'profile');
    }

    public function getPass($region)
    {
        return $this->get($region, 'pass');
    }

    public function redirectToRegionSubdomain($slug = null, $region = null)
    {
        // Знаем ли мы текущий город
        $current = $region ?: session('region', null);

        // Если да, сразу отправляем пользователя на поддомен региона
        // Если нет, то ищем местонахождение пользователя

        $region = $current ? (is_array($current) ? $current['subdomain'] : $current) : $this->setRegion()->subdomain;
        if ($region === 'moscow') {
            return $this->get($region, $slug);
        }

        return redirect(route('page', [
            'region' => $region,
            'slug' => $slug
        ]));
    }

    public function setRegion($region = null)
    {
        // Если нужно установить конкретный регион
        if($region) {
            $region = Region::where('subdomain', $region)->firstOrFail();

            // Записываем регион в сессию
            session(['region' => [
                'id' => $region->id,
                'name' => $region->name,
                'name_pr' => $region->name_pr,
                'city' => $region->city,
                'subdomain' => $region->subdomain,
                'created_at' => $region->created_at,
                'updated_at' => $region->updated_at
            ]]);

            return $region;
        }

        // Ищем местонахождение пользователя
        $geoip = geoip()->getLocation();

        // Проверяем, поддерживается ли его город
        $region = Region::where('name', $geoip['city'])->first();

        // Если нет, ставим Москву
        if(!$region) {
            $region = Region::where('city', 'Москва')->first();
        }

        // Записываем регион в сессию
        session(['region' => [
            'id' => $region->id,
            'name' => $region->name,
            'name_pr' => $region->name_pr,
            'city' => $region->city,
            'subdomain' => $region->subdomain,
            'created_at' => $region->created_at,
            'updated_at' => $region->updated_at
        ]]);

        return $region;
    }

    public function getGoldNumbersPage($region) {
        return $this->get($region, 'numbers/gold', ['price_range' => [10000, 100000]]);
    }

    public function getPlatinumNumbersPage($region) {
        return $this->get($region, 'numbers/platinum', ['price_range' => [100000, 900000]]);
    }

    public function getPromoNumbersPage($region) {
        return $this->get($region, 'numbers/promo', ['promo' => true]);
    }

    public function getUnlimitedTariffsPage($region) {
        return $this->get($region, 'tarifs/unlimited', ['unlimited' => true]);
    }

    public function getUnlimitedRuTariffsPage($region) {
        return $this->get($region, 'tarifs/unlimited-ru', ['unlimited_ru' => true]);
    }

    public function getInternetTariffsPage($region) {
        return $this->get($region, 'tarifs/internet', ['for_internet' => true]);
    }
}

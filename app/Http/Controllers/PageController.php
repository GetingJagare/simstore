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
        $page->name = preg_replace("/\s*\{region_dat\}/", " {$currentRegion['name_dat']}", $page->name);

        $toponym = (is_null($slug) ? '' : ' | ' . (!empty($currentRegion['city']) ? $currentRegion['city'] . ' и ' : '') . $currentRegion['name']);

        // SEO
        $this->seo()->setTitle(($page->seo_title ? str_replace('{region}', $currentRegion['name_pr'], $page->seo_title) : $page->name) . $toponym);
        $this->seo()->setDescription($page->seo_description);

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
            /** @var Region $region */
            $region = Region::where('subdomain', $region)->firstOrFail();

            // Записываем регион в сессию
            $region->writeToSession();

            return $region;
        }

        // Ищем местонахождение пользователя
        $geoip = geoip()->getLocation();

        // Проверяем, поддерживается ли его город
        $region = Region::where('name', $geoip['city'])->first();

        // Если нет, ставим Москву
        if(!$region) {
            /** @var Region $region */
            $region = Region::where('city', 'Москва')->first();
        }

        // Записываем регион в сессию
        $region->writeToSession();

        return $region;
    }

    public function getGoldNumbersPage($region = 'moscow') {
        return $this->get($region, 'nomera/zolotye', ['price_range' => [10000, 100000]]);
    }

    public function getPlatinumNumbersPage($region = 'moscow') {
        return $this->get($region, 'nomera/platinovye', ['price_range' => [100000, 900000]]);
    }

    public function getPromoNumbersPage($region = 'moscow') {
        return $this->get($region, 'nomera/aktsionnye', ['promo' => true]);
    }

    public function getUnlimitedTariffsPage($region = 'moscow') {
        return $this->get($region, 'tarify/bezlimitnye', ['unlimited' => true]);
    }

    public function getUnlimitedRuTariffsPage($region = 'moscow') {
        return $this->get($region, 'tarify/dlja-zvonkov-po-rossii', ['unlimited_ru' => true]);
    }

    public function getInternetTariffsPage($region = 'moscow') {
        return $this->get($region, 'tarify/internet', ['for_internet' => true]);
    }

    public function getRegionTariffsPage($region_slug_pr) {
        $currentRegion = session('region', null);
        if ($region_slug_pr !== str_slug($currentRegion['name_dat'])) {
            abort(404);
        }
        return $this->get($currentRegion['subdomain'], 'dlya-zvonkov-po');
    }
}

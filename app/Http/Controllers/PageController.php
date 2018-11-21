<?php

namespace App\Http\Controllers;

use App\Page;
use App\Region;
use App\Traits\RedirectTrait;

class PageController extends Controller
{
    use RedirectTrait;

    /**
     * @param string $region
     * @param null|string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get($region, $slug = null)
    {
        $currentRegion = session('region', null);

        if(!$currentRegion || $region != $currentRegion['subdomain']) {
            $currentRegion = $this->setRegion($region);
        }

        return Page::get($currentRegion, $slug);
    }

    public function setRegion($region = null)
    {
        return Region::set($region);
    }

    public function getRegionTariffsPage($region_slug_dat, $regionSlug = 'moscow') {
        /** @var Region $region */
        $region = Region::where('subdomain', $regionSlug)->firstOrFail();
        if (str_slug($region->name_dat) !== $region_slug_dat) {
            abort(404);
        }
        $region->writeToSession();
        return $this->get($region->subdomain, 'dlya-zvonkov-po');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

/**
 * Class Page
 * @package App
 */
class Page extends Model
{
    use SEOToolsTrait;

    /**
     * @param string $region
     * @param string|null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function get($region, $slug = null)
    {
        $page = self::where('slug', $slug ? $slug : '')->firstOrFail();
        $page->name = preg_replace("/\s*\{region_dat\}/", " {$region['name_dat']}", $page->name);

        $toponym = (is_null($slug) ? '' : ' | ' . (!empty($region['city']) ? $region['city'] . ' и ' : '') . $region['name']);

        foreach ($region['verif_codes'] as $meta) {
            $page->seo()->metatags()->addMeta($meta['meta_name'], $meta['meta_content']);
        }

        // SEO
        $page->seo()->setTitle(($page->seo_title ? str_replace(['{region}', '{region2}'], [$region['name_pr'], $region['name_dat']], $page->seo_title) : $page->name) . $toponym);
        $page->seo()->setDescription(str_replace(['{region}', '{region2}'], [$region['name_pr'], $region['name_dat']], $page->seo_description));

        $filterParams = json_decode($page->filters, true);
        $params = [];

        if (!isset($filterParams['promo']) || !$filterParams['promo']) {
            switch ($filterParams['name']) {
                case 'numbers':
                    $params['price_range'] = [];
                    foreach ($filterParams['value'] as $i => $value) {
                        $params['price_range'][$i] = (int)$value;
                    }
                    break;
                case 'tariffs':
                    foreach ($filterParams['value'] as $tariffType) {
                        $params[$tariffType] = true;
                    }
                    break;
            }
        } else {
            $params['promo'] = $filterParams['promo'];
        }

        $page->content = str_replace('{address}', $region['address'], $page->content);

        return view($page->template ? $page->template : 'frontend.static', ['page' => $page, 'region' => $region, 'params' => $params]);
    }
}

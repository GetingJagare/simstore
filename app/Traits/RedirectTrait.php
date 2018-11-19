<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.11.2018
 * Time: 11:07
 */

namespace App\Traits;

trait RedirectTrait
{
    /**
     * @param null|string $slug
     * @param null|string $region
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * @param null $region
     * @return Region|null
     */
    public static function set($region = null)
    {
        // Если нужно установить конкретный регион
        if($region) {
            /** @var Region $region */
            $region = self::where('subdomain', $region)->firstOrFail();

            // Записываем регион в сессию
            $region->writeToSession();

            return $region;
        }

        // Ищем местонахождение пользователя
        $geoip = geoip()->getLocation();

        // Проверяем, поддерживается ли его город
        $region = self::where('name', $geoip['city'])->first();

        // Если нет, ставим Москву
        if(!$region) {
            /** @var Region $region */
            $region = self::where('city', 'Москва')->first();
        }

        // Записываем регион в сессию
        $region->writeToSession();

        return $region;
    }

    public function writeToSession()
    {
        session(['region' => [
            'id' => $this->id,
            'name' => $this->name,
            'name_pr' => $this->name_pr,
            'name_dat' => $this->name_dat,
            'city' => $this->city,
            'subdomain' => $this->subdomain,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]]);
    }
}

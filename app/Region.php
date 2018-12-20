<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Region extends Model
{
    private static $defaultGeo = ['lat' => 55.7558224, 'lng' => 37.6159351];

    /**
     * @param null $region
     * @return Region|null
     */
    public static function set($region = null)
    {
        // Если нужно установить конкретный регион
        if ($region) {
            /** @var Region $region */
            $region = self::where('subdomain', $region)->firstOrFail();

            // Записываем регион в сессию
            return $region->writeToSession();
        }

        // Ищем местонахождение пользователя
        $geoip = geoip()->getLocation();

        // Проверяем, поддерживается ли его город
        $region = self::where('name', $geoip['city'])->first();

        // Если нет, ставим Москву
        if (!$region) {
            /** @var Region $region */
            $region = self::where('city', 'Москва')->first();
        }

        // Записываем регион в сессию
        return $region->writeToSession();
    }

    public function writeToSession()
    {
        $regionInSession = Cache::remember("region_{$this->subdomain}", 43200, function () {
            $geo = $this->geo ? json_decode($this->geo, true) : self::$defaultGeo;

            return [
                'id' => $this->id,
                'name' => $this->name,
                'name_pr' => $this->name_pr,
                'name_dat' => $this->name_dat,
                'city' => $this->city,
                'subdomain' => $this->subdomain,
                'address' => $this->address,
                'verif_codes' => !empty($this->verif_codes) ? json_decode($this->verif_codes, true) : [],
                'geo' => $geo,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        });

        session(['region' => $regionInSession]);

        return $regionInSession;
    }

    public static function getDefaultGeo()
    {
        return self::$defaultGeo;
    }
}

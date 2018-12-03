<?php

function formatStringToNumber($num)
{
    $numSplitted = preg_split('/[\.|,]/', $num);
    if (count($numSplitted) > 1) {
        return (float)($numSplitted[0] . "." . $numSplitted[1]);
    }
    return (int)$num;
}

function getNumbersIdsInCart()
{

    $collection = collect(session('numbers_cart', []));
    return $collection->count() > 0 ? $collection->keys() : [];

}

function getTariffsIdsInCart()
{

    $collection = collect(session('tariffs_cart', []));
    return $collection->count() > 0 ? $collection->keys() : [];

}

function addDiscountPriceToNumbers($numbers)
{

    //$discount = getSetting('catalog_numbers_discount');

    $discountType = \App\Setting::where('setting_key', 'numbers_discount_type')->first();

    foreach ($numbers as $number) {

        $number->discount_price = 0;

        if ($number->discount > 0) {
            if ($discountType->setting_value === 'percent') {
                $number->discount_price = round($number->price * (1 - $number->discount / 100));
            }
            if ($discountType->setting_value === 'rubles') {
                $number->discount_price = round($number->price - $number->discount);
            }
        }
    }

    return $numbers;
}


function getSetting($key)
{

    $setting = \App\Setting::where('setting_key', $key)->first();
    return $setting->setting_value;

}


function setSetting($key, $value)
{

    $setting = \App\Setting::where('setting_key', $key)->first();
    $setting->setting_value = $value;

    return $setting->save();

}

function maxNumbersPrice()
{

    return \Illuminate\Support\Facades\Cache::remember('numbers_max_price', 100, function () {

        $numbers = \App\Number::all();
        return $numbers->max('price');

    });
}

function cartCount()
{

    $numbersCart = session('numbers_cart', []);
    //$tariffsCart = session('tariffs_cart', []);

    return count($numbersCart);

}

/**
 * @param \Illuminate\Support\Collection $tariffs
 * @return mixed
 */
function formatTariffs($tariffs)
{
    $promoType = getSetting('tariffs_discount_type');
    $promoValue = getSetting('tariffs_discount');
    //$promoTariff = getSetting('promo_tariffs');

    return $tariffs->each(function ($tariff, $key) use ($promoType, $promoValue) {

        $tariff->final_price = $tariff->price;

        if ($tariff->sale) {

            if ($promoType == 'percent') {
                $tariff->final_price = abs(($tariff->final_price * $promoValue / 100) - $tariff->price);
            } else if ($promoType == 'rubles') {
                $tariff->final_price = ($tariff->price - $promoValue);
            }

            $tariff->is_promo = true;
        }

    });

}

function formatNumbers($numbers, $sale = false)
{

    // Наценка
    /* $markup = [];
     $settings = \App\Setting::where('setting_key', 'LIKE', '%markup%')->get();
     foreach ($settings as $setting) {

         if($setting->setting_key == 'markup') {
             $markup[$setting->setting_key] = floor($setting->setting_value);
         } else {
             $markup[$setting->setting_key] = $setting->setting_value;
         }
     }*/

    // Формат номера
    $formats = array(
        '10' => '+7 (###) ###-##-##'
    );

    //$salePercent = getSetting('catalog_numbers_sale');

    /*$promo_numbers = \App\Setting::where('setting_key', 'promo_numbers')->first();
    $promo_numbers = explode(',', $promo_numbers->setting_value);*/

    /*
    $discount = [];
    $discountSetting = \App\Setting::find([4,5]);
    foreach ($discountSetting as $item) {
        $discount[$item->setting_key] = $item->setting_value;
    }*/

    //dd($promo_numbers);

    $numbers = addDiscountPriceToNumbers($numbers);

    return $numbers->each(function ($number, $key) use ($formats, $sale) {

        $number->is_promo = false;
        $number->value = phone_format($number->value, $formats, '#');

        // Если наценка есть
        /*if($markup['markup'] > 0) {

            // Если наценка в рублях
            if($markup['markup_type'] == 'rubles') {
                $number->price_new = $number->price_new + $markup['markup'];

                // Если наценка в процентах
            } elseif($markup['markup_type'] == 'percent') {
                $number->price_new *= 1 . '.' . $markup['markup'];
                $number->price_new = floor($number->price_new);
            }
        }*/

        $number->final_price = $number->price_new;

        if ($number->discount_price) {
            $number->final_price = $number->discount_price;
            $number->is_promo = true;
        }

        /* if($number->on_sale) {
             $number->final_price = abs(($number->price_new * $salePercent / 100) - $number->price_new);
             //$number->is_promo = true;
         }*/

        /*
        if(in_array($number->id, $promo_numbers)) {

            if($discount['numbers_discount_type'] == 'percent') {
                $number->final_price = abs(($number->final_price * $discount['numbers_discount'] / 100) - $number->price_new);
            } else if($discount['numbers_discount_type'] == 'rubles') {
                $number->final_price = ($number->price_new - $discount['numbers_discount']);
            }

            $number->is_promo = true;
        }*/

    });

}

function phone_format($phone, $format, $mask = '#')
{
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (is_array($format)) {
        if (array_key_exists(strlen($phone), $format)) {
            $format = $format[strlen($phone)];
        } else {
            return false;
        }
    }

    $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

    $format = preg_replace_callback(
        str_replace('#', $mask, '/([#])/'),
        function () use (&$counter) {
            return '${' . (++$counter) . '}';
        },
        $format
    );

    return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
}

function sendToCRM($fields)
{

    $fields['entryPoint'] = 'MeetingsFromSite_simstore';
    $fields['key'] = 'C2Dq9Wx70DhxnjWJ3Aq8uNpF7sx9SvNvCdVd';
    //$fields['dev'] = config('app.debug') ? 'true' : null;
    $fields['dev'] = 'true';

    $url = 'https://cm.j-call.ru/index.php?' . http_build_query($fields);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);


    if (env('APP_DEBUG')) {
        //dd($response);
    }

    \Illuminate\Support\Facades\Log::info("Отправлен запрос в CRM: {$url}. Код ответа: {$status}");

    if ($status != 200) {
        return response()->json(['success' => false], $status);
    }

    return response()->json(['success' => true, 'url' => $url], $status);

}

function getRegionPhoneCodes($id)
{

    return \Illuminate\Support\Facades\Cache::remember("region_{$id}_c", 10080, function () use ($id) {
        $region = \App\Region::find($id);

        $codes = [];
        foreach (json_decode($region->codes) as $code) {
            $codes[] = $code->code;
        }

        return $codes;
    });

}

function getSimilarNumbers($numbers, $price_sort = 'asc')
{

    $result = [];

    foreach ($numbers as $number) {
        $group = [];
        $groupCost = 0;

        foreach ($numbers as $number2) {

            if (similar_text($number->value, $number2->value) > 7) {
                $group[] = $number2;
                $groupCost = $groupCost + $number2->price_new;
            }

        }

        $result[] = [
            'items' => $group,
            'cost' => $groupCost
        ];

    }

    $collection = collect($result);

    if ($price_sort == 'desc') {

        $collection = $collection->sortByDesc(function ($numbers) {
            return $numbers['cost'];
        });

    } else {

        $collection = $collection->sortBy(function ($numbers) {
            return $numbers['cost'];
        });

    }


    $result = [];
    foreach ($collection as $item) {
        foreach ($item['items'] as $number) {
            $result[$number->id] = $number;
        }
    }

    return collect($result);

}

/**
 * @param $number
 * @param bool $saled
 */
function bookNumberInStore($number, $saled = true)
{
    $curl = curl_init("http://188.65.210.23:8081/php/SimStorZakaz.php?phone=$number&sale=" . (int)$saled);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_exec($curl);
    curl_close($curl);
}

/**
 * @param string $type
 *
 * @return string
 */
function getUniqueId($type)
{
    return \Illuminate\Support\Facades\Cache::remember("unique_id_$type", 86400, function () {
        $symbolCodes = [range(97, 122), range(48, 57)];

        $string = '';
        for ($i = 1; $i <= 64; $i++) {
            $randomListIndex = rand(0, count($symbolCodes) - 1);
            $randomListLength = count($symbolCodes[$randomListIndex]);
            $randomCode = rand($symbolCodes[$randomListIndex][0], $symbolCodes[$randomListIndex][$randomListLength - 1]);

            $char = chr($randomCode);
            $upper = rand(0, 1);
            $string .= $upper < 0.5 ? mb_strtolower($char) : mb_strtoupper($char);
        }

        return $string;
    });
}
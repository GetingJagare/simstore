<?php

namespace App\Http\Controllers;

use App\Number;
use App\Region;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NumbersController extends Controller
{

    protected $mask = '#';

    public function index() {
        /*
        $numbers = Number::query();
        $numbers = $numbers->take(20);
        dd($numbers->get());*/
    }

    public function search(Request $request)
    {
        if (!$request->lucky) {
            $patterns = array(
                'AAAA'   => '/(\d)\1{3}/',
                'AABB'   => array(
                    '/(\d)\1(?!\1)([^\1])\2/',
                    '/(\d)\1([^\1])\2([^\1\2])\3/'
                ),
                'ABAB'   => array(
                    '/(\d)([^\1])(?:\1(?!\1)\2){1}/',
                    '/(\d)([^\1])\1\2\1\2/'
                ),
                'AABBCC' => '/(\d)\1([^\1])\2([^\1\2])\3/',
                'ABABAB' => '/(\d)([^\1])\1\2\1\2/',
                'ABCABC' => '/(\d)([^\1])([^\1\2])\1\2\3/'
            );

            /** @var Collection $numbers */
            $numbers = Number::query();

            if($request->birth) {
                $numbers = $numbers->where('value', 'LIKE', '%19__');
            }

            // Текущий регион
            $currentRegion = session('region', null);

            // Предверительная коллекция
            $numbers = $numbers
                ->where('block_po', '=', '0')
                ->where('saled', '=', '0')
                ->where('region_id', $currentRegion['id'])
                ->get();

            // Телефонные коды региона
            //$codes = getRegionPhoneCodes($currentRegion['id']);

            // Выводим только номера текущего региона
            /*$numbers = $numbers->each(function ($number, $key) use ($codes) {

                foreach ($codes as $code) {
                    $s = stripos($number->value, $code);
                    if(false !== $s && $s == 0) {
                        $number->regional = true;
                    }
                }

            })->filter(function ($number) {
                return $number->regional == true;
            });*/
            /*

            // Телефонные коды региона
            $codes = Cache::remember("region_{$currentRegion['id']}_codes", 100, function () use ($currentRegion) {
                $region = Region::find($currentRegion['id']);
                return json_decode($region->codes);
            });

            // Ищем только те номера, которые соответствуют региону
            if(!empty($codes)) {
                foreach ($codes as $code) {
                    $numbers = $numbers->where('value', 'LIKE', '%' . $code->code . '%', 'or');
                }
            }*/

            // Фильтр по цене
            $price = $request->price;
            if (!empty($price)) {
                $numbers = $numbers->filter(function ($item) use ($price) {
                    if (count($price) == 1) {
                        return $item->price == $price[0];
                    }
                    return $item->price >= $price[0] && $item->price <= $price[1];
                });
            }

            // Если в строке поиска паттерн, то фильтруем номера по регулярному выражению
            if(array_key_exists($request->search, $patterns)) {
                $pattern = $patterns[$request->search];

                $numbers = $numbers->filter(function ($num, $key) use ($pattern) {
                    $num->value = preg_replace('/\D/', '', $num->value);

                    if(is_array($pattern)) {
                        return (preg_match($pattern[0], $num->value) && !preg_match($pattern[1], $num->value));
                    }

                    return !!preg_match($pattern, $num->value);
                });
            }

            // Обычный поиск
            elseif(!empty($request->search)) {

                // Удаляем лишние символы
                $search = str_replace(['-', ' ', '(', ')', '+7'], '', $request->search);

                $searchPosition = $request->position_start ? "start"
                    : ($request->position_middle ? "middle"
                        : ($request->position_end ? "end" : null));

                $numbers = collect($numbers)->filter(function ($number) use ($search, $searchPosition) {

                    if($searchPosition) {
                        $length = strlen($search);

                        $searchInStart = substr($number->value, 0, $length) === $search;
                        $searchInEnd = substr($number->value, -$length) === $search;

                        if ($length) {
                            switch ($searchPosition) {
                                case 'start':
                                    return $searchInStart;
                                break;
                                case 'end':
                                    return $searchInEnd;
                                    break;
                                case 'middle':
                                    $searchInMiddle = stristr(substr($number->value, 1, -1), $search) !== false;
                                    return $searchInMiddle && !$searchInStart && !$searchInEnd;
                                    break;
                            }
                        }
                    }

                    // replace stristr with your choice of matching function
                    return false !== stristr($number->value, $search);
                });

            }

            // Параметры сортировки
            $sort = $request->sort;

            // Поиск похожих номеров
            if($request->similar) {
                $numbers = getSimilarNumbers($numbers, $sort['by']);
            }

            // Устанавливаем формат номера и добавлем наценку
            $numbers = formatNumbers($numbers);

            if($request->promo) {
                $numbers = $numbers->where('is_promo', true);
            }

            if(!$request->similar) {

                $numbers = $numbers->sortBy(function ($number, $key) use ($sort) {
                    return $number->{$sort['field']};
                });

                if($sort['by'] == 'desc') {
                    $numbers = $numbers->sortByDesc(function ($number, $key) use ($sort) {
                        return $number->{$sort['field']};
                    });
                }

            }

            $numbers = $numbers->values();
            $numbersCount = count($numbers);

            $numbers = $numbers->forPage($request->page, $request->perpage['value'])->all();
        } else {
            /** @var Collection $numbers */
            $numbers = getLuckyNumbers();
            $numbersCount = count($numbers);
            $numbers = $numbers->slice(($request->page - 1) * $request->perpage['value'], $request->perpage['value']);
        }

        return response()->json([
            'success' => true,
            'numbers' => $numbers,
            'perpage' => $request->perpage['value'],
            'count' => $numbersCount,
            'page' => $request->page]
        );
    }

    public function getPromo()
    {
        $now = Carbon::now();

        // Текущий регион
        $currentRegion = session('region');

        $data = Cache::remember('numbers_on_sale_' . $currentRegion['id'], $now->addHours(3), function () use ($currentRegion, $now) {

            $numbers = Number::where(['saled' => 0, 'region_id' => $currentRegion['id'], 'on_sale' => 1])->get();

            // Телефонные коды региона
            //$codes = getRegionPhoneCodes($currentRegion['id']);

            // Выводим только номера текущего региона
            /*$numbers = $numbers->each(function ($number, $key) use ($codes) {

                foreach ($codes as $code) {
                    $s = stripos($number->value, $code);
                    if(false !== $s && $s == 0) {
                        $number->regional = true;
                    }
                }

            })->filter(function ($number) {
                return $number->regional == true;
            });*/

            $numbers = formatNumbers($numbers, true);

            // Выбираем 5 рандомных номеров, если столько есть
            if($numbers->count() > 5) {
                $numbers = $numbers->random(5);
            }

            return [
                'numbers' => $numbers->all(),
                'date_end' => $now->format('F j, Y H:i')
            ];

        });

        return response()->json(['success' => true, 'numbers' => $data['numbers'], 'end' => $data['date_end']]);

    }

}

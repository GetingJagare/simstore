<?php

namespace App\Http\Controllers;

use App\Number;
use App\Setting;
use App\Tariff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TariffsController extends Controller
{

    public function search(Request $request)
    {

        $tariffs = Tariff::query();

        if($request->promo) {
            $tariffs = $tariffs->where('promo', 1);
        }

        if($request->no_limit) {
            $tariffs = $tariffs->orWhere('no_limit', 1);
        }

        if($request->no_limit_ru) {
            $tariffs = $tariffs->orWhere('no_limit_ru', 1);
        }

        if($request->for_internet) {
            $tariffs = $tariffs->orWhere('for_internet', 1);
        }

        if($request->minutes) {
            $tariffs = $tariffs->whereBetween('minutes', $request->minutes);
        }

        if($request->price) {
            $tariffs = $tariffs->whereBetween('price', $request->price);
        }

        if($request->traffic) {
            $tariffs = $tariffs->whereBetween('traffic', $request->traffic);
        }

        $tariffs = $tariffs->orderBy($request->sort['field'], $request->sort['by']);
        $tariffs = $tariffs->get();


        $tariffs = formatTariffs($tariffs);

        return response()->json(['success' => true, 'tariffs' => $tariffs]);

    }

    public function getPromo()
    {
        $tariff = Tariff::where('sale', 1)->get();
        $tariff = formatTariffs($tariff);
        $now = Carbon::now();

        $data = Cache::remember('tariffs_on_sale', $now->addDay(), function () use ($now) {

            return [
                'date_end' => $now->format('F j, Y H:i')
            ];

        });

        return response()->json(['success' => true, 'tariff' => $tariff->random(1)->first(), 'end' => $data['date_end']]);

    }

}

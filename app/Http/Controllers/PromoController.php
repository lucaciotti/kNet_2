<?php

namespace knet\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use knet\ArcaModels\Promo;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function idx(Request $req, $codCli = null)
    {
        $thisYear = Carbon::now()->year;
        $date = Carbon::now();
        $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();

        // if(RedisUser::get('ditta_DB')=='kNet_es' || (RedisUser::get('ditta_DB')=='kNet_it' && RedisUser::get('codag')=='002'))
        // {
        //    $endOfYear = new Carbon('last day of December 2018');
        // }

        $promoList = Promo::with(['product' => function ($q) {
                $q->select('codice', 'descrizion');
            }])
            // ->groupBy('descrizion')
            ->orderBy('codicearti')->get();
        
        dd($promoList);

        return view('promo.idx', [
            'promoList' => $promoList->groupBy('descrizion'),
            'thisYear' => $thisYear,
            'endOfYear' => $endOfYear,
        ]);
    }
}

<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use knet\Http\Requests;
use Torann\Registry\Facades\Registry;

use knet\ArcaModels\StatABC;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\SuperAgent;
use knet\ArcaModels\Nazione;

class StAbcController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function idxAg (Request $req, $codAg=null) {
      $agents = StatABC::distinct()->select('codag')
                          ->where('codag', '!=', '00')
                          ->where('codag', '!=', '')
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
      $agente = (!empty($codAg)) ? $codAg : $agents->first()->codag;
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear-1;
      $AbcProds = StatABC::select('articolo', 'codag',
                                  DB::raw('MAX(prodotto) as prodotto'),
                                  DB::raw('MAX(gruppo) as gruppo'),
                                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", qta, 0)) as qtaN'),
                                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", qta, 0)) as qtaN1')
                                )
                          ->where('codag', $agente)
                          ->where('isomaggio', false)
                          ->whereIn('esercizio', [''.$thisYear.'', ''.$prevYear.''])
                          ->groupBy(['articolo', 'codag'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'product' => function($query){
                              $query->select('codice', 'descrizion', 'unmisura');
                            }
                            ])
                          ->orderBy('qtaN', 'DESC')
                          ->get();

      return view('stAbc.idxAg', [
        'agents' => $agents,
        'agente' => $agente,
        'AbcProds' => $AbcProds,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
      ]);
    }
}

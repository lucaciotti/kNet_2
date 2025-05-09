<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use knet\Http\Requests;
use knet\Helpers\RedisUser;
use knet\Helpers\AgentFltUtils;

use knet\ArcaModels\StatABC;
use knet\ArcaModels\Client;
use knet\ArcaModels\Product;
use knet\ArcaModels\Agent;
use knet\ArcaModels\SuperAgent;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\SubGrpProd;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;
use knet\ArcaModels\Zona;
use knet\ArcaModels\Settore;

class StAbcController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }
    
    // idxAg -> non più utilizzato 
    public function idxAg (Request $req, $codAg=null) {
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear-1;
      $thisMonth = Carbon::now()->month;
      $dataFineAgente = Carbon::createFromDate( $prevYear, 1, 1);
      $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
      $codAg = ($req->input('codag')) ? $req->input('codag') : (!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $codAg);
      $agente = (!empty($codAg)) ? $codAg : $agents->first()->codice;
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $AbcProds = StatABC::select('articolo', 'codag',
                    DB::raw('MAX(prodotto) as prodotto'),
                    DB::raw('MAX(gruppo) as gruppo'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta, 0)) as qta_TY'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta, 0)) as qta_PY'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta1, 0)) as qta_TY_1'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta2, 0)) as qta_TY_2'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta3, 0)) as qta_TY_3'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta4, 0)) as qta_TY_4'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta5, 0)) as qta_TY_5'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta6, 0)) as qta_TY_6'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta7, 0)) as qta_TY_7'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta8, 0)) as qta_TY_8'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta9, 0)) as qta_TY_9'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta10, 0)) as qta_TY_10'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta11, 0)) as qta_TY_11'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta12, 0)) as qta_TY_12'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta1, 0)) as qta_PY_1'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta2, 0)) as qta_PY_2'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta3, 0)) as qta_PY_3'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta4, 0)) as qta_PY_4'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta5, 0)) as qta_PY_5'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta6, 0)) as qta_PY_6'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta7, 0)) as qta_PY_7'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta8, 0)) as qta_PY_8'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta9, 0)) as qta_PY_9'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta10, 0)) as qta_PY_10'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta11, 0)) as qta_PY_11'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta12, 0)) as qta_PY_12')
                    )
                    ->where('isomaggio', false)
                    ->whereIn('esercizio', [''.$thisYear.'', ''.$prevYear.'']);
      if(RedisUser::get('ditta_DB')=='kNet_es' && RedisUser::get('codag')=='A6'){
            $AbcProds = $AbcProds->where('gruppo', 'like', 'A%');
        } else {
            $AbcProds = $AbcProds->where('codag', $agente);
        }
      if($req->input('gruppo')) {
        $AbcProds = $AbcProds->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $AbcProds = $AbcProds->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $AbcProds = $AbcProds->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);
      }
      $AbcProds = $AbcProds->groupBy(['articolo', 'codag'])
                ->with([
                  'agent' => function($query){
                    $query->select('codice', 'descrizion');
                  }, 
                  'grpProd' => function($query){
                    $query->select('codice', 'descrizion');
                  }, 
                  'product' => function($query){
                    $query->select('codice', 'descrizion', 'unmisura');
                  }
                ])
                ->orderBy('qta_TY', 'DESC')
                ->get();
                
      $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();          

      return view('stAbc.idxAg', [
        'agents' => $agents,
        'agente' => $agente,
        'AbcProds' => $AbcProds,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
        'thisMonth' => $thisMonth,
        'gruppi' => $gruppi,
      ]);
    }

    // idxCli -> non più utilizzato 
    public function idxCli (Request $req, $codCli=null) {          
      $customers = StatAbc::select('codicecf')
                    ->whereHas('client')
                    ->with(['client'=>function($q){
                      $q->select('codice', 'descrizion');
                    }])
                    ->groupBy('codicecf')
                    ->orderBy('codicecf')->get();
      $codCli = ($req->input('codcli')) ? $req->input('codcli') : $codCli;
      $customer = (!empty($codCli)) ? $codCli : $customers->first()->codicecf;

      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear-1;
      $thisMonth = Carbon::now()->month;
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $AbcProds = StatABC::select('articolo', 'codicecf',
                    DB::raw('MAX(prodotto) as prodotto'),
                    DB::raw('MAX(gruppo) as gruppo'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta, 0)) as qta_TY'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta, 0)) as qta_PY'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta1, 0)) as qta_TY_1'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta2, 0)) as qta_TY_2'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta3, 0)) as qta_TY_3'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta4, 0)) as qta_TY_4'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta5, 0)) as qta_TY_5'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta6, 0)) as qta_TY_6'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta7, 0)) as qta_TY_7'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta8, 0)) as qta_TY_8'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta9, 0)) as qta_TY_9'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta10, 0)) as qta_TY_10'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta11, 0)) as qta_TY_11'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta12, 0)) as qta_TY_12'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta1, 0)) as qta_PY_1'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta2, 0)) as qta_PY_2'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta3, 0)) as qta_PY_3'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta4, 0)) as qta_PY_4'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta5, 0)) as qta_PY_5'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta6, 0)) as qta_PY_6'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta7, 0)) as qta_PY_7'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta8, 0)) as qta_PY_8'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta9, 0)) as qta_PY_9'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta10, 0)) as qta_PY_10'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta11, 0)) as qta_PY_11'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta12, 0)) as qta_PY_12')
                    )
                    ->where('codicecf', $customer)
                    ->where('isomaggio', false)
                    ->whereIn('esercizio', [''.$thisYear.'', ''.$prevYear.'']);
      if($req->input('grpPrdSelected')) {
        $AbcProds = $AbcProds->whereIn('gruppo', $req->input('grpPrdSelected'));
      }
      if(RedisUser::get('ditta_DB')=='kNet_es' && RedisUser::get('codag')=='A6'){
            $AbcProds = $AbcProds->where('gruppo', 'like', 'A%');
        }
      if (!empty($req->input('optTipoProd'))) {
        $AbcProds = $AbcProds->where('prodotto', $req->input('optTipoProd'));
      } else {
        $AbcProds = $AbcProds->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);
      }
      $AbcProds = $AbcProds->groupBy(['articolo', 'codicecf'])
                ->with([
                  'grpProd' => function($query){
                    $query->select('codice', 'descrizion');
                  }, 
                  'product' => function($query){
                    $query->select('codice', 'descrizion', 'unmisura');
                  }
                ])
                ->orderBy('qta_TY', 'DESC')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->get();
                
      $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();          

      return view('stAbc.idxCli', [
        'customers' => $customers,
        'customer' => $customer,
        'AbcProds' => $AbcProds,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
        'thisMonth' => $thisMonth,
        'gruppi' => $gruppi,
        'gruppo' => $req->input('grpPrdSelected'),
        'optTipoProd' => $req->input('optTipoProd'),
      ]);
    }

    public function idxArt(Request $req, $codAg = null)
    {
    // lista di tutti gli agenti attivi
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear - 1;
      $thisMonth = Carbon::now()->month;
      $dataFineAgente = Carbon::createFromDate( $prevYear, 1, 1);
      $agentList = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
      // $agentList = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();

      $codAg = ($req->input('codag')) ? $req->input('codag') : ($codAg ? array_wrap($codAg) : array_wrap($codAg));
      // $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $codAg));
      $fltAgents = AgentFltUtils::checkSpecialRules($codAg);
      
      $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
      $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
      $customerSelected = ($req->input('customerSelected')) ? $req->input('customerSelected') : null;
        
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $AbcProds = StatABC::select(
        'articolo',
        'codag',
        'codicecf',
        DB::raw('MAX(prodotto) as prodotto'),
        DB::raw('MAX(gruppo) as gruppo'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta, 0)) as qta_TY'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta, 0)) as qta_PY'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta1, 0)) as qta_TY_1'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta2, 0)) as qta_TY_2'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta3, 0)) as qta_TY_3'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta4, 0)) as qta_TY_4'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta5, 0)) as qta_TY_5'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta6, 0)) as qta_TY_6'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta7, 0)) as qta_TY_7'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta8, 0)) as qta_TY_8'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta9, 0)) as qta_TY_9'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta10, 0)) as qta_TY_10'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta11, 0)) as qta_TY_11'),
        DB::raw('SUM(IF(esercizio=' . $thisYear . ', qta12, 0)) as qta_TY_12'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta1, 0)) as qta_PY_1'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta2, 0)) as qta_PY_2'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta3, 0)) as qta_PY_3'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta4, 0)) as qta_PY_4'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta5, 0)) as qta_PY_5'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta6, 0)) as qta_PY_6'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta7, 0)) as qta_PY_7'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta8, 0)) as qta_PY_8'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta9, 0)) as qta_PY_9'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta10, 0)) as qta_PY_10'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta11, 0)) as qta_PY_11'),
        DB::raw('SUM(IF(esercizio=' . $prevYear . ', qta12, 0)) as qta_PY_12')
      )
        ->where('isomaggio', false)
        ->whereIn('esercizio', ['' . $thisYear . '', '' . $prevYear . '']);
      if ($req->input('grpPrdSelected')) {
        $AbcProds = $AbcProds->whereIn('gruppo', $req->input('grpPrdSelected'));
      }
      if(RedisUser::get('ditta_DB')=='kNet_es' && RedisUser::get('codag')=='A6'){
        $AbcProds = $AbcProds->where('gruppo', 'like', 'A%');
      } else {
        if(count($fltAgents)>0) $AbcProds = $AbcProds->whereIn('codag', $fltAgents);
      }
      if (!empty($req->input('optTipoProd'))) {
        $AbcProds = $AbcProds->where('prodotto', $req->input('optTipoProd'));
      } else {
        $AbcProds = $AbcProds->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);
      }
      if ($customerSelected){
         $AbcProds = $AbcProds->whereIn('codicecf', $customerSelected);
      }
      if ($settoreSelected != null) {
          $AbcProds->whereHas('client', function ($query) use ($settoreSelected){
          $query->where('settore', $settoreSelected);
        });
      }
      if ($zoneSelected != null) {
          $AbcProds->whereHas('client', function ($query) use ($zoneSelected){
          $query->where('zona', $zoneSelected);
        });
      }
      $AbcProds->whereHas('product', function ($query) {
        $query->where('u_artlis', true);
      });

      $AbcProdsList = $AbcProds->groupBy(['articolo'])
      ->with([
        'grpProd' => function ($query) {
          $query->select('codice', 'descrizion');
        },
        'product' => function ($query) {
          $query->select('codice', 'descrizion', 'unmisura');
        }
      ])
        ->orderBy('qta_TY', 'DESC')
        ->get();

      $AbcCustomers = $AbcProds->whereHas('client')
                    ->groupBy('codicecf')
                    ->orderBy('codicecf')->get();

      $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
      ->where('codice', 'NOT LIKE', 'DIC%')
        ->where('codice', 'NOT LIKE', '0%')
        ->where('codice', 'NOT LIKE', '2%')
        ->orderBy('codice')
        ->get();
      
      $customerList = Client::select('codice', 'descrizion', 'zona', 'settore')->whereIn('codice', $AbcCustomers->pluck('codicecf'))->get();
      $zoneList = Zona::whereIn('codice', $customerList->pluck('zona')->all())->get();
      $settoriList = Settore::whereIn('codice', $customerList->pluck('settore')->all())->get();

      return view('stAbc.idxArt', [
        'AbcProds' => $AbcProdsList,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
        'thisMonth' => $thisMonth,
        'gruppi' => $gruppi,
        'gruppo' => $req->input('grpPrdSelected'),
        'optTipoProd' => $req->input('optTipoProd'),
        'agentList' => $agentList,
        'fltAgents' => $fltAgents,
        'customerList' => $customerList,
        'customerSelected' => $customerSelected,
        'zoneList' => $zoneList,
        'settoriList' => $settoriList,
        'zoneSelected' => $zoneSelected,
        'settoreSelected' => $settoreSelected,
      ]);
    }

    public function detailArt (Request $req, $codArt, $codAg = null, $codZona = null) {
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear-1;
      $thisMonth = Carbon::now()->month;

      $isDetAg = !empty($req->input('codag')) || !empty($codAg);
      $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
      $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
      $customerSelected = ($req->input('customerSelected')) ? $req->input('customerSelected') : null;
      if($isDetAg){
        $dataFineAgente = Carbon::createFromDate( $prevYear, 1, 1);
        $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
        // $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $agente = (!empty($codAg)) ? $codAg : $agents->first()->codice;
        $descrAg = (!empty($agents->whereStrict('codice', $agente)->first()) ? $agents->whereStrict('codice', $agente)->first()->descrizion : "");
      }
      $isZona = !empty($req->input('codzona')) || !empty($codZona);
      if ($isZona) {
        $codZona = !empty($codZona) ? $codZona : $req->input('codzona');
      }
      // dd($agente);
      $codArt = !empty($codArt) ? $codArt : $req->input('codart');
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $AbcProds = StatABC::select('articolo', 'codag', 'codicecf',
                    DB::raw('MAX(prodotto) as prodotto'),
                    DB::raw('MAX(gruppo) as gruppo'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta, 0)) as qta_TY'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta, 0)) as qta_PY'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta1, 0)) as qta_TY_1'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta2, 0)) as qta_TY_2'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta3, 0)) as qta_TY_3'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta4, 0)) as qta_TY_4'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta5, 0)) as qta_TY_5'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta6, 0)) as qta_TY_6'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta7, 0)) as qta_TY_7'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta8, 0)) as qta_TY_8'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta9, 0)) as qta_TY_9'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta10, 0)) as qta_TY_10'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta11, 0)) as qta_TY_11'),
                    DB::raw('SUM(IF(esercizio='.$thisYear.', qta12, 0)) as qta_TY_12'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta1, 0)) as qta_PY_1'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta2, 0)) as qta_PY_2'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta3, 0)) as qta_PY_3'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta4, 0)) as qta_PY_4'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta5, 0)) as qta_PY_5'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta6, 0)) as qta_PY_6'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta7, 0)) as qta_PY_7'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta8, 0)) as qta_PY_8'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta9, 0)) as qta_PY_9'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta10, 0)) as qta_PY_10'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta11, 0)) as qta_PY_11'),
                    DB::raw('SUM(IF(esercizio='.$prevYear.', qta12, 0)) as qta_PY_12')
                  )
                  ->where('articolo', $codArt)
                  ->where('isomaggio', false)
                  ->whereIn('esercizio', ['' . $thisYear . '', '' . $prevYear . '']);
      if($isDetAg){
        $AbcProds->where('codag', $agente);
      }       
      
      if ($customerSelected){
         $AbcProds = $AbcProds->whereIn('codicecf', $customerSelected);
      }
      if ($settoreSelected != null) {
          $AbcProds->whereHas('client', function ($query) use ($settoreSelected){
            $query->where('settore', $settoreSelected);
          });
      }
      if ($zoneSelected != null) {
          $AbcProds->whereHas('client', function ($query) use ($zoneSelected){
            $query->where('zona', $zoneSelected);
          });
      }
                    
      if($isZona){
      $AbcProds->whereHas('client', function ($query) use ($codZona) {
          $query->select('codice', 'descrizion', 'zona')->where('zona', $codZona);
        });
      }
      $AbcProds->with([
                    'client' => function($query){
                      $query->select('codice', 'descrizion', 'zona')
                      ->withoutGlobalScope('agent')
                      ->withoutGlobalScope('superAgent')
                      ->withoutGlobalScope('client');
                    }, 
                    'grpProd' => function($query){
                      $query->select('codice', 'descrizion');
                    }, 
                    'product' => function($query){
                      $query->select('codice', 'descrizion', 'unmisura');
                    }
                  ]);
      
      $AbcProds = $AbcProds->groupBy(['codicecf'])
                    ->orderBy('qta_TY', 'DESC')
                    ->get();
                    
      $zone = Zona::whereIn('codice', $AbcProds->pluck('client')->pluck('zona')->all())->get();

      return view('stAbc.detailArt', [
        'agents' => $isDetAg ? $agents : null,
        'agente' => $isDetAg ? $agente : null,
        'descrAg' => $isDetAg ? $descrAg : null,
        'codArt' => $codArt,
        'AbcProds' => $AbcProds,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
        'thisMonth' => $thisMonth,
        'zone' => $zone,
        'codZona' => $isZona ? $codZona : null,
      ]);
    }

    public function docsArtCli (Request $req, $codArt, $codCli) {
      
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear-1;
      $thisMonth = Carbon::now()->month;

      // dati Cliente
      $customer = Client::select('codice', 'descrizion', 'agente')->where('codice', $codCli)
                        ->withoutGlobalScope('agent')
                        ->withoutGlobalScope('superAgent')
                        ->withoutGlobalScope('client')->first();

      // dati Articolo
      $product = Product::select('codice', 'descrizion')->where('codice', $codArt)->first();
      
      //Lista Documenti
      $listDocs = DocRow::select('id_testa', 'codicearti', 'unmisura', 'quantita', 'prezzoun', 'sconti', 'prezzotot')
                  ->where('codicearti', $codArt)
                  ->where('quantitare', '>', 0)
                  ->where('ommerce', 0)
                  ->whereHas('doccli', function($q) use ($codCli, $thisYear, $prevYear){
                    $q->where('codicecf', $codCli)
                      ->whereIn('esercizio', [$thisYear, $prevYear])
                      ->withoutGlobalScope('agent')
                      ->withoutGlobalScope('superAgent')
                      ->withoutGlobalScope('client');
                  })
                  ->with(['doccli' => function ($query) use ($codCli, $thisYear, $prevYear){
                    $query->select('id', 'tipodoc', 'datadoc', 'numerodoc', 'scontocass', 'agente')
                      ->withoutGlobalScope('agent')
                      ->withoutGlobalScope('superAgent')
                      ->withoutGlobalScope('client');
                  }])->get();
      // dd($listDoc);
      return view('stAbc.docsArtCli', [
        'customer' => $customer,
        'agente' => $customer->agente,
        'product' => $product,
        'listDocs' => $listDocs
      ]);
    }
}

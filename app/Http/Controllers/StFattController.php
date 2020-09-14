<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use knet\Http\Requests;
use knet\Helpers\RedisUser;

use knet\ArcaModels\StatFatt;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\SuperAgent;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\GrpProd;
use knet\Helpers\AgentFltUtils;

class StFattController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function idxAg (Request $req, $codAg=null) {
      $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
      $codAg = ($req->input('codag')) ? $req->input('codag') : ($codAg ? array_wrap($codAg) : $codAg);
      $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
      //$descrAg = (!empty($agents->whereStrict('codice', $agente)->first()) ? $agents->whereStrict('codice', $agente)->first()->descrizion : "");
      $thisYear = (string)(Carbon::now()->year);
      $fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);
      // dd($fltAgents);

      // (Legenda PY -> Previous Year ; TY -> This Year)
      $fat_TY = StatFatt::select('agente', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', 'CTOT')
                          ->whereIn('agente', $fltAgents)
                          ->where('esercizio', $thisYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fat_TY = $fat_TY->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fat_TY = $fat_TY->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fat_TY = $fat_TY->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET']);
      }          
      $fat_TY = $fat_TY->groupBy(['tipologia'])
                          ->get();
      // ->with([
      //   'agent' => function($query){
      //     $query->select('codice', 'descrizion');
      //   }
      //   ])
      // dd($fatTot);
      
      $prevYear = (string)((Carbon::now()->year)-1);
      $fat_PY = StatFatt::select('agente', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', 'CTOT')
                          ->whereIn('agente', $fltAgents)
                          ->where('esercizio', $prevYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fat_PY = $fat_PY->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fat_PY = $fat_PY->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fat_PY = $fat_PY->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET']);
      }          
      $fat_PY = $fat_PY->groupBy(['tipologia'])
                          ->get();

      $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();

      $prevMonth = (Carbon::now()->month);
      $valMese = 'valore' . $prevMonth;
      if(!empty($fat_TY->first())){
        $prevMonth = ($fat_TY->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth;
      }
      $stats = $this->makeFatTgtJson($fat_TY, $fat_PY, $prevMonth);
      // dd($stats);
      return view('stFatt.idxAg', [
        'agentList' => $agentList,
        'fltAgents' => $fltAgents,
        'fat_TY' => $fat_TY,
        //'fatDet' => $fatDet,
        'fat_PY' => $fat_PY,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
        'gruppi' => $gruppi,
        'grpSelected' => $req->input('gruppo'),
        'descrAg' => '',
        'thisYear' => $thisYear,
        'prevYear' => $prevYear
      ]);
    }

    public function idxCli (Request $req, $codCli=null) {
      $clients = StatFatt::select('codicecf')
                          ->where('codicecf', '!=', 'CTOT')
                          ->whereHas('client')
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }
                          ])
                          ->groupBy('codicecf')
                          ->get();
      $codCli = ($req->input('codcli')) ? $req->input('codcli') : $codCli;
      $cliente = (!empty($codCli)) ? $codCli : $clients->first()->codicecf;

      $thisYear = (string)(Carbon::now()->year);
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $fat_TY = StatFatt::select('agente', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', $cliente)
                          ->where('esercizio', $thisYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fat_TY = $fat_TY->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fat_TY = $fat_TY->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fat_TY = $fat_TY->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET']);
      }          
      $fat_TY = $fat_TY->groupBy(['codicecf', 'tipologia'])
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }
                            ])
                          ->get();

      $prevYear = (string)((Carbon::now()->year)-1);
      $fat_PY = StatFatt::select('agente', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', $cliente)
                          ->where('esercizio', $prevYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fat_PY = $fat_PY->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fat_PY = $fat_PY->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fat_PY = $fat_PY->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA']);
      }          
      $fat_PY = $fat_PY->groupBy(['codicecf', 'tipologia'])
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }
                            ])
                          ->withoutGlobalScope('agent')
                          ->withoutGlobalScope('superAgent')
                          ->get();
      
      $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();

      $prevMonth = (Carbon::now()->month);
      $valMese = 'valore' . $prevMonth;
      $prevMonth = $fat_TY->isEmpty() ? $prevMonth : (($fat_TY->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth);
      $stats = $this->makeFatTgtJson($fat_TY, $fat_PY, $prevMonth);
      // dd($stats);
      // dd($clients->first());
      return view('stFatt.idxCli', [
        'clients' => $clients,
        'cliente' => $cliente,
        'fat_TY' => $fat_TY,
        'fat_PY' => $fat_PY,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
        'gruppi' => $gruppi,
        'grpSelected' => $req->input('gruppo'),
        'thisYear' => $thisYear,
        'prevYear' => $prevYear
      ]);
    }

    public function idxManager (Request $req, $codManager=null) {
      $managers = SuperAgent::distinct()->select('codice', 'descrizion')
                          ->get();
      $codManager = ($req->input('manager')) ? $req->input('manager') : $codManager;
      $manager = (!empty($codManager)) ? $codManager : $managers->first()->codice;
      $fatDet = StatFatt::select('tipologia', 'gruppo',
                                  DB::raw('MAX(prodotto) as prodotto'),
                                  DB::raw('MAX(LEFT(gruppo,1)) as grp'),
                                  DB::raw('SUM(valore1) as valore1'),
                                  DB::raw('SUM(valore2) as valore2'),
                                  DB::raw('SUM(valore3) as valore3'),
                                  DB::raw('SUM(valore4) as valore4'),
                                  DB::raw('SUM(valore5) as valore5'),
                                  DB::raw('SUM(valore6) as valore6'),
                                  DB::raw('SUM(valore7) as valore7'),
                                  DB::raw('SUM(valore8) as valore8'),
                                  DB::raw('SUM(valore9) as valore9'),
                                  DB::raw('SUM(valore10) as valore10'),
                                  DB::raw('SUM(valore11) as valore11'),
                                  DB::raw('SUM(valore12) as valore12')
                                )
                          ->where('codicecf', '!=', 'CTOT')
                          ->whereHas('agent', function ($query) use ($manager) {
                              $query->where('u_capoa', $manager);
                            })
                          ->where('tipologia', 'FATTURATO')
                          ->where('esercizio', '2017')
                          ->groupBy(['tipologia', 'gruppo'])
                          ->with([
                            'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      $fatTot = StatFatt::select('tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', '!=', 'CTOT')
                          ->whereHas('agent', function ($query) use ($manager) {
                              $query->where('u_capoa', $manager);
                            })
                          ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA'])
                          ->where('tipologia', 'FATTURATO')
                          ->groupBy(['tipologia'])
                          ->get();
      $target = StatFatt::select(
                                  'tipologia', 'gruppo',
                                  DB::raw('SUM(valore1) as valore1'),
                                  DB::raw('SUM(valore2) as valore2'),
                                  DB::raw('SUM(valore3) as valore3'),
                                  DB::raw('SUM(valore4) as valore4'),
                                  DB::raw('SUM(valore5) as valore5'),
                                  DB::raw('SUM(valore6) as valore6'),
                                  DB::raw('SUM(valore7) as valore7'),
                                  DB::raw('SUM(valore8) as valore8'),
                                  DB::raw('SUM(valore9) as valore9'),
                                  DB::raw('SUM(valore10) as valore10'),
                                  DB::raw('SUM(valore11) as valore11'),
                                  DB::raw('SUM(valore12) as valore12')
                                )
                          ->where('codicecf', '!=', 'CTOT')
                          ->whereHas('agent', function ($query) use ($manager) {
                              $query->where('u_capoa', $manager);
                            })
                          ->where('tipologia', 'TARGET')
                          ->where('esercizio', '2017')
                          ->groupBy(['tipologia'])
                          ->with(['grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      // dd($target);
      $prevMonth = (Carbon::now()->month);
      $valMese = 'valore' . $prevMonth;
      $prevMonth = $fatTot->isEmpty() ? $prevMonth : (($fatTot->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth);
      $stats = $this->makeFatTgtJson($fatTot, $target, $prevMonth);
      // dd($stats);
      // dd($clients->first());
      return view('stFatt.idxManager', [
        'agents' => $managers,
        'agente' => $manager,
        'fatTot' => $fatTot,
        'fatDet' => $fatDet,
        'target' => $target,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
      ]);
    }

    public function idxZone (Request $req, $codAg=null) {
      $agents = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
      $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
      $agente = (string)(!empty($codAg)) ? $codAg : (!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice);
      $descrAg = (!empty($agents->whereStrict('agente', $agente)->first()->agent) ? $agents->whereStrict('agente', $agente)->first()->agent->descrizion : "");
      $thisYear = (string)(Carbon::now()->year);
      $prevYear = (string)((Carbon::now()->year)-1);

      // PRENDO FATTURATO dell'Anno in corso 
      // (Legenda PY -> Previous Year ; TY -> This Year)
      $fatZone = StatFatt::select('codicecf',
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                  )
                  ->where('agente', $agente)->where(DB::raw('LENGTH(agente)'), strlen($agente))
                  ->whereIn('esercizio', [$thisYear, $prevYear])
                  ->where('codicecf', "!=", 'CTOT')
                  ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fatZone = $fatZone->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fatZone = $fatZone->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fatZone = $fatZone->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET']);
      }          
      $fatZone = $fatZone->groupBy(['codicecf'])
                  ->with([ 
                    'client' => function($query){
                      $query->select('codice', 'descrizion', 'settore', 'zona')
                      ->with(['detZona', 'detSect']);
                    }
                    ])
                  ->get();
      $fatZone = $fatZone->groupBy('client.zona');
      // dd($fatTot); , 'client.settore'
      
      $fatTot = StatFatt::select('codicecf',
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                  DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                  DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                  )
                  ->where('agente', $agente)->where(DB::raw('LENGTH(agente)'), strlen($agente))
                  ->whereIn('esercizio', [$thisYear, $prevYear])
                  ->where('codicecf', 'CTOT')
                  ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fatTot = $fatTot->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fatTot = $fatTot->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fatTot = $fatTot->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET']);
      }          
      $fatTot = $fatTot->groupBy(['codicecf'])->get();

      $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();

      return view('stFatt.idxZone', [
        'agents' => $agents,
        'agente' => $agente,
        'fatZone' => $fatZone,
        'fatTot' => $fatTot->first(),
        'thisYear' => $thisYear,
        'prevYear' => $prevYear,
        'gruppi' => $gruppi,
        'grpSelected' => $req->input('gruppo'),
        'descrAg' => $descrAg,
      ]);
    }



    //Costruisce Collection JSON per Grafico.
    protected function makeFatTgtJson($fat, $tgt, $mese){
      $mese=12;
      $collect = collect([]);
      $fatM = 0;
      $tgtM = 0;
      for($i=1; $i<=$mese; $i++){
        $valMese = 'valore' . $i;
        $fatM += round($fat->isEmpty() ? 0 : $fat->first()->$valMese, 0);
        $tgtM += round($tgt->isEmpty() ? 0 : $tgt->first()->$valMese, 0);
        $dt = Carbon::createFromDate(null, $i, 1);
        $data = [
          'm' => $dt->year.'-'.$dt->month,
          'a' => $fatM,
          'b' => $tgtM
        ];
        $collect->push($data);
      }
      // dd($collect);
      return $collect->toJSON();
    }

}


/* $fatDet = StatFatt::select('agente', 'tipologia', 'gruppo',
                                  DB::raw('MAX(prodotto) as prodotto'),
                                  DB::raw('MAX(LEFT(gruppo,1)) as grp'),
                                  DB::raw('SUM(valore1) as valore1'),
                                  DB::raw('SUM(valore2) as valore2'),
                                  DB::raw('SUM(valore3) as valore3'),
                                  DB::raw('SUM(valore4) as valore4'),
                                  DB::raw('SUM(valore5) as valore5'),
                                  DB::raw('SUM(valore6) as valore6'),
                                  DB::raw('SUM(valore7) as valore7'),
                                  DB::raw('SUM(valore8) as valore8'),
                                  DB::raw('SUM(valore9) as valore9'),
                                  DB::raw('SUM(valore10) as valore10'),
                                  DB::raw('SUM(valore11) as valore11'),
                                  DB::raw('SUM(valore12) as valore12')
                                )
                          ->where('codicecf', 'CTOT')
                          ->where('agente', $agente)
                          ->where('esercizio', $thisYear);
      if($req->input('gruppo')) {
        $fatDet = $fatDet->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fatDet = $fatDet->where('prodotto', $req->input('optTipoDoc'));
      } else {
        $fatDet = $fatDet->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA']);
      }                   
      $fatDet = $fatDet->where('tipologia', 'FATTURATO')
                          ->groupBy(['agente', 'tipologia', 'gruppo'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get(); */
/* $target = StatFatt::where('codicecf', 'CTOT')
                          ->where('agente', $agente)
                          ->where('esercizio', '2017')
                          ->where('tipologia', 'TARGET')
                          ->groupBy(['agente', 'tipologia', 'gruppo'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get(); */
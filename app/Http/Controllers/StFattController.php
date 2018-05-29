<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use knet\Http\Requests;

use knet\ArcaModels\StatFatt;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\SuperAgent;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\GrpProd;

class StFattController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function idxAg (Request $req, $codAg=null) {
      $agents = StatFatt::select('agente')
                          ->where('agente', '!=', '00')
                          ->where('agente', '!=', '')
                          ->groupBy('agente')
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
      // dd($req->input());
      $agente = (string)(!empty($codAg)) ? $codAg : $agents->first()->agente;
      $descrAg = (!empty($agents->whereStrict('agente', $agente)->first()->agent) ? $agents->whereStrict('agente', $agente)->first()->agent->descrizion : "");
      $thisYear = (string)(Carbon::now()->year);
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
      $fatTot = StatFatt::select('agente', 'tipologia',
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
                          ->where('agente', $agente)->where(DB::raw('LENGTH(agente)'), strlen($agente))
                          ->where('esercizio', $thisYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $fatTot = $fatTot->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $fatTot = $fatTot->where('prodotto', $req->input('optTipoDoc'));
      } /* else {
        $fatTot = $fatTot->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA']);
      } */          
      $fatTot = $fatTot->groupBy(['agente', 'tipologia'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      // dd($fatTot);
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
      $prevYear = (string)((Carbon::now()->year)-1);
      $target = StatFatt::select('agente', 'tipologia',
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
                          ->where('agente', $agente)->where(DB::raw('LENGTH(agente)'), strlen($agente))
                          ->where('esercizio', $prevYear)
                          ->where('tipologia', 'FATTURATO');
      if($req->input('gruppo')) {
        $target = $target->whereIn('gruppo', $req->input('gruppo'));
      }
      if(!empty($req->input('optTipoDoc'))) {
        $target = $target->where('prodotto', $req->input('optTipoDoc'));
      }          
      $target = $target->groupBy(['agente', 'tipologia'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();

      $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();

      $prevMonth = (Carbon::now()->month);
      $valMese = 'valore' . $prevMonth;
      if(!empty($fatTot->first())){
        $prevMonth = ($fatTot->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth;
      }
      $stats = $this->makeFatTgtJson($fatTot, $target, $prevMonth);
      // dd($stats);
      return view('stFatt.idxAg', [
        'agents' => $agents,
        'agente' => $agente,
        'fatTot' => $fatTot,
        //'fatDet' => $fatDet,
        'target' => $target,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
        'gruppi' => $gruppi,
        'grpSelected' => $req->input('gruppo'),
        'descrAg' => $descrAg,
        'thisYear' => $thisYear,
        'prevYear' => $prevYear
      ]);
    }

    public function idxCli (Request $req, $codCli=null) {
      $clients = StatFatt::select('codicecf')
                          ->where('codicecf', '!=', 'CTOT')
                          ->groupBy('codicecf')
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }
                            ])
                          ->get();
      $codCli = ($req->input('codcli')) ? $req->input('codcli') : $codCli;
      $cliente = (!empty($codCli)) ? $codCli : $clients->first()->codicecf;
      $fatDet = StatFatt::select('codicecf', 'tipologia', 'gruppo',
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
                          ->where('codicecf', $cliente)
                          ->where('tipologia', 'FATTURATO')
                          ->where('esercizio', '2017')
                          ->groupBy(['codicecf', 'tipologia', 'gruppo'])
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      $fatTot = StatFatt::select('codicecf', 'tipologia',
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
                          ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA'])
                          ->where('tipologia', 'FATTURATO')
                          ->where('esercizio', '2017')
                          ->groupBy(['codicecf', 'tipologia'])
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }
                            ])
                          ->get();
      $target = StatFatt::where('codicecf', $cliente)
                          ->where('tipologia', 'TARGET')
                          ->where('esercizio', '2017')
                          ->groupBy(['codicecf', 'tipologia', 'gruppo'])
                          ->with([
                            'client' => function($query){
                              $query->select('codice', 'descrizion')
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                          ->get();
      $prevMonth = (Carbon::now()->month);
      $valMese = 'valore' . $prevMonth;
      $prevMonth = $fatTot->isEmpty() ? $prevMonth : (($fatTot->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth);
      $stats = $this->makeFatTgtJson($fatTot, $target, $prevMonth);
      // dd($stats);
      // dd($clients->first());
      return view('stFatt.idxCli', [
        'clients' => $clients,
        'cliente' => $cliente,
        'fatTot' => $fatTot,
        'fatDet' => $fatDet,
        'target' => $target,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
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

    public function idxZone (Request $req, $codZone=null) {
      $zones = Nazione::select('codice', 'descrizion')
                          ->whereHas('client', function($query){})
                          ->get();
      $codZone = ($req->input('zone')) ? $req->input('zone') : $codZone;
      $zone = (!empty($codZone)) ? $codZone : $zones->first()->codice;
      // // TO DELETE
      // $zoneClients=StatFatt::distinct()->select('codicecf')->where('codicecf', '!=','CTOT')
      //                       ->where('agente', '!=','00')
      //                       ->whereHas('client', function ($query) use ($zone) {
      //                       $query->where('codnazione', 'I');
      //                       })
      //                       ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA'])
      //                       ->where('tipologia', 'FATTURATO')->get();
      // $mauroClients=StatFatt::distinct()->select('codicecf')
      //                       ->where('codicecf','!=', 'CTOT')
      //                       ->whereHas('agent', function ($query) {
      //                           $query->where('u_capoa', '002');
      //                         })
      //                       ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA'])
      //                       ->where('tipologia', 'FATTURATO')->get();
      // $coll1=collect($zoneClients->pluck('codicecf')->all());
      // $coll2=collect($mauroClients->pluck('codicecf')->all());
      // dd($coll1->diff($coll2)->all());
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
                          ->where('codicecf', '!=','CTOT')
                          ->where('agente', '!=','00')
                          ->whereHas('client', function ($query) use ($zone) {
                              $query->where('codnazione', $zone);
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
                          ->where('codicecf', '!=','CTOT')
                          ->where('agente', '!=','00')
                          ->where('esercizio', '2017')
                          ->whereHas('client', function ($query) use ($zone) {
                              $query->where('codnazione', $zone);
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
                          ->where('codicecf', '!=','CTOT')
                          ->where('agente', '!=','00')
                          ->where('esercizio', '2017')
                          ->whereHas('client', function ($query) use ($zone) {
                              $query->where('codnazione', $zone);
                            })
                          ->where('tipologia', 'TARGET')
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
      return view('stFatt.idxZone', [
        'agents' => $zones,
        'agente' => $zone,
        'fatTot' => $fatTot,
        'fatDet' => $fatDet,
        'target' => $target,
        'stats' => $stats,
        'prevMonth' => $prevMonth,
      ]);
    }

    protected function makeFatTgtJson($fat, $tgt, $mese){
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

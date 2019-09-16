<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use knet\Http\Requests;
use knet\ArcaModels\ScadCli;
use knet\ArcaModels\Agent;

use knet\Helpers\RedisUser;

class ScadCliController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

  public function index (Request $req){
    $thisYear = Carbon::now()->year;
    $startDate = Carbon::createFromDate($thisYear-1, 1, 1);
    $endDate = Carbon::now();
    $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();

    $scads = ScadCli::select('id', 'id_doc', 'numfatt', 
              'datafatt', 'datascad', 'codcf', 'tipomod', 
              'tipo', 'insoluto', 'u_insoluto', 'pagato', 
              'impeffval', 'importopag', 'idragg', 'tipoacc',
              'impprovlit', 'impprovliq', 'liquidate'
            );
    $scads = $scads->whereBetween('datascad', array($startDate, $endDate))->whereIn('tipoacc', ['F', '']);
    if(RedisUser::get('role')!='client'){
      $scads = $scads->whereRaw("(`insoluto` = 1 OR `u_insoluto` = 1) AND `pagato` = 0");
    }
    $scads = $scads->whereHas('client', function($query){
      $query->whereNotIn('statocf', ['C', 'S', 'L'])
            ->withoutGlobalScope('agent')
            ->withoutGlobalScope('superAgent')
            ->withoutGlobalScope('client');
          });
    $scads = $scads->with(array('client' => function($query) {
      $query->select('codice', 'descrizion')
            ->withoutGlobalScope('agent')
            ->withoutGlobalScope('superAgent')
            ->withoutGlobalScope('client');
    }));
    $scads = $scads->orderBy('datascad', 'desc')->orderBy('id', 'desc')->get();
    // dd($scads);

    return view('scads.index', [
      'scads' => $scads,
      'startDate' => $startDate,
      'endDate' => $endDate,
      'agentList' => $agentList
    ]);
  }

  public function fltIndex (Request $req){
    // dd($req);
    $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
    $scads = ScadCli::select('id', 'id_doc', 'numfatt', 
              'datafatt', 'datascad', 'codcf', 'tipomod', 
              'tipo', 'insoluto', 'u_insoluto', 'pagato', 
              'impeffval', 'importopag', 'idragg', 'tipoacc',
              'impprovlit', 'impprovliq', 'liquidate'
            );
    $scads = $scads->whereIn('tipoacc', [$req->input('optRaggr'), '']);
    $scads = $scads->whereIn('tipo', $req->input('chkPag'));
    if($req->input('startDate') && $req->input('noDate')!='C'){
      $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
      $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
      $scads = $scads->whereBetween('datascad', [$startDate, $endDate]);
    } else {
      $startDate = null;
      $endDate = null;
    }
    if($req->input('ragsoc')) {
      $ragsoc = strtoupper($req->input('ragsoc'));
      if($req->input('ragsocOp')=='eql'){
        $scads = $scads->whereHas(array('client' => function($query) use ($ragsoc) {
          $query->where('descrizion', $ragsoc)
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        }));
      }
      if($req->input('ragsocOp')=='stw'){
        $scads = $scads->whereHas(array('client' => function($query) use ($ragsoc){
          $query->where('descrizion', 'LIKE', $ragsoc.'%')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        }));
      }
      if($req->input('ragsocOp')=='cnt'){
        $scads = $scads->whereHas('client', function ($query) use ($ragsoc){
          $query->where('descrizion', 'like', '%'.$ragsoc.'%')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        });
      }
    }
    if($req->input('chkStato_T')!='T'){
      $scads = ($req->input('chkStato_P')=='P') ? $scads->where('pagato',1) : $scads->where('pagato',0);
    }

    $scads = $scads->whereHas('client', function($query){
      $query->whereNotIn('statocf', ['C', 'S', 'L'])
            ->withoutGlobalScope('agent')
            ->withoutGlobalScope('superAgent')
            ->withoutGlobalScope('client');
          });

    $scads = $scads->with(array('client' => function($query) {
      $query->select('codice', 'descrizion')
            ->withoutGlobalScope('agent')
            ->withoutGlobalScope('superAgent')
            ->withoutGlobalScope('client');
    }));
    $scads = $scads->orderBy('datascad', 'desc')->orderBy('id', 'desc')->get();

    return view('scads.index', [
      'scads' => $scads,
      'ragSoc' => $req->input('ragsoc'),
      'startDate' => $startDate,
      'endDate' => $endDate,
      'chkStato_T' => $req->input('chkStato_T'),
      'chkStato_P' => $req->input('chkStato_P'),
      'optRaggr' => $req->input('optRaggr'),
      'chkPag' => $req->input('chkPag'),
      'agentList' => $agentList
    ]);
  }

  public function showDetail (Request $req, $id){
    $scad = ScadCli::findOrFail($id);
    // dd($nextDocs);
    return view('scad.detail', [
      'scad' => $scad,
    ]);
  }
}

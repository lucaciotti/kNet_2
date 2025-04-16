<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use knet\Http\Requests;
use knet\ArcaModels\ScadCli;
use knet\ArcaModels\Agent;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\RncCause;
use knet\ArcaModels\RncIso;
use knet\ArcaModels\RncTipoRapp;
use knet\Helpers\RedisUser;

class RNCController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

  public function list (Request $req){
    $thisYear = Carbon::now()->year;
    $startDate = Carbon::createFromDate($thisYear, 1, 1);
    $endDate = Carbon::now();
    // $rncs = RncIso::where('esercizio', (string)$thisYear);
    $rncs = RncIso::whereBetween('datareg', [$startDate, $endDate]);

    $rncs = $rncs->orderBy('nummov', 'asc')->get();

    // FILTRI
    $listCause = RncCause::all();
    $listTipo = RncTipoRapp::all();
    $listSeverity = [
      ''=>'Tutti',
      1=>'1 - Basso',
      2=>'2 - Medio',
      3=>'3 - Alto',
    ];

    return view('rnc.list', [
      'rncs' => $rncs,
      'startDate' => $startDate,
      'endDate' => $endDate,
      'listCause' => $listCause,
      'listTipo' => $listTipo,
      'listSeverity' => $listSeverity,
    ]);
  }

  public function fltList (Request $req){
    // dd($req);
    $thisYear = Carbon::now()->year;

    if ($req->input('startDate') && $req->input('noDate') != 'C') {
      $startDate = Carbon::createFromFormat('d/m/Y', $req->input('startDate'));
      $endDate = Carbon::createFromFormat('d/m/Y', $req->input('endDate'));
    } else {
      $startDate = Carbon::createFromDate($thisYear, 1, 1);
      $endDate = Carbon::now();
    }
    if ($req->input('noDate') == 'C'){
      // dd((string)($thisYear - 6));
      $rncs = RncIso::where('esercizio', '>=', (string)($thisYear-6));
      $startDate = null;
      $endDate = null;
    } else {
      $rncs = RncIso::whereBetween('datareg', [$startDate, $endDate]);
    }
    
    // if ($req->input('nummov')) $rncs = $rncs->where('nummov', $req->input('nummov'));
    if ($req->input('ctiporapp')) $rncs = $rncs->whereIn('ctiporapp', $req->input('ctiporapp'));
    if ($req->input('causa')) $rncs = $rncs->whereIn('causa', $req->input('causa'));
    if ($req->input('difett') && !empty($req->input('difett'))) $rncs = $rncs->where('difett', (int)$req->input('difett'));
   
    if($req->input('ragsoc')) {
      $ragsoc = strtoupper($req->input('ragsoc'));
      if($req->input('ragsocOp')=='eql'){
        $rncs = $rncs->whereHas(array('client' => function($query) use ($ragsoc) {
          $query->where('descrizion', $ragsoc)
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        }));
      }
      if($req->input('ragsocOp')=='stw'){
        $rncs = $rncs->whereHas(array('client' => function($query) use ($ragsoc){
          $query->where('descrizion', 'LIKE', $ragsoc.'%')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        }));
      }
      if($req->input('ragsocOp')=='cnt'){
        $rncs = $rncs->whereHas('client', function ($query) use ($ragsoc){
          $query->where('descrizion', 'like', '%'.$ragsoc.'%')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
        });
      }
    }

    if ($req->input('nummov')) {
      if ($req->input('nummovOp') == 'eql') {
        $rncs = $rncs->where('nummov', $req->input('nummov'));
      }
      if ($req->input('nummovOp') == 'stw') {
        $rncs = $rncs->where('nummov', 'LIKE', $req->input('nummov') . '%');
      }
      if ($req->input('nummovOp') == 'cnt') {
        $rncs = $rncs->where('nummov', 'LIKE', '%' . $req->input('nummov') . '%');
      }
    }

    $rncs = $rncs->orderBy('nummov', 'asc')->get();

    // FILTRI
    $listCause = RncCause::has('rnc')->get();
    $listTipo = RncTipoRapp::has('rnc')->get();
    $listSeverity = [
      '' => 'Tutti',
      1 => '1 - Basso',
      2 => '2 - Medio',
      3 => '3 - Alto',
    ];

    return view('rnc.list', [
      'rncs' => $rncs,
      'startDate' => $startDate,
      'endDate' => $endDate,
      'listCause' => $listCause,
      'listTipo' => $listTipo,
      'listSeverity' => $listSeverity,
      'selectedCause' => $req->input('causa'),
      'selectedTipo' => $req->input('ctiporapp'),
      'selectedSeverity' => $req->input('difett'),
      'selectedRagSoc' => $req->input('ragsoc'),
      'selectedRncNum' => $req->input('nummov'),
    ]);
  }

  public function showDetail (Request $req, $id){
    $rnc = RncIso::findOrFail($id);
    $rncDocRif = $this->getRncDocRif($rnc);

    // dd($rncDocRif['id']);
    // dd($rnc);
    return view('rnc.detail', [
      'rnc' => $rnc,
      'rncDocRif' => $rncDocRif,
    ]);
  }

  private function getRncDocRif($rnc){
    // dd($rnc);
    $doc = DocCli::select('id', 'datadoc')
            ->where('tipodoc', $rnc->doctip)
            ->where('numerodoc', 'LIKE', '%'.$rnc->docnmov)
            ->where('codicecf', $rnc->codfor)
            ->where('esercizio', $rnc->doceser)
            ->get();
    // dd($doc);
    if(count($doc)) {
      return collect([
        'id'  =>  $doc->first()->id,
        'tipodoc'  =>  $rnc->doctip,
        'numerodoc'  =>  $rnc->docnmov,
        'esercizio'  =>  $rnc->doceser,
        'datadoc'  =>  $doc->first()->datadoc,
      ]);
    }
    return collect([
      'id'  =>  0,
      'tipodoc'  =>  $rnc->doctip,
      'numerodoc'  =>  $rnc->docnmov,
      'esercizio'  =>  $rnc->doceser,
      'datadoc'  =>  null
    ]);
  }

}

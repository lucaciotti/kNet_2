<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use knet\Http\Requests;
use knet\ArcaModels\Client;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow;
use knet\WebModels\wDdtOk;
use Torann\Registry\Facades\Registry;

class DocCliController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

  public function index (Request $req, $tipomodulo=null){
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    if ($tipomodulo){
      $docs = $docs->where('tipomodulo', $tipomodulo);
    }
    $docs = $docs->where('datadoc', '>=', Carbon::now()->subMonth());
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();
    // dd($docs);

    switch ($tipomodulo) {
      case 'O':
        $descModulo = trans('doc.orders_title');
        break;
      case 'B':
        $descModulo = trans('doc.ddt_title');
        break;
      case 'F':
        $descModulo = trans('doc.invoice_title');
        break;
      case 'P':
        $descModulo = trans('doc.quotes_title');
        break;
      case 'N':
        $descModulo = trans('doc.notecredito_title');
        break;

      default:
        $descModulo = trans('doc.documents');
        break;
    }

    return view('docs.index', [
      'docs' => $docs,
      'tipomodulo' => $tipomodulo,
      'descModulo' => $descModulo,
      'startDate' => Carbon::now()->subMonth(),
      'endDate' => Carbon::now(),
    ]);
  }

  public function fltIndex (Request $req){
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    $docs = $docs->where('tipomodulo', 'LIKE', ($req->input('optTipoDoc')=='' ? '%' : $req->input('optTipoDoc')));
    if($req->input('startDate')){
      $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
      $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
    } else {
      $startDate = Carbon::now()->subMonth();
      $endDate = Carbon::now();
    }
    $docs = $docs->whereBetween('datadoc', [$startDate, $endDate]);
    if($req->input('ragsoc')) {
      $ragsoc = strtoupper($req->input('ragsoc'));
      if($req->input('ragsocOp')=='eql'){
        $docs = $docs->whereHas('client', function ($query) use ($ragsoc){
          $query->where('descrizion', $ragsoc)
          ->withoutGlobalScope('agent')
          ->withoutGlobalScope('superAgent')
          ->withoutGlobalScope('client');
        });
      }
      if($req->input('ragsocOp')=='stw'){
        $docs = $docs->whereHas('client', function ($query) use ($ragsoc){
          $query->where('descrizion', 'like', $ragsoc.'%')
          ->withoutGlobalScope('agent')
          ->withoutGlobalScope('superAgent')
          ->withoutGlobalScope('client');
        });
      }
      if($req->input('ragsocOp')=='cnt'){
        $docs = $docs->whereHas('client', function ($query) use ($ragsoc){
          $query->where('descrizion', 'like', '%'.$ragsoc.'%')
          ->withoutGlobalScope('agent')
          ->withoutGlobalScope('superAgent')
          ->withoutGlobalScope('client');
        });
      }
    }
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();

    switch ($req->input('optTipoDoc')) {
      case 'O':
        $descModulo = trans('doc.orders_title');
        break;
      case 'B':
        $descModulo = trans('doc.ddt_title');
        break;
      case 'F':
        $descModulo = trans('doc.invoice_title');
        break;
      case 'P':
        $descModulo = trans('doc.quotes_title');
        break;
      case 'N':
        $descModulo = trans('doc.notecredito_title');
        break;

      default:
        $descModulo = trans('doc.documents');
        break;
    }

    return view('docs.index', [
      'docs' => $docs,
      'ragSoc' => $req->input('ragsoc'),
      'tipomodulo' => $req->input('optTipoDoc'),
      'descModulo' => $descModulo,
      'startDate' => $startDate,
      'endDate' => $endDate,
    ]);
  }

  public function docCli (Request $req, $codice, $tipomodulo=null){
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    if ($tipomodulo){
      $docs = $docs->where('tipomodulo', $tipomodulo)->where('codicecf', $codice);
    } else {
      $docs = $docs->where('codicecf', $codice);
    }
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();

    $client = Client::select('codice', 'descrizion')
                      ->withoutGlobalScope('agent')
                      ->withoutGlobalScope('superAgent')
                      ->withoutGlobalScope('client')
                      ->findOrFail($codice);

    switch ($tipomodulo) {
      case 'O':
        $descModulo = trans('doc.orders_title');
        break;
      case 'B':
        $descModulo = trans('doc.ddt_title');
        break;
      case 'F':
        $descModulo = trans('doc.invoice_title');
        break;
      case 'P':
        $descModulo = trans('doc.quotes_title');
        break;
      case 'N':
        $descModulo = trans('doc.notecredito_title');
        break;

      default:
        $descModulo = trans('doc.documents');
        break;
    }

    // dd($docs);
    return view('docs.indexCli', [
      'docs' => $docs,
      'tipomodulo' => $tipomodulo,
      'descModulo' => $descModulo,
      'client' => $client,
      'codicecf' => $codice,
    ]);
  }

  public function showDetail (Request $req, $id_testa){
    $tipoDoc = DocCli::select('tipomodulo')->findOrFail($id_testa);
    $head = DocCli::with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    if ($tipoDoc->tipomodulo=='F'){
        $head = $head->with(['scadenza' => function($query) {
          $query
          ->withoutGlobalScope('agent')
          ->withoutGlobalScope('superAgent')
          ->withoutGlobalScope('client');
        }]);
    } elseif ($tipoDoc->tipomodulo=='B') {
        $head = $head->with('vettore', 'detBeni');
    }
    $head = $head->findOrFail($id_testa);
    if ($tipoDoc->tipomodulo == 'B'){
      $destDiv = Destinaz::where('codicecf', $head->codicecf)->where('codicedes', $head->destdiv)->first();
      $ddtOk = wDdtOk::where('id_testa', $head->id)->first();
    } else {
      $destDiv = null;
      $ddtOk = null;
    }
    $rows = DocRow::where('id_testa', $id_testa)->orderBy('numeroriga', 'asc')->get();
    $prevIds = DocRow::distinct('riffromt')->where('id_testa', $id_testa)->where('riffromt', '!=', 0)->get();
    $prevDocs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc')->whereIn('id', $prevIds->pluck('riffromt'))->get();
    $nextIds = DocRow::distinct('id_testa')->where('riffromt', $id_testa)->get();
    $nextDocs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc')->whereIn('id', $nextIds->pluck('id_testa'))->get();
    // dd($head);
    return view('docs.detail', [
      'head' => $head,
      'rows' => $rows,
      'prevDocs' => $prevDocs,
      'nextDocs' => $nextDocs,
      'destinaz' => $destDiv,
      'ddtOk' => $ddtOk,
    ]);
  }

  public function showOrderToDeliver(Request $req){
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    $docs = $docs->where('tipomodulo', 'O');
    $docs = $docs->where('numrighepr', '>', 0);
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();
    // dd($docs);

    $tipomodulo = 'O';
    $descModulo = ($tipomodulo == 'O' ? 'Ordini' : ($tipomodulo == 'B' ? 'Bolle' : ($tipomodulo == 'F' ? 'Fatture' : $tipomodulo)));

    return view('docs.index', [
      'docs' => $docs,
      'tipomodulo' => $tipomodulo,
      'descModulo' => $descModulo,
    ]);
  }

  public function showDdtToReceive(Request $req){
    $lastMonth = new Carbon('first day of last month');
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc')
                    ->where('tipomodulo', 'B')
                    ->where('datadoc', '>=', $lastMonth)
                    ->doesntHave('wDdtOk');
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();
    // dd($docs);

    $tipomodulo = 'B';
    $descModulo = ($tipomodulo == 'O' ? 'Ordini' : ($tipomodulo == 'B' ? 'Bolle' : ($tipomodulo == 'F' ? 'Fatture' : $tipomodulo)));

    return view('docs.index', [
      'docs' => $docs,
      'tipomodulo' => $tipomodulo,
      'descModulo' => $descModulo,
    ]);
  }

}

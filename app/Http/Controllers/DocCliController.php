<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use knet\Http\Requests;
use knet\ArcaModels\Client;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow;
use knet\WebModels\wDdtOk;
use knet\Helpers\RedisUser;

use knet\ExportsXLS\DocExport;

use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\File;
use knet\Helpers\PdfReport;
use PDF;

class DocCliController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

  public function index (Request $req, $tipomodulo=null){
    if(RedisUser::get('role')=='client'){
      return redirect()->action('DocCliController@docCli', ['codice'=> RedisUser::get('codcli'), 'tipomodulo'=>$tipomodulo]);
    }
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    if ($tipomodulo){
      $docs = $docs->where(function ($query) use ($tipomodulo) {
        $query->where('tipomodulo', $tipomodulo);
        if ($tipomodulo == 'B') {
          $query = $query->orWhereIn('tipodoc', ['BV', 'BI', 'BS']);
        }
      });     
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
    $docs = $docs->where(function ($query) use ($req){
        $query->where('tipomodulo', 'LIKE', ($req->input('optTipoDoc') == '' ? '%' : $req->input('optTipoDoc')));
        if ($req->input('optTipoDoc') == 'B') {
          $query = $query->orWhereIn('tipodoc', ['BV', 'BI', 'BS']);
        }
    });
    // $docs = $docs->where('tipomodulo', 'LIKE', ($req->input('optTipoDoc')=='' ? '%' : $req->input('optTipoDoc')));
    // if ($req->input('optTipoDoc') == 'B') {
    //   $docs = $docs->orWhereIn('tipodoc', ['BV', 'BK', 'BI', 'BS']);
    // }
    if($req->input('startDate')){
      $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
      $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
    } else {
      $startDate = Carbon::now()->subMonth();
      $endDate = Carbon::now();
    }
    if(!$req->input('noDate')){
      $docs = $docs->whereBetween('datadoc', [$startDate, $endDate]);
    }
    if($req->input('fltAgents')){
      $docs->whereIn('agente', $req->input('fltAgents'));
    }
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
      'startDate' => !$req->input('noDate') ? $startDate : "",
      'endDate' => !$req->input('noDate') ? $endDate : "",
    ]);
  }

  public function docCli (Request $req, $codice, $tipomodulo=null){
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    if ($tipomodulo) {
      $docs = $docs->where(function ($query) use ($tipomodulo) {
        $query->where('tipomodulo', $tipomodulo);
        if ($tipomodulo == 'B') {
          $query = $query->orWhereIn('tipodoc', ['BV', 'BI', 'BS']);
        }
      });
    }
    $docs = $docs->where('codicecf', $codice);
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
      'startDate' => "",
      'endDate' => "",
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
      'startDate' => "",
      'endDate' => "",
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
      'startDate' => $lastMonth,
      'endDate' => Carbon::now(),
    ]);
  }

  public function showOrderDispachMonth(Request $req){
    $thisYear = ($req->input('year')) ? $req->input('year') : Carbon::now()->year;
		$prevYear = $thisYear-1;	
		$dStartMonth = new Carbon('first day of '.Carbon::createFromDate(null, $req->input('mese'), null)->format('F').' '.((string)$thisYear)); 
		$dEndMonth = new Carbon('last day of '.Carbon::createFromDate(null, $req->input('mese'), null)->format('F').' '.((string)$thisYear));

    $idOrders = $this->getIDOrderToShip($req->input('fltAgents'), $thisYear, $prevYear, $dStartMonth, $dEndMonth);

    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc');
    $docs = $docs->where('tipomodulo', 'O');
    $docs = $docs->whereIn('id', $idOrders);
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
      'startDate' => $dStartMonth,
      'endDate' => $dEndMonth,
    ]);
  }

  public function showDdtToInvoice(Request $req){
    $lastMonth = new Carbon('first day of last month');
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc')
                    ->where('tipomodulo', 'B')
                    ->where('datadoc', '>=', $lastMonth)
                    ->where('numrighepr', '>', 0);
    if($req->input('fltAgents')){
      $docs->whereIn('agente', $req->input('fltAgents'));
    }
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
      'startDate' => $lastMonth,
      'endDate' => Carbon::now(),
    ]);
  }

  public function showInvoiceMonth(Request $req){
    $thisYear = ($req->input('year')) ? $req->input('year') : Carbon::now()->year;
		$prevYear = $thisYear-1;	
		$dStartMonth = new Carbon('first day of '.Carbon::createFromDate(null, $req->input('mese'), null)->format('F').' '.((string)$thisYear)); 
		$dEndMonth = new Carbon('last day of '.Carbon::createFromDate(null, $req->input('mese'), null)->format('F').' '.((string)$thisYear));
    $docs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc', 'codicecf', 'numerodocf', 'numrighepr', 'totdoc')
                    ->whereIn('tipomodulo', ['F', 'N'])->where('tipodoc', '!=', 'FP')
                    ->whereBetween('datadoc', [$dStartMonth, $dEndMonth]);
    if($req->input('fltAgents')){
      $docs->whereIn('agente', $req->input('fltAgents'));
    }
    $docs = $docs->with(['client' => function($query) {
      $query
      ->withoutGlobalScope('agent')
      ->withoutGlobalScope('superAgent')
      ->withoutGlobalScope('client');
    }]);
    $docs = $docs->orderBy('datadoc', 'desc')->orderBy('id', 'desc')->get();
    // dd($docs);

    $tipomodulo = 'F';
    $descModulo = ($tipomodulo == 'O' ? 'Ordini' : ($tipomodulo == 'B' ? 'Bolle' : ($tipomodulo == 'F' ? 'Fatture' : $tipomodulo)));

    return view('docs.index', [
      'docs' => $docs,
      'tipomodulo' => $tipomodulo,
      'descModulo' => $descModulo,
      'startDate' => $dStartMonth,
      'endDate' => $dEndMonth,
    ]);
  }

  /////////////////////
  // CERCO GLI ID DELLE TESTE DEGLI ORDINI DA SPEDIRE!!!
  /////////////////////
  public function getIDOrderToShip($agents=[], $thisYear, $prevYear, $dStartMonth, $dEndMonth, $filiali=false){

		// Mi costruisco l'array delle teste dei documenti da cercare se Non esiste		
    $docTes = DocCli::select('id')							
              ->whereIn('esercizio', [(string)$thisYear, (string)$prevYear])
              ->where('tipodoc', 'OC');
    if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
      $docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
    }
    if(!empty($agents)){
      $docTes->whereIn('agente', $agents);
    }
    $docTes = $docTes->get();
    $arrayIDOC = $docTes->toArray();
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa')
							->where('quantitare', '>', 0)
							->where('ommerce', 0)
              ->where('codicearti', '!=', '');
		// 26/11 Richiesta di Mauro per fare Ordini solo del mese!
    $docRow->whereBetween('dataconseg', [$dStartMonth, $dEndMonth]);
		// $docRow->where('dataconseg', '<=', $dEndMonth);
		$docRow = $docRow->whereIn('id_testa', $arrayIDOC)->get();
		
		return $docRow->toArray();
  }
  ///////////////////////////////////////
  

  public function downloadXML(Request $req, $id_testa){
    $tipoDoc = DocCli::select('tipomodulo')->findOrFail($id_testa);
    $head = DocCli::select(DB::raw('concat(tipodoc, " ", numerodoc) as doc'), 'datadoc', 'esercizio',
                            'codicecf', 'numrighepr', 'valuta', 'sconti', 'scontocass',
                            'cambio', 'numerodocf', 'datadocfor', 'tipomodulo',
                            'pesolordo', 'pesonetto', 'volume', 'v1data', 'v1ora', 'vettore1', 'destdiv', 'aspbeni',
                            'colli', DB::raw('speseim+spesetr as spesetras'), 'totmerce',
                            'totsconto', 'totimp', 'totiva', 'totdoc');
    if ($tipoDoc->tipomodulo=='B') {
        $head = $head->with('vettore', 'detBeni');
    }
    $head = $head->findOrFail($id_testa);
    if ($tipoDoc->tipomodulo == 'B'){
      $destDiv = Destinaz::where('codicecf', $head->codicecf)->where('codicedes', $head->destdiv)->first();
    } else {
      $destDiv = null;
    }

    $rows = DocRow::select('numeroriga', 'codicearti', 'descrizion', 'unmisura', 'fatt',
                            'quantita', 'quantitare', 'sconti', 'prezzoun', 'prezzotot', 'aliiva',
                            'ommerce', 'lotto', 'matricola', 'dataconseg', 'u_dtpronto');
    $rows = $rows->where('id_testa', $id_testa)->orderBy('numeroriga', 'asc')->get();

    if ($destDiv) {
      $head = array_merge($head->toArray(), ['destinazione' => $destDiv->toArray()]);
    } else {
      $head = $head->toArray();
    }

    $array = [
        'Head' => $head,
        'Rows' => [
          'Row' => $rows->toArray(),
        ]
    ];
    $result = ArrayToXml::convert($array, "DocRoot");
  
    $file = time() . '_file.xml';
    $destinationPath=sys_get_temp_dir()."/";
    if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
    File::put($destinationPath.$file,$result);
    return response()->download($destinationPath.$file);
  }

  public function downloadExcel(Request $req, $id_testa){
    return (new DocExport($id_testa))->download(time() . '_file.xlsx');
    /* $pdf = PDF::loadView('_exports.pdf.masterPage.masterPdf', []);
    return $pdf->stream('test.pdf'); */
  }

  public function downloadPDF(Request $req, $id_testa)
  {
    $tipoDoc = DocCli::select('tipomodulo')->findOrFail($id_testa);
    $head = DocCli::with(['client' => function ($query) {
      $query
        ->withoutGlobalScope('agent')
        ->withoutGlobalScope('superAgent')
        ->withoutGlobalScope('client');
    }, 'agent']);
    if ($tipoDoc->tipomodulo == 'F') {
      $head = $head->with(['scadenza' => function ($query) {
        $query
          ->withoutGlobalScope('agent')
          ->withoutGlobalScope('superAgent')
          ->withoutGlobalScope('client');
      }]);
    } elseif ($tipoDoc->tipomodulo == 'B') {
      $head = $head->with('vettore', 'detBeni');
    }
    $head = $head->findOrFail($id_testa);
    if ($tipoDoc->tipomodulo == 'B') {
      $destDiv = Destinaz::where('codicecf', $head->codicecf)->where('codicedes', $head->destdiv)->first();
      $ddtOk = wDdtOk::where('id_testa', $head->id)->first();
    } else {
      $destDiv = null;
      $ddtOk = null;
    }
    $rows = DocRow::where('id_testa', $id_testa)->orderBy('numeroriga', 'asc');
    if (RedisUser::get('location') != RedisUser::get('lang')) {
      // dd(RedisUser::getAll());
      $rows->with('descrLangEN');
    }
    $rows=$rows->get();
    $prevIds = DocRow::distinct('riffromt')->where('id_testa', $id_testa)->where('riffromt', '!=', 0)->get();
    $prevDocs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc')->whereIn('id', $prevIds->pluck('riffromt'))->get();
    $nextIds = DocRow::distinct('id_testa')->where('riffromt', $id_testa)->get();
    $nextDocs = DocCli::select('id', 'tipodoc', 'numerodoc', 'datadoc')->whereIn('id', $nextIds->pluck('id_testa'))->get();

    $totValueFOC = $rows->where('ommerce', true)->sum('prezzotot');
    // dd($rows);
    $title = "Doc Detail";
    $subTitle = $head->tipodoc."_".$head->numerodoc."/".$head->esercizio;
    $view = '_exports.pdf.docDetailPdf';
    $data = [
      'head' => $head,
      'rows' => $rows,
      'prevDocs' => $prevDocs,
      'nextDocs' => $nextDocs,
      'destinaz' => $destDiv,
      'ddtOk' => $ddtOk,
      'totValueFOC' => $totValueFOC,
    ];
    $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);

    return $pdf->stream($title . '-' . $subTitle . '.pdf');

  }
}

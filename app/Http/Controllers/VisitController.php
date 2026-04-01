<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

use knet\Http\Requests;

use knet\ArcaModels\Client;
use knet\WebModels\wVisit;
use knet\WebModels\wRubrica;
use knet\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use knet\ArcaModels\Supplier;
use Session;

use knet\ExportsXLS\VisitImport;

use knet\Helpers\PdfReport;

class VisitController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function index(Request $req, $codCli=null){
      // Redirect to Form Page
      if (empty($codCli)) {
        $client = Client::select('codice', 'descrizion')->get();
      } else {
        $client = Client::select('codice', 'descrizion')->findOrFail($codCli);
      }
      return view('visit.insert', [
        'client' => $client,
        'contact' => '',
      ]);
    }

    public function edit(Request $req, $id){
      // Redirect to Form Page
      $visit = wVisit::with('user')->findOrFail($id);
      if (!empty($visit->rubri_id)) return $this->editRubri($req, $id);
      $client = Client::select('codice', 'descrizion')->findOrFail($visit->codicecf);
      return view('visit.insert', [
        'visit' => $visit,
        'client' => $client,
        'contact' => '',
      ]);
    }

    public function delete(Request $req, $id){
      // Redirect to Form Page
      $visit = wVisit::findOrFail($id)->delete();
      return $this->report($req);
    }

    public function indexRubri(Request $req, $rubri_id=null){
      // Redirect to Form Page
      if (empty($rubri_id)) {
        $contact = wRubrica::select('id', 'descrizion')->get();
      } else {
        $contact = wRubrica::select('id', 'descrizion')->findOrFail($rubri_id);
      }
      return view('visit.insertRubri', [
        'client' => '',
        'contact' => $contact,
      ]);
    }

    public function editRubri(Request $req, $id){
      // Redirect to Form Page
      $visit = wVisit::with('user')->findOrFail($id);
      $contact = wRubrica::select('id', 'descrizion')->findOrFail($visit->rubri_id);
      return view('visit.insertRubri', [
        'visit' => $visit,
        'client' => '',
        'contact' => $contact,
      ]);
    }

    public function store(Request $req){
      // dd($req);
      if(!empty($req->input('id'))){
        $visit = wVisit::where('id', $req->input('id'))->update([
          'codicecf' => ($req->input('codcli') ? $req->input('codcli') : null),
          'rubri_id' => ($req->input('rubri_id') ? $req->input('rubri_id') : null),
          'user_id' => Auth::user()->id,
          'data' => new Carbon($req->input('data')),
          'tipo' => $req->input('tipo'),
          'descrizione' => $req->input('descrizione'),
          'note' => $req->input('note'),
          'conclusione' => $req->input('conclusione'),
          'persona_contatto' => $req->input('persona'),
          'funzione_contatto' => $req->input('rolePersona'),
          'ordine' => $req->input('optOrdine'),
          'lastOfDay' => $req->input('optLastDay') ?? false,
          'lastOfDayType' => $req->input('optLastType') ?? false,
          'data_prox' => $req->input('dateNext')!=null ? (new Carbon($req->input('dateNext'))) : null
        ]);
      } else {
        $visit = wVisit::create([
          'codicecf' => ($req->input('codcli') ? $req->input('codcli') : null),
          'rubri_id' => ($req->input('rubri_id') ? $req->input('rubri_id') : null),
          'user_id' => Auth::user()->id,
          'data' => new Carbon($req->input('data')),
          'tipo' => $req->input('tipo'),
          'descrizione' => $req->input('descrizione'),
          'note' => $req->input('note'),
          'conclusione' => $req->input('conclusione'),
          'persona_contatto' => $req->input('persona'),
          'funzione_contatto' => $req->input('rolePersona'),
          'ordine' => $req->input('optOrdine'),
          'lastOfDay' => $req->input('optLastDay') ?? false,
          'lastOfDayType' => $req->input('optLastType') ?? false,
          'data_prox' => $req->input('dateNext')!=null ? (new Carbon($req->input('dateNext'))) : null
        ]);
      }

      if($req->input('rubri_id')){
        $contact = wRubrica::find($req->input('rubri_id'));
        $contact->date_lastvisit = new Carbon($req->input('data'));
        // $contact->date_nextvisit = (new Carbon($req->input('data')))->addDays(60);
        if(!empty($req->input('dateNext'))){
          $contact->date_nextvisit = (new Carbon($req->input('dateNext')));
        }
        $contact->save();
        return Redirect::route('visit::showRubri', $req->input('rubri_id'));
      }
      if (Str::startsWith($req->input('codcli'), 'F')) {
        return Redirect::route('visit::showSupplier', $req->input('codcli'));
      } else {
        return Redirect::route('visit::show', $req->input('codcli'));
      }
    }

    public function show(Request $req, $codCli=null ){
      // dd($req);
      $visits = wVisit::where('codicecf', $codCli)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
      $client = Client::findOrFail($codCli);
      

      return view('visit.show', [
        'visits' => $visits,
        'client' => $client,
        'dateNow' => Carbon::now(),
        ]);
    }

    public function indexSupplier(Request $req, $codCli = null)
    {
      // Redirect to Form Page
      if (empty($codCli)) {
        $client = Supplier::select('codice', 'descrizion')->get();
      } else {
        $client = Supplier::select('codice', 'descrizion')->findOrFail($codCli);
      }
      return view('visit.insertSupplier', [
        'client' => $client,
        'contact' => '',
      ]);
    }

    public function editSupplier(Request $req, $id)
    {
      // Redirect to Form Page
      $visit = wVisit::with('user')->findOrFail($id);
      if (!empty($visit->rubri_id)) return $this->editRubri($req, $id);
      $client = Supplier::select('codice', 'descrizion')->findOrFail($visit->codicecf);
      return view('visit.insertSupplier', [
        'visit' => $visit,
        'client' => $client,
        'contact' => '',
      ]);
    }

    public function deleteSupplier(Request $req, $id)
    {
      // Redirect to Form Page
      $visit = wVisit::findOrFail($id)->delete();
      return $this->report($req);
    }
    
    public function showSupplier(Request $req, $codice = null)
    {
      // dd($req);
      $visits = wVisit::where('codicecf', $codice)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
      $client = Supplier::findOrFail($codice);
      // dd($visits);

      return view('visit.showSupplier', [
        'visits' => $visits,
        'client' => $client,
        'dateNow' => Carbon::now(),
      ]);
    }

    public function showRubri(Request $req, $rubri_id=null ){
      
      $visits = wVisit::where('rubri_id', $rubri_id)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
      $client = wRubrica::findOrFail($rubri_id);

      return view('visit.showRubri', [
        'visits' => $visits,
        'client' => $client,
        'dateNow' => Carbon::now(),
        ]);
    }

    public function report(Request $req) {
      $visits = wVisit::select('*');

      if($req->input('startDate') and !$req->input('noDate')){
        $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
        $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
      } else {
        // $startDate = Carbon::now()->subYear();
        $thisYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();
      }
      if(!$req->input('noDate')){
        $visits = $visits->whereBetween('data', [$startDate, $endDate]);
      }

      if($req->input('ragsoc')) {
        $ragsoc = strtoupper($req->input('ragsoc'));
        if($req->input('ragsocOp')=='eql'){
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc){
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('rubri', function ($query) use ($ragsoc){
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          });
        }
        if($req->input('ragsocOp')=='stw'){
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', $ragsoc.'%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', $ragsoc.'%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', $ragsoc.'%');
          });
        }
        if($req->input('ragsocOp')=='cnt'){
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', '%'.$ragsoc.'%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', '%'.$ragsoc.'%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc){
            $query->where('descrizion', 'like', '%'.$ragsoc.'%');
          });
        }
      }

      // VECCHIA GESTIONE TIPOLOGIA
      // if($req->input('optTipo')){
      //   $visits->where('tipo', $req->input('optTipo'));
      // }

      // dd(array_filter(array_map(function($key) use ($req) {
      //   if($req->filled($key)){
      //     if(Str::startsWith($key, 'type')){
      //       return $key;
      //     }
      //   }
      // }, array_keys($req->all()))));
      $allType=false;      
      $typeInputs = array_filter(
                        array_map(
                          function ($key) use ($req) {
                            if($req->filled($key)){
                              if(Str::startsWith($key, 'type')){
                                return $key;
                              }
                            }
                          }, 
                          array_keys(
                            array_filter(
                              $req->all(), 
                              function ($v, $k) {
                                return $v !== false && !is_null($v) && ($v != '' || $v == '0') && $v!=0;
                              },
                              ARRAY_FILTER_USE_BOTH
                            )
                          )
                        )
                      );
      if (count($typeInputs)>0){
        $visits->where(
          function ($query)  use ($typeInputs) {
            foreach ($typeInputs as $type) {
              $query->orWhere('tipo', str_replace('type', '', $type));
            }
            return $query;
          }
        );
      } else {
        $allType=true;
      }
      
      if($req->input('relat')) {
        $relat = strtoupper($req->input('relat'));
        if($req->input('relatOp')=='eql'){
          $user_ids = User::where('name', $relat)->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='stw'){
          $user_ids = User::where('name', 'like', $relat.'%')->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='cnt'){
          $user_ids = User::where('name', 'like', '%'.$relat.'%')->pluck('id')->toArray();
        }
        $visits->whereIn('user_id', $user_ids);
      }

      $visits = $visits->with(['client', 'rubri', 'user']);

      $visits = $visits->orderBy('data', 'desc')->orderBy('id', 'desc')->get();

      $dataForReport = [
        'ragSoc' => $req->input('ragsoc') ?? '',
        'ragsocOp' => $req->input('ragsocOp'),
        'startDate' => !$req->input('noDate') ? $req->input('startDate') : "",
        'endDate' => !$req->input('noDate') ? $req->input('endDate') : "",
        // 'optTipo' => $req->input('optTipo'),
        'typeMeet' => $req->input('typeMeet')==1 or $allType,
        'typeMail' => $req->input('typeMail')==1 or $allType,
        'typeProd' => $req->input('typeProd')== 1 or $allType,
        'typeScad' => $req->input('typeScad')==1 or $allType,
        'typeRNC' => $req->input('typeRNC')==1 or $allType,
        'relat' => $req->input('relat'),
        'relatOp' => $req->input('relatOp'),
        'noDate' => $req->input('noDate'),
        'groupBy' => 'user_id'
      ];
      // dd($dataForReport);
      return view('visit.report', [
        'visits' => $visits,
        'ragSoc' => $req->input('ragsoc') ?? '',
        'ragsocOp' => $req->input('ragsocOp'),
        'startDate' => !$req->input('noDate') ? $startDate : "",
        'endDate' => !$req->input('noDate') ? $endDate : "",
        // 'optTipo' => $req->input('optTipo'),
        'typeMeet' => $req->input('typeMeet')==1 or $allType,
        'typeMail' => $req->input('typeMail')==1 or $allType,
        'typeProd' => $req->input('typeProd')==1 or $allType,
        'typeScad' => $req->input('typeScad')==1 or $allType,
        'typeRNC' => $req->input('typeRNC')==1 or $allType,
        'relat' => $req->input('relat'),
        'relatOp' => $req->input('relatOp'),
        'dataForReport' => $dataForReport
      ]);
    }

    public function reportPDF(Request $req){
      //Let's Set the Date
      $visits = wVisit::select('*');
      // dd($req);

      if($req->input('startDate') and !$req->input('noDate')){
        $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
        $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
      } else {
        // $startDate = Carbon::now()->subYear();
        $thisYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();
      }
      if(!$req->input('noDate')){
        $visits = $visits->whereBetween('data', [$startDate, $endDate]);
      }

      if ($req->input('ragsoc')) {
        $ragsoc = strtoupper($req->input('ragsoc'));
        if ($req->input('ragsocOp') == 'eql') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          });
        }
        if ($req->input('ragsocOp') == 'stw') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          });
        }
        if ($req->input('ragsocOp') == 'cnt') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          });
        }
      }

      $allType=false;
      $typeInputs = array_filter(
                        array_map(
                          function ($key) use ($req) {
                            if($req->filled($key)){
                              if(Str::startsWith($key, 'type')){
                                return $key;
                              }
                            }
                          }, 
                          array_keys(
                            array_filter(
                              $req->all(), 
                              function ($v, $k) {
                                return $v !== false && !is_null($v) && ($v != '' || $v == '0') && $v!=0;
                              },
                              ARRAY_FILTER_USE_BOTH
                            )
                          )
                        )
                      );
      if (count($typeInputs)>0){
        $visits->where(
          function ($query)  use ($typeInputs) {
            foreach ($typeInputs as $type) {
              $query->orWhere('tipo', str_replace('type', '', $type));
            }
            return $query;
          }
        );
      } else {
        $allType=true;
      }

      if($req->input('relat')) {
        $relat = strtoupper($req->input('relat'));
        if($req->input('relatOp')=='eql'){
          $user_ids = User::where('name', $relat)->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='stw'){
          $user_ids = User::where('name', 'like', $relat.'%')->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='cnt'){
          $user_ids = User::where('name', 'like', '%'.$relat.'%')->pluck('id')->toArray();
        }
        $visits->whereIn('user_id', $user_ids);
      }

      $visits = $visits->with(['client', 'rubri', 'user']);

      $visits = $visits->orderBy('data', 'desc')->orderBy('id', 'desc')->get();
      
      if($req->input('groupBy')){
        $visits = $visits->groupBy($req->input('groupBy'));
      }

      $title = "Scheda Visite ";
      $subTitle = $startDate->format('Y-m-d').' - '. $endDate->format('Y-m-d');
      $view = '_exports.pdf.schedaVisitPdf';
      $data = [
          'visits' => $visits,
      ];
      $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);

      return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

    public function reportDistancePDF(Request $req){
      //Let's Set the Date
      $visits = wVisit::select('*')
        ->addSelect(DB::raw('CONCAT(YEAR(data),"/",LPAD(DATE_FORMAT(data, "%v"),2,"0")) as periodo'))
        // ->addSelect(DB::raw('LPAD(DATE_FORMAT(data, "%v"),2,"0") as week'))
        ->addSelect(DB::raw('LPAD(DATE_FORMAT(data, "%d"),3,"0") as giorno'))
        ->addSelect(DB::raw('IF(ISNULL(codicecf), "PC", IF(LEFT(codicecf, 1)="C", "C", "F")) as tipologia'));
      // dd($req);

      if($req->input('startDate') and !$req->input('noDate')){
        $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
        $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
      } else {
        // $startDate = Carbon::now()->subYear();
        $thisYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();
      }
      if(!$req->input('noDate')){
        $visits = $visits->whereBetween('data', [$startDate, $endDate]);
      }

      if ($req->input('ragsoc')) {
        $ragsoc = strtoupper($req->input('ragsoc'));
        if ($req->input('ragsocOp') == 'eql') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          });
        }
        if ($req->input('ragsocOp') == 'stw') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          });
        }
        if ($req->input('ragsocOp') == 'cnt') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          });
        }
      }

      $allType=false;
      $typeInputs = array_filter(
                        array_map(
                          function ($key) use ($req) {
                            if($req->filled($key)){
                              if(Str::startsWith($key, 'type')){
                                return $key;
                              }
                            }
                          }, 
                          array_keys(
                            array_filter(
                              $req->all(), 
                              function ($v, $k) {
                                return $v !== false && !is_null($v) && ($v != '' || $v == '0') && $v!=0;
                              },
                              ARRAY_FILTER_USE_BOTH
                            )
                          )
                        )
                      );
      if (count($typeInputs)>0){
        $visits->where(
          function ($query)  use ($typeInputs) {
            foreach ($typeInputs as $type) {
              $query->orWhere('tipo', str_replace('type', '', $type));
            }
            return $query;
          }
        );
        $visits->where('tipo', '!=', 'Mail');
      } else {
        $allType=true;
      }

      if($req->input('relat')) {
        $relat = strtoupper($req->input('relat'));
        if($req->input('relatOp')=='eql'){
          $user_ids = User::where('name', $relat)->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='stw'){
          $user_ids = User::where('name', 'like', $relat.'%')->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='cnt'){
          $user_ids = User::where('name', 'like', '%'.$relat.'%')->pluck('id')->toArray();
        }
        $visits->whereIn('user_id', $user_ids);
      }

      $visits = $visits->with(['client', 'rubri', 'supplier', 'user']);

      $visits = $visits->orderBy('user_id', 'desc')->orderBy('data', 'desc')->orderBy('id', 'desc')->get();

      // dd($visits->first());
      $mappedVisit = $this->mapVisitWithDistance($visits);
      // dd($mappedVisit);

      $title = "Scheda Visite ";
      $subTitle = $startDate->format('Y-m-d').' - '. $endDate->format('Y-m-d');
      $view = '_exports.pdf.schedaVisitDistancePdf';
      $data = [
          'visits' => $mappedVisit,
      ];
      $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

      return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

    public function reportCompletoPDF(Request $req)
    {
      //Let's Set the Date
      $visits = wVisit::select('*')
        ->addSelect(DB::raw('CONCAT(YEAR(data),"/",LPAD(DATE_FORMAT(data, "%v"),2,"0")) as periodo'))
        // ->addSelect(DB::raw('LPAD(DATE_FORMAT(data, "%v"),2,"0") as week'))
        ->addSelect(DB::raw('LPAD(DATE_FORMAT(data, "%d"),3,"0") as giorno'))
        ->addSelect(DB::raw('IF(ISNULL(codicecf), "PC", IF(LEFT(codicecf, 1)="C", "C", "F")) as tipologia'));
      // dd($req);

      if ($req->input('startDate') and !$req->input('noDate')) {
        $startDate = Carbon::createFromFormat('d/m/Y', $req->input('startDate'));
        $endDate = Carbon::createFromFormat('d/m/Y', $req->input('endDate'));
      } else {
        // $startDate = Carbon::now()->subYear();
        $thisYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();
      }
      if (!$req->input('noDate')) {
        $visits = $visits->whereBetween('data', [$startDate, $endDate]);
      }

      if ($req->input('ragsoc')) {
        $ragsoc = strtoupper($req->input('ragsoc'));
        if ($req->input('ragsocOp') == 'eql') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          });
        }
        if ($req->input('ragsocOp') == 'stw') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          });
        }
        if ($req->input('ragsocOp') == 'cnt') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          });
        }
      }

      $allType = false;
      $typeInputs = array_filter(
        array_map(
          function ($key) use ($req) {
            if ($req->filled($key)) {
              if (Str::startsWith($key, 'type')) {
                return $key;
              }
            }
          },
          array_keys(
            array_filter(
              $req->all(),
              function ($v, $k) {
                return $v !== false && !is_null($v) && ($v != '' || $v == '0') && $v != 0;
              },
              ARRAY_FILTER_USE_BOTH
            )
          )
        )
      );
      if (count($typeInputs) > 0) {
        $visits->where(
          function ($query)  use ($typeInputs) {
            foreach ($typeInputs as $type) {
              $query->orWhere('tipo', str_replace('type', '', $type));
            }
            return $query;
          }
        );
        $visits->where('tipo', '!=', 'Mail');
      } else {
        $allType = true;
      }

      if ($req->input('relat')) {
        $relat = strtoupper($req->input('relat'));
        if ($req->input('relatOp') == 'eql') {
          $user_ids = User::where('name', $relat)->pluck('id')->toArray();
        }
        if ($req->input('relatOp') == 'stw') {
          $user_ids = User::where('name', 'like', $relat . '%')->pluck('id')->toArray();
        }
        if ($req->input('relatOp') == 'cnt') {
          $user_ids = User::where('name', 'like', '%' . $relat . '%')->pluck('id')->toArray();
        }
        $visits->whereIn('user_id', $user_ids);
      }

      $visits = $visits->with(['client', 'rubri', 'supplier', 'user']);

      $visits = $visits->orderBy('user_id', 'desc')->orderBy('data', 'desc')->orderBy('id', 'desc')->get();

      // dd($visits->first());
      $mappedVisit = $this->mapVisitWithDistance($visits);
      // dd($mappedVisit);

      $title = "Scheda Visite ";
      $subTitle = $startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d');
      $view = '_exports.pdf.schedaVisitCompletoPdf';
      $data = [
        'visits' => $mappedVisit,
      ];
      $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);

      return $pdf->stream($title . '-' . $subTitle . '.pdf');
    }


    public function countPDF(Request $req){
      //Let's Set the Date
      $visits = wVisit::select('user_id', 'codicecf', 'rubri_id')
                      ->selectRaw('MAX(IF(ISNULL(codicecf), "Potenziali Clienti", IF(LEFT(codicecf, 1)="C", "Clienti", "Fornitori"))) as tipologia')
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_01', [1])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_02', [2])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_03', [3])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_04', [4])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_05', [5])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_06', [6])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_07', [7])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_08', [8])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_09', [9])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_10', [10])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_11', [11])
                      ->selectRaw('SUM(IF(MONTH(data) = ?, 1, 0)) as month_12', [12])
                      ->selectRaw('SUM(1) as tot');
      // dd($req);

      if($req->input('startDate') and !$req->input('noDate')){
        $startDate = Carbon::createFromFormat('d/m/Y',$req->input('startDate'));
        $endDate = Carbon::createFromFormat('d/m/Y',$req->input('endDate'));
      } else {
        // $startDate = Carbon::now()->subYear();
        $thisYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();
      }
      if(!$req->input('noDate')){
        $visits = $visits->whereBetween('data', [$startDate, $endDate]);
      }

      if ($req->input('ragsoc')) {
        $ragsoc = strtoupper($req->input('ragsoc'));
        if ($req->input('ragsocOp') == 'eql') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', $ragsoc);
          });
        }
        if ($req->input('ragsocOp') == 'stw') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', $ragsoc . '%');
          });
        }
        if ($req->input('ragsocOp') == 'cnt') {
          $visits = $visits->whereHas('client', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('rubri', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          })->orWhereHas('supplier', function ($query) use ($ragsoc) {
            $query->where('descrizion', 'like', '%' . $ragsoc . '%');
          });
        }
      }

      $allType=false;
      
      $typeInputs = array_filter(
                        array_map(
                          function ($key) use ($req) {
                            if($req->filled($key)){
                              if(Str::startsWith($key, 'type')){
                                return $key;
                              }
                            }
                          }, 
                          array_keys(
                            array_filter(
                              $req->all(), 
                              function ($v, $k) {
                                return $v !== false && !is_null($v) && ($v != '' || $v == '0') && $v!=0;
                              },
                              ARRAY_FILTER_USE_BOTH
                            )
                          )
                        )
                      );
      if (count($typeInputs)>0){
        $visits->where(
          function ($query)  use ($typeInputs) {
            foreach ($typeInputs as $type) {
              $query->orWhere('tipo', str_replace('type', '', $type));
            }
            return $query;
          }
        );
      } else {
        $allType=true;
      }

      if($req->input('relat')) {
        $relat = strtoupper($req->input('relat'));
        if($req->input('relatOp')=='eql'){
          $user_ids = User::where('name', $relat)->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='stw'){
          $user_ids = User::where('name', 'like', $relat.'%')->pluck('id')->toArray();
        }
        if($req->input('relatOp')=='cnt'){
          $user_ids = User::where('name', 'like', '%'.$relat.'%')->pluck('id')->toArray();
        }
        $visits->whereIn('user_id', $user_ids);
      }

      $visits = $visits->with(['client', 'client.detSect', 'client.detZona', 'supplier', 'supplier.detSect', 'supplier.detZona', 'rubri', 'rubri.detZona', 'user']);

      $visits = $visits->groupBy(['user_id', 'codicecf', 'rubri_id']);

      $visits = $visits->orderBy('tot', 'desc')->orderBy('codicecf', 'asc')->orderBy('rubri_id', 'asc');

      $visits = $visits->get();
      
      $visits = $visits->groupBy(['user_id', 'tipologia']);

      // dd($visits->first());

      $title = "Scheda Conteggio Visite ";
      $subTitle = $startDate->format('Y-m-d').' - '. $endDate->format('Y-m-d');
      $view = '_exports.pdf.schedaCountVisitPdf';
      $data = [
          'visits' => $visits,
      ];
      $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

      return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

    //SEZIONE IMPORT FILE EXCEL
    public function showImportXls(Request $req){
      return view('visit.import');
    }

    public function doImportXls(Request $req){
      $destinationPath = storage_path('app')."/upload/Visit/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = time() . '_file.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName);
      if(!((new VisitImport($fileName))->getResult())){  
        Session::flash('fail', 'Import failed');
      } else {
        Session::flash('success', 'Import successfull');
      }
      return Redirect::back();
    }


    public function mapVisitWithDistance($visits) {
      
      $mappedVisit = $visits->sortBy('user_id')->groupBy('user_id')->mapWithKeys(function ($groupUser, $user_id) {
        $user = $groupUser->first()->user;
        return collect([
          $user->name => $groupUser->sortBy('periodo')->groupBy('periodo')->mapWithKeys(function ($groupPeriodo, $periodo) {
            return collect([
              $periodo => $groupPeriodo->sortBy(['giorno'])->groupBy('giorno')->mapWithKeys(function ($visiteData, $data) {
                return  collect([
                  $data => $visiteData->sortBy('id')->map(function ($visita) {
                    $localita = null;
                    $cap = null;
                    $codnazione = null;
                    $regione = null;
                    $prov = null;
                    $contatto = null;
                    $formatted_address = null;
                    $nation_address = null;
                    if ($visita->tipologia == 'PC') {
                      $contatto = $visita->rubri;
                      $localita = $visita->rubri->localita;
                      $cap = $visita->rubri->cap;
                      $codnazione = $visita->rubri->codnazione;
                      // $regione = $visita->rubri->regione;
                      $prov = $visita->rubri->prov;
                    }
                    if ($visita->tipologia == 'C') {
                      $contatto = $visita->client;
                      $localita = $visita->client->localita;
                      $cap = $visita->client->cap;
                      $codnazione = $visita->client->codnazione;
                      // $regione = $visita->client->regione;
                      $prov = $visita->client->prov;
                    }
                    if ($visita->tipologia == 'F') {
                      $contatto = $visita->supplier;
                      $localita = $visita->supplier->localita;
                      $cap = $visita->supplier->cap;
                      $codnazione = $visita->supplier->codnazione;
                      // $regione = $visita->supplier->regione;
                      $prov = $visita->supplier->prov;
                    }
                    $geoposition = \GoogleMaps::load('geocoding')
                      ->setParam([
                        'address' => $localita . ',' . $cap . ',' . $prov . ',' . $codnazione,
                      ])->getResponseByKey('results');
                    if ($geoposition['results']) {
                      // dd($geoposition['results']);
                      foreach ($geoposition['results'][0]['address_components'] as $value) {
                        if($value['types'][0]=='country') $nation_address = $value['short_name'];
                      }
                      $formatted_address = $geoposition['results'][0]['formatted_address'];
                    }
                    // if ($formatted_address == null) {
                    //   dd($formatted_address);
                    // }
                    return collect([
                      'visita' => $visita,
                      'tipoContatto' => $visita->tipologia,
                      'contatto' => $contatto,
                      'formatted_address' => $formatted_address,
                      'nation_address' => $nation_address,
                      'prev_address' => '',
                      'prev_nation_address' => '',
                      'distanceKm' => 0,
                    ]);
                  }),
                ]);
              }),
            ]);
          }),
        ]);
      });

      foreach ($mappedVisit as $userMap => $groupUser) {
        $visitaFakeUser = $groupUser->first()->first()->first()['visita']->first()->user;
        $homeAddress = null;
        $homeNationAddress = null;
        $user = User::where('id', $visitaFakeUser->id)
          ->with([
            'agent' => function ($query) {
                $query
                  ->withoutGlobalScope('agent')
                  ->withoutGlobalScope('superAgent');
              }, 
            'client' => function ($query) {
              $query
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
            }])->first();
        // dd($user);
        if ($user && $user->agent && $user->client){
          $localita = $user->client->localita;
          $cap = $user->client->cap;
          $codnazione = $user->client->codnazione;
          // $regione = $visitaFakeUser->user->client->regione;
          $prov = $user->client->prov;
          $geoposition = \GoogleMaps::load('geocoding')
          ->setParam([
            'address' => $localita . ',' . $cap . ',' . $prov . ',' . $codnazione,
            ])->getResponseByKey('results');
            dd($geoposition);
          if ($geoposition['results']) {
            foreach ($geoposition['results'][0]['address_components'] as $value) {
              if ($value['types'][0] == 'country') $homeNationAddress = $value['short_name'];
            }
            $homeAddress = $geoposition['results'][0]['formatted_address'];
            if ($homeAddress=='PT'){
              $homeAddress = 'P';
            }
          }
        } 

        foreach ($groupUser as $periodo => $groupPeriodo) {
          $prevAddress = null;
          $prevNationAddress = null;
          $nVisitOfPeriodo = 0;
          foreach ($groupPeriodo as $day => $visite) {
            $nVisitOfPeriodo++;
            $nVisitOfDay = 0;
            foreach ($visite as $key => $visitaData) {
              $nVisitOfDay++;
              // $user = $visitaData['visita']->user;  
              if (($nVisitOfDay==1 and $homeNationAddress == $visitaData['nation_address']) or $nVisitOfPeriodo==1){
                $prevAddress = $homeAddress;
                $prevNationAddress = $homeNationAddress;
              }
              $distance = 0;
              $d =   \GoogleMaps::load('directions')
                ->setParamByKey('origin', $prevAddress)
                ->setParamByKey('destination', $visitaData['formatted_address'])
                ->setParamByKey('mode', 'driving')
                ->getResponseByKey('rows.elements');
              if(count($d['routes'])>0){
                $distance = $d['routes'][0]['legs'][0]['distance']['value']/1000;
              }
              $mappedVisit[$userMap][$periodo][$day][$key]['prev_address'] = $prevAddress;
              $mappedVisit[$userMap][$periodo][$day][$key]['prev_nation_address'] = $prevNationAddress;
              $mappedVisit[$userMap][$periodo][$day][$key]['distanceKm'] = $distance;
              if ($visitaData['formatted_address']){
              $prevAddress = $visitaData['formatted_address'];
              $prevNationAddress = $visitaData['nation_address'];
              } else {
                $mappedVisit[$userMap][$periodo][$day][$key]['formatted_address'] = '-- INSERIRE INDIRIZZO IN ANAGRAFICA --';
              }
            }
            # code...
          }
        }
      }

      return $mappedVisit;
    }
}

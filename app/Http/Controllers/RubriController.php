<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use knet\ExportsXLS\LEADImport;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Carbon\Carbon;

use knet\WebModels\wRubrica;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Agente;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;

use knet\Helpers\RedisUser;
use knet\ArcaModels\Agent;
use Auth;

class RubriController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){

      $contacts = wRubrica::select('id', 'descrizion', 'codnazione', 'agente', 'regione', 'localita', 'date_nextvisit', 'vote', 'codicecf', 'isModCarp01');
      $contacts = $contacts->with(['agent']);
      $contacts = $contacts->where('statocf', 'T');
      $contacts = $contacts->orderBy('date_lastvisit', 'DESC')->orderBy('descrizion');
      $contacts = $contacts->get();
      
      // $nazioni = Nazione::all();
      // $settori = Settore::all();
      $zone = wRubrica::distinct()->orderBy('prov')->get(['prov']);
      $regioni = wRubrica::distinct()->orderBy('regione')->get(['regione']);
      $agenti = wRubrica::distinct()->orderBy('agente')->with(['agent'])->get(['agente']);

      // $clients = $clients->paginate(25);
      // dd($clients);
      Session::forget('_old_input');
      return view('rubri.index', [
        'contacts' => $contacts,
        'fltContacts' => $contacts,//wRubrica::select('id', 'descrizion')->orderBy('descrizion')->get(),
        'zone' => $zone,
        'regioni' => $regioni,
        'agenti' => $agenti,
        'mapsException' => ''
      ]);
    }

    public function fltIndex (Request $req){
      $contacts = wRubrica::where('statocf', 'LIKE', ($req->input('optStatocf')=='' ? '%' : $req->input('optStatocf')));
      if($req->input('rubri_id')) {
        $contacts = $contacts->where('id', $req->input('rubri_id'));
      }
      if($req->input('partiva')) {
        if($req->input('partivaOp')=='eql'){
          $contacts = $contacts->where('partiva', strtoupper($req->input('partiva')));
        }
        if($req->input('partivaOp')=='stw'){
          $contacts = $contacts->where('partiva', 'LIKE', strtoupper($req->input('partiva')).'%');
        }
        if($req->input('partivaOp')=='cnt'){
          $contacts = $contacts->where('partiva', 'LIKE', '%'.strtoupper($req->input('partiva')).'%');
        }
      }
      if($req->input('regione')) {
        $contacts = $contacts->where('regione', $req->input('regione'));
      }
      if($req->input('prov')) {
        $contacts = $contacts->where('prov', $req->input('prov'));
      }
      if($req->input('agente')) {
        $contacts = $contacts->where('agente', $req->input('agente'));
      }
      if($req->input('optModCarp')) {
        $contacts = $contacts->where('isModCarp01', ($req->input('optModCarp')=='S' ? true : false));
      }
      $contacts = $contacts->select('id', 'descrizion', 'codnazione', 'agente', 'regione', 'localita', 'prov', 'date_nextvisit', 'vote', 'codicecf', 'isModCarp01');
      $contacts = $contacts->with('agent');
      $contacts = $contacts->get();

      $zone = $contacts->unique('prov')->sortByDesc('prov');
      $regioni = wRubrica::distinct()->orderBy('regione')->get(['regione']);
      $agenti = wRubrica::distinct()->orderBy('agente')->with(['agent'])->get(['agente']);

      $req->flash();

      return view('rubri.index', [
        'contacts' => $contacts,
        'fltContacts' => $contacts,//wRubrica::select('id', 'descrizion')->orderBy('descrizion')->get(),
        'zone' => $zone,
        'regioni' => $regioni,
        'agenti' => $agenti,
        'mapsException' => ''
      ]);
    }

    public function insertOrEdit (Request $req, $rubri_id=null){
      if (!empty($rubri_id)) {
        $contact = wRubrica::select('id', 'descrizion')->findOrFail($rubri_id);
      }
      
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $thisYear =  Carbon::now()->year;
      $prevYear = $thisYear - 1;
      $dataFineAgente = Carbon::createFromDate( $prevYear, 1, 1);
      $agenti = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();

      $returnToVisit = ($req->input('visit')) ? True : False;

      return view('rubri.insertOrEdit', [
        'contact' => $contact ?? '',
        'nazioni' => $nazioni,
        'settori' => $settori,
        'agenti' => $agenti,
        'returnToVisit' => $returnToVisit
      ]);
    }

    public function store (Request $req){
      // dd($req);
      $rubri = wRubrica::create([
        'descrizion' => $req->input('ragsoc'),
        'partiva' => ($req->input('vatCode') ? $req->input('vatCode') : ''),
        'user_id' => Auth::user()->id,
        'settore' => $req->input('sector'),
        'statocf' => 'T',
        'codnazione' => $req->input('nation'),
        'localita' => $req->input('location'),
        'indirizzo' => $req->input('address'),
        'cap' => $req->input('posteCode'),
        'email' => ($req->input('email') ? $req->input('email') : ''),
        'agente' => $req->input('referenceAgent'),
        'persdacont' => ($req->input('persdacont') ? $req->input('persdacont') : ''),
        'posperscon' => ($req->input('pospersdacont') ? $req->input('pospersdacont') : ''),
        'telefono' => ($req->input('phone') ? $req->input('phone') : ''),
        'sitoweb' => ($req->input('site') ? $req->input('site') : ''),
      ]);

      // if($req->input('rubri_id')){
      if($req->input('insertVisit')){
        return Redirect::route('visit::insertRubri', $rubri->id);
      }

      return Redirect::route('rubri::detail', $req->input('rubri_id'));
    }

    public function detail (Request $req, $rubri_id){
      $contact = wRubrica::with(['agent', 'client'])->findOrFail($rubri_id);
      $address = $contact->indirizzo.",".$contact->localita.",".$contact->prov.",".$contact->regione.", ".$contact->nazione;
      $expt = '';
      try {
        Mapper::location($address)
                ->map([
                  'zoom' => 11,
                  'center' => true,
                  'markers' => [
                    'title' => $contact->descrizion,
                    'animation' => 'DROP'
                  ],
                  'eventAfterLoad' => 'onMapLoad(maps[0].map);'
                ]);
      } catch (\Exception $e) {
        $expt = $e->getMessage();
      }
      $visits = wVisit::where('rubri_id', $rubri_id)->with('user')->take(3)->orderBy('data', 'desc')->orderBy('id')->get();
      // dd($visits->isEmpty());
      // dd($visits);
      return view('rubri.detail', [
        'contact' => $contact,
        'mapsException' => $expt,
        'visits' => $visits,
        'dateNow' => Carbon::now(),
      ]);
    }

    public function closeContact(Request $req, $rubri_id){
      $contact=wRubrica::findOrFail($rubri_id);
      $contact->statocf = 'C';
      $contact->save();
      return redirect()->action('RubriController@detail', $rubri_id);
    }

    //SEZIONE IMPORT FILE EXCEL
    public function showImport(Request $req){
      return view('rubri.import');
    }

    public function doImport(Request $req){
      $destinationPath = storage_path('app')."/upload/LEAD/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = time() . '_file.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName);
      if(!((new LEADImport($fileName, $req->input('country'), $req->input('mese')))->getResult())){  
        Session::flash('fail', 'Import failed');
      } else {
        Session::flash('success', 'Import successfull');
      }
      return Redirect::back();
    }
}

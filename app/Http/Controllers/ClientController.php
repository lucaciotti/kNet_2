<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\DB;

use knet\Http\Requests;
use knet\ArcaModels\Client;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;

use Auth;
use knet\User;

class ClientController extends Controller
{

    protected $connection = '';

    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){

      if($req->user()->role_name=='client'){
        return redirect()->action('ClientController@detail', $req-user()->codcli);
      }
      // on($this->connection)->
      $clients = Client::where('statocf', 'T')->where('agente', '!=', '');
      $clients = $clients->select('codice', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $clients = $clients->with(['agent']);
      $clients = $clients->get();
      
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();

      // $clients = $clients->paginate(25);
      // dd($clients);
      return view('client.index', [
        'clients' => $clients,
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
        'mapsException' => ''
      ]);
    }

    public function fltIndex (Request $req){
      // dd($req);
      $clients = Client::where('statocf', 'LIKE', ($req->input('optStatocf')=='' ? '%' : $req->input('optStatocf')));
      if($req->input('ragsoc')) {
        if($req->input('ragsocOp')=='eql'){
          $clients = $clients->where('descrizion', strtoupper($req->input('ragsoc')));
        }
        if($req->input('ragsocOp')=='stw'){
          $clients = $clients->where('descrizion', 'LIKE', strtoupper($req->input('ragsoc')).'%');
        }
        if($req->input('ragsocOp')=='cnt'){
          $clients = $clients->where('descrizion', 'LIKE', '%'.strtoupper($req->input('ragsoc')).'%');
        }
      }
      if($req->input('settore')) {
        $clients = $clients->whereIn('settore', $req->input('settore'));
      }
      if($req->input('nazione')) {
        $clients = $clients->whereIn('codnazione', $req->input('nazione'));
      }
      if($req->input('zona')) {
        $clients = $clients->whereIn('zona', $req->input('zona'));
      }
      $clients = $clients->where('agente', '!=', '');
      $clients = $clients->select('codice', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $clients = $clients->with('agent');
      $clients = $clients->get();
      // $clients = $clients->paginate(25);
      // $clients = $clients->appends($req->all());
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();

      return view('client.index', [
        'clients' => $clients,
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
      ]);
    }

    public function detail (Request $req, $codCli){
      $client = Client::with(['agent', 'detNation', 'detZona', 'detSect', 'clasCli', 'detPag', 'detStato'])->findOrFail($codCli);
      $scadToPay = ScadCli::where('codcf', $codCli)->where('pagato',0)->whereIn('tipoacc', ['F', ''])->orderBy('datascad','desc')->get();
      $address = $client->indirizzo.", ".$client->localita.", ".$client->nazione;
      $expt = '';
      try {
        Mapper::location($address)
                ->map([
                  'zoom' => 14,
                  'center' => true,
                  'markers' => [
                    'title' => $client->descrizion,
                    'animation' => 'DROP'
                  ],
                  'eventAfterLoad' => 'onMapLoad(maps[0].map);'
                ]);
      } catch (\Exception $e) {
        $expt = $e->getMessage();
      }
      $visits = wVisit::where('codicecf', $codCli)->with('user')->take(3)->orderBy('data', 'desc')->orderBy('id')->get();
      // dd($visits->isEmpty());
      // dd($visits);
      return view('client.detail', [
        'client' => $client,
        'scads' => $scadToPay,
        'mapsException' => $expt,
        'visits' => $visits,
        'dateNow' => Carbon::now(),
      ]);
    }

    public function allCustomers (Request $req){
      return Client::paginate();
    }
}

<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
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
use knet\Helpers\RedisUser;

class ClientController extends Controller
{

    protected $connection = '';

    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){

      if(RedisUser::get('role')=='client'){
        return redirect()->action('ClientController@detail', RedisUser::get('codcli'));
      }
      // on($this->connection)->
      $clients = Client::whereNotIn('statocf', ['C', 'L', 'S', '1', '2', 'B', 'N'])->where('agente', '!=', '');
      $clients = $clients->select('codice', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $clients = $clients->with(['agent']);
      $clients = $clients->get();
      
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();

      // $clients = $clients->paginate(25);
      // dd($clients);
      Session::forget('_old_input');
      return view('client.index', [
        'clients' => $clients,
        'fltClients' => Client::select('codice', 'descrizion')->orderBy('descrizion')->get(),
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
        $clients = $clients->where('codice', $req->input('ragsoc'));
      }
      if($req->input('partiva')) {
        if($req->input('partivaOp')=='eql'){
          $clients = $clients->where('partiva', strtoupper($req->input('partiva')));
        }
        if($req->input('partivaOp')=='stw'){
          $clients = $clients->where('partiva', 'LIKE', strtoupper($req->input('partiva')).'%');
        }
        if($req->input('partivaOp')=='cnt'){
          $clients = $clients->where('partiva', 'LIKE', '%'.strtoupper($req->input('partiva')).'%');
        }
      }
      if($req->input('codcli')) {
        if($req->input('codcliOp')=='eql'){
          $clients = $clients->where('codice', strtoupper($req->input('codcli')));
        }
        if($req->input('codcliOp')=='stw'){
          $clients = $clients->where('codice', 'LIKE', strtoupper($req->input('codcli')).'%');
        }
        if($req->input('codcliOp')=='cnt'){
          $clients = $clients->where('codice', 'LIKE', '%'.strtoupper($req->input('codcli')).'%');
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
      
      $req->flash();

      return view('client.index', [
        'clients' => $clients,
        'fltClients' => $clients,//Client::select('codice', 'descrizion')->orderBy('descrizion')->get(),
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
      ]);
    }

    public function detail (Request $req, $codCli){
      $client = Client::with(['agent', 'detNation', 'detZona', 'detSect', 'clasCli', 'detPag', 'detStato', 'grpCli', 'anagNote'])->findOrFail($codCli);
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
      // dd($client);
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

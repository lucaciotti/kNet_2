<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\DB;

use knet\Http\Requests;
use knet\ArcaModels\Supplier;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;

use Auth;
use knet\User;
use knet\Helpers\RedisUser;

class SupplierController extends Controller
{

    protected $connection = '';

    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){

      if(RedisUser::get('role')=='supplier'){
        return redirect()->action('SupplierController@detail', RedisUser::get('codcli'));
      }
      // on($this->connection)->
      $suppliers = Supplier::whereNotIn('statocf', ['C', 'L', 'S', '1', '2', 'B', 'N']);
      $suppliers = $suppliers->select('codice', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $suppliers = $suppliers->with(['agent']);
      $suppliers = $suppliers->get();
      
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();

      // $suppliers = $suppliers->paginate(25);
      // dd($suppliers);
      Session::forget('_old_input');
      return view('supplier.index', [
        'suppliers' => $suppliers,
        'fltsuppliers' => Supplier::select('codice', 'descrizion')->orderBy('descrizion')->get(),
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
        'mapsException' => ''
      ]);
    }

    public function fltIndex (Request $req){
      // dd($req);
      $suppliers = Supplier::where('statocf', 'LIKE', ($req->input('optStatocf')=='' ? '%' : $req->input('optStatocf')));
      if($req->input('ragsoc')) {
        $suppliers = $suppliers->where('codice', $req->input('ragsoc'));
      }
      if($req->input('partiva')) {
        if($req->input('partivaOp')=='eql'){
          $suppliers = $suppliers->where('partiva', strtoupper($req->input('partiva')));
        }
        if($req->input('partivaOp')=='stw'){
          $suppliers = $suppliers->where('partiva', 'LIKE', strtoupper($req->input('partiva')).'%');
        }
        if($req->input('partivaOp')=='cnt'){
          $suppliers = $suppliers->where('partiva', 'LIKE', '%'.strtoupper($req->input('partiva')).'%');
        }
      }
      if($req->input('codcli')) {
        if($req->input('codcliOp')=='eql'){
          $suppliers = $suppliers->where('codice', strtoupper($req->input('codcli')));
        }
        if($req->input('codcliOp')=='stw'){
          $suppliers = $suppliers->where('codice', 'LIKE', strtoupper($req->input('codcli')).'%');
        }
        if($req->input('codcliOp')=='cnt'){
          $suppliers = $suppliers->where('codice', 'LIKE', '%'.strtoupper($req->input('codcli')).'%');
        }
      }
      if($req->input('settore')) {
        $suppliers = $suppliers->whereIn('settore', $req->input('settore'));
      }
      if($req->input('nazione')) {
        $suppliers = $suppliers->whereIn('codnazione', $req->input('nazione'));
      }
      if($req->input('zona')) {
        $suppliers = $suppliers->whereIn('zona', $req->input('zona'));
      }
      // $suppliers = $suppliers->where('agente', '!=', '');
      $suppliers = $suppliers->select('codice', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $suppliers = $suppliers->with('agent');
      $suppliers = $suppliers->get();
      // $suppliers = $suppliers->paginate(25);
      // $suppliers = $suppliers->appends($req->all());
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();
      
      $req->flash();

      return view('supplier.index', [
        'suppliers' => $suppliers,
        'fltsuppliers' => $suppliers,//supplier::select('codice', 'descrizion')->orderBy('descrizion')->get(),
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
      ]);
    }

    public function detail (Request $req, $codCli){
      $supplier = Supplier::with(['agent', 'detNation', 'detZona', 'detSect', 'clasCli', 'detPag', 'detStato', 'grpCli', 'anagNote'])->findOrFail($codCli);
      $scadToPay = ScadCli::where('codcf', $codCli)->where('pagato',0)->whereIn('tipoacc', ['F', ''])->orderBy('datascad','desc')->get();
      $address = $supplier->indirizzo.", ".$supplier->localita.", ".$supplier->nazione;
      $expt = '';
      try {
        Mapper::location($address)
                ->map([
                  'zoom' => 14,
                  'center' => true,
                  'markers' => [
                    'title' => $supplier->descrizion,
                    'animation' => 'DROP'
                  ],
                  'eventAfterLoad' => 'onMapLoad(maps[0].map);'
                ]);
      } catch (\Exception $e) {
        $expt = $e->getMessage();
      }
      $visits = wVisit::where('codicecf', $codCli)->with('user')->take(3)->orderBy('data', 'desc')->orderBy('id')->get();
      // dd($visits->isEmpty());
      // dd($supplier);
      return view('supplier.detail', [
        'supplier' => $supplier,
        'scads' => $scadToPay,
        'mapsException' => $expt,
        'visits' => $visits,
        'dateNow' => Carbon::now(),
      ]);
    }

    public function allCustomers (Request $req){
      return Supplier::paginate();
    }
}

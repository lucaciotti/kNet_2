<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use knet\ExportsXLS\LEADImport;

use knet\WebModels\wRubrica;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;

class RubriController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){

      if($req->user()->role_name=='client'){
        return redirect()->action('ClientController@detail', $req-user()->codcli);
      }
      // on($this->connection)->
      $contacts = wRubrica::select('id', 'descrizion', 'codnazione', 'agente', 'localita', 'settore');
      $contacts = $contacts->with(['agent']);
      $contacts = $contacts->get();
      
      $nazioni = Nazione::all();
      $settori = Settore::all();
      $zone = Zona::all();

      // $clients = $clients->paginate(25);
      // dd($clients);
      return view('rubri.index', [
        'contacts' => $contacts,
        'nazioni' => $nazioni,
        'settori' => $settori,
        'zone' => $zone,
        'mapsException' => ''
      ]);
    }

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

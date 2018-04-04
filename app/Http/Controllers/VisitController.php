<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use knet\Http\Requests;
use Torann\Registry\Facades\Registry;

use knet\ArcaModels\Client;
use knet\WebModels\wVisit;
use Auth;

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
      ]);
    }

    public function store(Request $req){
      // dd($req);
      $visit = wVisit::create([
        'codicecf' => $req->input('codcli'),
        'user_id' => Auth::user()->id,
        'data' => new Carbon($req->input('data')),
        'tipo' => $req->input('tipo'),
        'descrizione' => $req->input('descrizione'),
        'note' => $req->input('note')
      ]);

      return Redirect::route('visit::show', $req->input('codcli'));
    }

    public function show(Request $req, $codCli){
      // dd($req);
      $visits = wVisit::where('codicecf', $codCli)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
      $client = Client::findOrFail($codCli);

      return view('visit.show', [
        'visits' => $visits,
        'client' => $client,
        'dateNow' => Carbon::now(),
        ]);
    }
}

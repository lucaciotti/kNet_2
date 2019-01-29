<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use knet\Http\Requests;

use knet\ArcaModels\Client;
use knet\WebModels\wVisit;
use knet\WebModels\wRubrica;
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
        'codicecf' => ($req->input('codcli') ? $req->input('codcli') : null),
        'rubri_id' => ($req->input('rubri_id') ? $req->input('rubri_id') : null),
        'user_id' => Auth::user()->id,
        'data' => new Carbon($req->input('data')),
        'tipo' => $req->input('tipo'),
        'descrizione' => $req->input('descrizione'),
        'note' => $req->input('note')
      ]);

      if($req->input('rubri_id')){
        $contact = wRubrica::find($req->input('rubri_id'));
        $contact->date_lastvisit = new Carbon($req->input('data'));
        $contact->date_nextvisit = (new Carbon($req->input('data')))->addDays(60);
        $contact->save();
      }

      return Redirect::route('visit::show', $req->input('codcli'), $req->input('rubri_id'));
    }

    public function show(Request $req, $codCli=null, $rubri_id=null ){
      // dd($req);
      if($codCli){
        $visits = wVisit::where('codicecf', $codCli)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
        $client = Client::findOrFail($codCli);
      } elseif($rubri_id){
        $visits = wVisit::where('rubri_id', $rubri_id)->with('user')->orderBy('data', 'desc')->orderBy('id')->get();
        $client = wRubrica::findOrFail($rubri_id);
      }

      return view('visit.show', [
        'visits' => $visits,
        'client' => $client,
        'dateNow' => Carbon::now(),
        ]);
    }
}

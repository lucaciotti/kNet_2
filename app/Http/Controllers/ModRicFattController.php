<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use knet\WebModels\wModricFat;
use knet\WebModels\wSysMkt;
use knet\ArcaModels\Client;
use Auth;

class ModRicFattController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createModule(Request $req, $codicecf = null)
    {
        if(empty($codicecf)){
            $codicecf= 'C09005';
        }
        // dd(wRubrica::find($rubri_id));
        return view('modRicFat.create', [
            'client' => Client::find($codicecf),
            'sysMkt' => wSysMkt::where('codice', 'LIKE', 'KU%')->orWhere('codice', 'LIKE', 'KZ%')->get(),
            'sysOther' => wSysMkt::where('codice', 'LIKE', 'OT%')->get(),
        ]);
    }

    public function store(Request $req)
    {
        $client = Client::find($req->input('codicecf'));
        $modCarp = wModricFat::create([
            'data_ricezione' => $req->input('data_ricezione'),
            'richiedente' => $req->input('richiedente'),
            'email_richiedente' => $req->input('email_richiedente'),
            'ragione_sociale' => $req->input('ragione_sociale'),
            'codicecf' => $req->input('codicecf'),
            'tipologia_prodotto' => $req->input('tipologia_prodotto'),
            'descr_pers' => $req->input('descr_pers'),
            'url_pers' => $req->input('url_pers'),
            'system_kk' => $req->input('system_kk'),
            'system_other' => $req->input('system_other'),
            'info_tecn_comm' => $req->input('info_tecn_comm'),
            'imballaggio' => $req->input('imballaggio'),
            'um' => $req->input('um'),
            'quantity' => $req->input('quantity'),
            'periodo_ordinativi' => $req->input('periodo_ordinativi'),
            'target_price' => $req->input('target_price'),
            'ditta' => $req->input('ditta'),
            'user_id' =>  Auth::user()->id
        ]);

        return ['Richiesta Fattibilità Salvata'];
    }
}

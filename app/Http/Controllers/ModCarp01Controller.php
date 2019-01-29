<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use knet\WebModels\wModCarp01;
use knet\WebModels\wMCarp01_SysBuyOfKK;
use knet\WebModels\wMCarp01_SysBuyOfOther;
use knet\WebModels\wMCarp01_SysKnown;
use knet\WebModels\wMCarp01_SysLiked;
use knet\WebModels\wSysMkt;
use knet\WebModels\wRubrica;
use knet\WebModels\wVisit;
use Auth;

class ModCarp01Controller extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function createModule(Request $req, $rubri_id){
        // dd(wRubrica::find($rubri_id));
        return view('modCarp01.create', [
            'contact' => wRubrica::find($rubri_id),
            'sysMkt' => wSysMkt::all(),
        ]);
    }
    
    public function store(Request $req){
        $contact=wRubrica::find($req->input('rubri_id'));
        $modCarp = wModCarp01::create([
            'rubri_id' => $req->input('rubri_id'),
            'prod_mobili' => $req->input('typeProdMobili'),
            'prod_porte' => $req->input('typeProdPorte'),
            'prod_portefinestre' => $req->input('typeProdFinestre'),
            'prod_cucine' => $req->input('typeProdCucine'),
            'prod_other' => $req->input('typeProdOther'),
            'prod_note' => $req->input('noteProdOther'),
            'know_kk' => $req->input('rConosceKK')=='true' ? true : false,
            'isKkBuyer' => $req->input('rAcquistaKK')=='true' ? true : false,
            'yes_supplierType' => intval($req->input('yes_supplierType')),
            'yes_supplierName' => $req->input('yes_supplierName'),
            'yes_isInformato' => $req->input('yes_isInformato'),
            'not_why_prezzo' => $req->input('not_why_prezzo'),
            'not_why_qualita' => $req->input('not_why_qualita'),
            'not_why_servizio' => $req->input('not_why_servizio'),
            'not_why_catalogo' => $req->input('not_why_catalogo'),
            'not_why_noinfo' => $req->input('not_why_noinfo'),
            'not_supplierType' => intval($req->input('not_supplierType')),
            'not_supplierName' => $req->input('not_supplierName'),
            'wants_tryKK' => $req->input('wants_tryKK'),
            'notryKK_note' => $req->input('notryKK_note'),
            'wants_info' => $req->input('wants_info'),
            'final_note' => $req->input('final_note'),
            'vote' => $req->input('vote'),
            'user_id' =>  Auth::user()->id
        ]);
        if($req->input('sysKnown')){
            foreach($req->input('sysKnown') as $sys){
                wMCarp01_SysKnown::create([
                    'mcarp01_id' => $modCarp->id,
                    'sysmkt_cod' => $sys['codice']
                ]);
            }
        }        
        if($req->input('sysBuyOfKK')){
            foreach($req->input('sysBuyOfKK') as $sys){
                wMCarp01_SysBuyOfKK::create([
                    'mcarp01_id' => $modCarp->id,
                    'sysmkt_cod' => $sys['codice']
                ]);
            }
        }        
        if($req->input('sysBuyOfOther')){
            foreach($req->input('sysBuyOfOther') as $sys){
                wMCarp01_SysBuyOfOther::create([
                    'mcarp01_id' => $modCarp->id,
                    'sysmkt_cod' => $sys['codice']
                ]);
            }
        }        
        if($req->input('sysLiked')){
            foreach($req->input('sysLiked') as $sys){
                wMCarp01_SysLiked::create([
                    'mcarp01_id' => $modCarp->id,
                    'sysmkt_cod' => $sys['codice']
                ]);
            }
        }
        $visit = wVisit::create([
            'codicecf' => $contact->codicecf,
            'rubri_id' => $req->input('rubri_id'),
            'user_id' => Auth::user()->id,
            'data' => Carbon::now(),
            'tipo' => "MCarp",
            'descrizione' => "Compilazione Modulo Falegnami",
            'modCarp_id' => $modCarp->id
        ]);

        // Infine aggiorno il contatto con informazioni Chiave.
        $contact->isKKBuyer = $modCarp->isKKBuyer;
        $contact->know_kk = $modCarp->know_kk;
        $contact->wants_tryKK = $modCarp->wants_tryKK;
        $contact->wants_info = $modCarp->wants_info;
        $contact->prod_mobili = $modCarp->prod_mobili;
        $contact->prod_porte = $modCarp->prod_porte;
        $contact->prod_portefinestre = $modCarp->prod_portefinestre;
        $contact->prod_cucine = $modCarp->prod_cucine;
        $contact->prod_other = $modCarp->prod_other;
        $contact->prod_note = $modCarp->prod_note;
        $contact->vote = $modCarp->vote;
        $contact->isModCarp01 = true;
        $contact->date_lastvisit = Carbon::now();
        $contact->date_nextvisit = Carbon::now()->addDays(60);
        $contact->save();


        return ['Modulo Falegnami Salvato'];
    }

}

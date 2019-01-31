<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

// use knet\Http\Requests;
use knet\Helpers\RedisUser;

use knet\ArcaModels\Listini;
use knet\ArcaModels\Client;
use knet\ArcaModels\GrpCli;
use knet\ArcaModels\Agent;
use knet\ArcaModels\SuperAgent;
use knet\ArcaModels\Product;
use knet\ArcaModels\GrpProd;
use knet\ArcaModels\SubGrpProd;
use knet\WebModels\wListiniOk;

class ListiniController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function idxCli (Request $req, $codCli=null) {
        $thisYear = Carbon::now()->year;
        $date = Carbon::now();
        // $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();

        if(RedisUser::get('ditta_DB')=='kNet_es' || (RedisUser::get('ditta_DB')=='kNet_it' && RedisUser::get('codag')=='002'))
        {
           $endOfYear = new Carbon('last day of December 2018');
        }

        // ->where('datafine', '<=', $endOfYear)
        $customers = Listini::select('codclifor')->where('codclifor', '!=', '')
                        ->with(['client'=>function($q){
                        $q->select('codice', 'descrizion');
                        }])
                        ->groupBy('codclifor')
                        ->orderBy('codclifor')->get();
        $codCli = ($req->input('codcli')) ? $req->input('codcli') : $codCli;
        $customer = (!empty($codCli)) ? $codCli : $customers->first()->codclifor;
        $customerDet = Client::select('codice', 'descrizion', 'gruppolist')
                        ->with('grpCli')
                        ->where('codice', $customer)->first();
        
        $ListProds = Listini::where('codclifor', $customer)
                        ->where('codicearti', '!=', '');
        if($req->input('gruppo')) {
            $ListProds = $ListProds->whereIn('gruppomag', $req->input('gruppo'));
        }
        // $ListProds = $ListProds->where('datafine', '<=', $endOfYear);
        /* if(!empty($req->input('optTipoDoc'))) {
            $ListProds = $ListProds->where('tipo_prod', $req->input('optTipoDoc'));
        } else {
            $ListProds = $ListProds->whereIn('tipo_prod', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);
        } */
        $ListProds = $ListProds->with([ 
                    'product' => function($query){
                        $query->select('codice', 'descrizion', 'unmisura', 'gruppo', 'listino6', 'listino1')
                            ->withoutGlobalScope('Listino')
                            ->with('grpProd');
                    },
                    'wListOk'
                    ])
                    ->orderBy('codicearti')
                    ->get();
        // dd($ListProds);
        $ListGrpProds = Listini::where('codclifor', $customer)
                        ->where('gruppomag', '!=', '');
        if($req->input('gruppo')) {
            $ListGrpProds = $ListGrpProds->whereIn('gruppomag', $req->input('gruppo'));
        }
        // $ListGrpProds = $ListGrpProds->where('datafine', '<=', $endOfYear);
        $ListGrpProds = $ListGrpProds->with([
                    'grpProd' => function($query){
                        $query->select('codice', 'descrizion');
                    },
                    'masterProd' => function($query){
                        $query->select('codice', 'descrizion');
                    },
                    'wListOk'
                    ])
                    ->orderBy('gruppomag')
                    ->get();
        // dd($ListGrpProds);

        $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                    ->where('codice', 'NOT LIKE', 'DIC%')
                    ->where('codice', 'NOT LIKE', '0%')
                    ->where('codice', 'NOT LIKE', '2%')
                    ->orderBy('codice')
                    ->get();          

        return view('listini.idxCli', [
            'customers' => $customers,
            'customer' => $customer,
            'customerDet' => $customerDet,
            'ListProds' => $ListProds,
            'ListGrpProds' => $ListGrpProds,
            'thisYear' => $thisYear,
            'endOfYear' => $endOfYear,
            'gruppi' => $gruppi,
        ]);
    }

    public function idxGrpCli (Request $req, $grpCli=null) {
        $thisYear = Carbon::now()->year;
        $date = Carbon::now();
        // $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();
        if(RedisUser::get('ditta_DB')=='kNet_es' || (RedisUser::get('ditta_DB')=='kNet_it' && RedisUser::get('codag')=='002'))
        {
           $endOfYear = new Carbon('last day of December 2018');
        }
        // ->where('datafine', '<=', $endOfYear)
        $cliGrps = Listini::select('gruppocli')->where('gruppocli', '!=', '')
                        ->with(['grpCli'=>function($q){
                        $q->select('codice', 'descrizion');
                        }])
                        ->groupBy('gruppocli')
                        ->orderBy('gruppocli')->get();
        $codGrp = ($req->input('grpCli')) ? $req->input('grpCli') : $grpCli;
        $customerGrp = (!empty($codGrp)) ? $codGrp : $cliGrps->first()->gruppocli;
        $grpCliDet = GrpCli::select('codice', 'descrizion')
                        ->with(['client'=>function($q){
                                $q->select('codice', 'descrizion', 'gruppolist');
                            }])
                        ->where('codice', $customerGrp)->first();
        // dd($grpCliDet);
        $ListProds = Listini::where('gruppocli', $customerGrp)
                        ->where('codicearti', '!=', '');
        if($req->input('gruppo')) {
            $ListProds = $ListProds->whereIn('gruppomag', $req->input('gruppo'));
        }
        // $ListProds = $ListProds->where('datafine', '<=', $endOfYear);
        /* if(!empty($req->input('optTipoDoc'))) {
            $ListProds = $ListProds->where('tipo_prod', $req->input('optTipoDoc'));
        } else {
            $ListProds = $ListProds->whereIn('tipo_prod', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);
        } */
        $ListProds = $ListProds->with([ 
                    'product' => function($query){
                        $query->select('codice', 'descrizion', 'unmisura', 'gruppo', 'listino6', 'listino1')
                            ->withoutGlobalScope('Listino')
                            ->with('grpProd');
                    }
                    ])
                    ->orderBy('codicearti')
                    ->get();
        // dd($ListProds);
        $ListGrpProds = Listini::where('gruppocli', $customerGrp)
                        ->where('gruppomag', '!=', '');
        if($req->input('gruppo')) {
            $ListGrpProds = $ListGrpProds->whereIn('gruppomag', $req->input('gruppo'));
        }
        // $ListGrpProds = $ListGrpProds->where('datafine', '<=', $endOfYear);
        $ListGrpProds = $ListGrpProds->with([
                    'grpProd' => function($query){
                        $query->select('codice', 'descrizion');
                    },
                    'masterProd' => function($query){
                        $query->select('codice', 'descrizion');
                    }
                    ])
                    ->orderBy('gruppomag')
                    ->get();
        // dd($ListGrpProds);

        $gruppi = GrpProd::where('codice', 'NOT LIKE', '1%')
                    ->where('codice', 'NOT LIKE', 'DIC%')
                    ->where('codice', 'NOT LIKE', '0%')
                    ->where('codice', 'NOT LIKE', '2%')
                    ->orderBy('codice')
                    ->get();          

        return view('listini.idxGrpCli', [
            'cliGrps' => $cliGrps,
            'customerGrp' => $customerGrp,
            'grpCliDet' => $grpCliDet,
            'ListProds' => $ListProds,
            'ListGrpProds' => $ListGrpProds,
            'thisYear' => $thisYear,
            'endOfYear' => $endOfYear,
            'gruppi' => $gruppi,
        ]);
    }

    public function setListOk(Request $req, $grpCli=null){
        if($req->estendi){
            foreach($req->estendi as $listId){
                $listOk = wListiniOk::create([
                    'listini_id'  =>$listId,
                    'esercizio' => '2019',
                ]);
                $listOk->save();
            }
        }
        if($req->routeCli){
            return redirect()->action('ListiniController@idxCli', $req->routeCli);
        } else {
            return redirect()->action('ListiniController@grpCli', $req->routeGrp);
        }
        
    }

    public function cliListScad(Request $req){
        $thisYear = Carbon::now()->year;
        $date = Carbon::now();
        // $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();
        if(RedisUser::get('ditta_DB')=='kNet_es' || (RedisUser::get('ditta_DB')=='kNet_it' && RedisUser::get('codag')=='002'))
        {
           $endOfYear = new Carbon('last day of December 2018');
        }

        $customers = Listini::select(
                            'codclifor',
                            DB::raw('SUM(IF(codicearti!="", 1, 0)) as nCodArt'),
                            DB::raw('SUM(IF(gruppomag!="", 1, 0)) as nGrpMag')
                            )
                        ->where('codclifor', '!=', '')->where('datafine', '<=', $endOfYear)
                        ->whereDoesntHave('wListOk')
                        ->with(['client'=>function($q){
                            $q->select('codice', 'descrizion');
                        }])
                        ->groupBy('codclifor')
                        ->orderBy('codclifor')->get();
        // dd($customers->first());

        return view('listini.cliListScad', [
            'customers' => $customers,
            'thisYear' => $thisYear,
            'endOfYear' => $endOfYear,
        ]);

    }

}

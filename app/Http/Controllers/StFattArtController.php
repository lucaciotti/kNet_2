<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\Helpers\RedisUser;
use knet\ArcaModels\Agent;
use knet\ArcaModels\Settore;

class StFattArtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function idxAg(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $agente = (string) (!empty($codAg)) ? $codAg : (!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice);
        $descrAg = (!empty($agentList->whereStrict('codice', $agente)->first()) ? $agentList->whereStrict('codice', $agente)->first()->descrizion : "");
        $thisYear = (Carbon::now()->year);
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal')) ? $req->input('limitVal') : 0;
        // dd($agentList);

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', function ($join) use ($agente) {
                $join->on('anagrafe.codice', '=', 'u_statfatt_art.codicecf')
                    ->where('anagrafe.agente', '=', $agente);
            })
            ->join('agenti', function ($join) use ($agente) {
                $join->on('agenti.codice', '=', 'anagrafe.agente')
                    // ->orOn('agenti.codice', '=', 'anagrafe.agente2')
                    ->whereRaw('LENGTH(agenti.codice) = ?', [strlen($agente)]);
            })
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicecf')
            ->selectRaw('MAX(anagrafe.descrizion) as ragionesociale, MAX(settori.descrizion) as settore, MAX(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear-1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear-2]);
        
        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('anagrafe.agente = ? AND LENGTH(anagrafe.agente) = ?', [$agente, strlen($agente)]);
        if($settoreSelected!=null) $fatList->whereIn('anagrafe.settore', $settoreSelected);
        $fatList->groupBy('codicecf');
        // $fatList->havingRaw('fatN > ?', [$limitVal]);

        // dd($fatList->get());

        return view('stFattArt.idxAg', [
            'agentList' => $agentList,
            'agente' => $agente,
            'descrAg' => $descrAg,
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
            'settoriList' => Settore::all(),
            'settoreSelected' => $settoreSelected,
            'limitVal' => $limitVal,
            'fatList' => $fatList->get(),
        ]);
    }
}

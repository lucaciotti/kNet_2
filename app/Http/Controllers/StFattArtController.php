<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\Helpers\RedisUser;
use knet\ArcaModels\Agent;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\SubGrpProd;

class StFattArtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function idxAg(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : ($codAg ? array_wrap($codAg) : $codAg);
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
        $thisYear = (Carbon::now()->year);
        $zoneList = strpos($codAg[0], 'A')==0 ? Zona::whereRaw('LEFT(codice,1)=?', ['0'])->get() : Zona::whereRaw('LEFT(codice,1)!=?', ['0'])->get();
        $grpPrdList = SubGrpProd::where('codice', 'NOT LIKE', '1%')
            ->where('codice', 'NOT LIKE', 'DIC%')
            ->where('codice', 'NOT LIKE', '0%')
            ->where('codice', 'NOT LIKE', '2%')
            ->orderBy('codice')
            ->get(); 
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal')=='0') ? $req->input('limitVal') : 0;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);
        // dd($req->input('limitVal'));

        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot' );
        // dd($querySelect_fatN);
        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicecf')
            ->selectRaw('MAX(anagrafe.descrizion) as ragionesociale, MAX(settori.descrizion) as settore, MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear-1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear-2]);
        
        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,'.$querySelect_fatN.', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereIn('anagrafe.agente', $fltAgents);
        $fatList->whereRaw('(LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ?)', ['CAMP', 'NOTA', 'BONU']);
        $fatList->whereRaw('(LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,3) != ?)', ['C', '2', 'DIC']);
        if ($settoreSelected != null) $fatList->whereIn('anagrafe.settore', $settoreSelected);
        if ($zoneSelected != null) $fatList->whereIn('anagrafe.zona', $zoneSelected);
        if ($req->input('grpPrdSelected')) {
            $fatList->whereIn('u_statfatt_art.gruppo', $req->input('grpPrdSelected'));
        }
        if (!empty($req->input('optTipoProd'))) {
            $fatList->where('u_statfatt_art.prodotto', $req->input('optTipoProd'));
        }
        $fatList->groupBy('codicecf');
        $fatList->havingRaw('fatN >= ?', [$limitVal]);
        $fatList=$fatList->get();

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);
        // dd($fatList->get());

        return view('stFattArt.idxAg', [
            'agentList' => $agentList,
            'fltAgents' => $fltAgents,
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
            'settoriList' => Settore::all(),
            'zone' => $zoneList,
            'settoreSelected' => $settoreSelected,
            'zoneSelected' => $zoneSelected,
            'grpPrdList' => $grpPrdList,
            'grpPrdSelected' => $req->input('grpPrdSelected'),
            'optTipoProd' => $req->input('optTipoProd'),
            'limitVal' => $limitVal,
            'fatList' => $fatList,
            'mese' => $meseRif,
            'onlyMese' => $onlyMese,
            'pariperiodo' => $isPariPeriodo
        ]);
    }

    private function buildQueryPeriodo($prefColumn, $mese, $onlyMese)
    {
        $q = '';
        if($onlyMese){
            $q= $prefColumn . str_pad(strval($mese), 2, "0", STR_PAD_LEFT);
        }
        else{
            for($i=1;$i<=$mese; $i++){
                if (empty($q)) {
                    $q .= $prefColumn . str_pad(strval($i), 2, "0", STR_PAD_LEFT);
                } else {
                    $q .= '+' . $prefColumn . str_pad(strval($i), 2, "0", STR_PAD_LEFT);
                }
            }
        }
        return $q;
    }
}

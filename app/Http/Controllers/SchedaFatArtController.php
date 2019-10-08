<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\Helpers\RedisUser;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\Product;
use knet\Exports\StatFatArtExport;
use knet\Exports\StatFatArtListCliExport;
use knet\Helpers\PdfReport;
use Maatwebsite\Excel\Facades\Excel;

class SchedaFatArtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codicecf = null)
    {
        $codCli = ($req->input('codicecf')) ? $req->input('codicecf') : $codicecf;
        $customer = Client::with(['agent', 'detNation', 'detZona', 'detSect', 'clasCli', 'detPag', 'detStato'])->findOrFail($codCli);
        $thisYear = (Carbon::now()->year);
        // $settori = ($req->input('settori')) ? $req->input('settori') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : null;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);

        $querySelect_qta = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.qta_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.qta_tot';
        $querySelect_qtaN = ($isPariPeriodo ? $querySelect_qta : 'u_statfatt_art.qta_tot');
        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->leftjoin('magart', 'magart.codice', '=', 'u_statfatt_art.codicearti')
            ->leftJoin('maggrp', 'maggrp.codice', '=', 'u_statfatt_art.gruppo')
            ->leftjoin('maggrp as macrogrp', function ($join) {
                $join->on('macrogrp.codice', '=', 'u_statfatt_art.macrogrp')
                    ->whereRaw('LENGTH(macrogrp.codice) = ?', [3]);
            })
            ->select('u_statfatt_art.codicearti')
            ->selectRaw('MAX(magart.descrizion) as descrArt')
            ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
            ->selectRaw('MAX(macrogrp.descrizion) as descrMacrogrp')
            ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
            ->selectRaw('MAX(maggrp.descrizion) as descrGruppo')
            ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
            ->selectRaw('MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,'.$querySelect_qta.', 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fat. ')/('.$querySelect_qta.'), 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.codicecf = ?', [$codCli]);
        $fatList->whereRaw('u_statfatt_art.esercizio >= ?', [$thisYear - $yearBack]);
        $fatList->whereRaw('(LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ?)', ['CAMP', 'NOTA', 'BONU']);
        $fatList->whereRaw('(LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,3) != ?)', ['C', '2', 'DIC']);
        if ($req->input('grpPrdSelected')) {
            $fatList->whereIn('u_statfatt_art.gruppo', $req->input('grpPrdSelected'));
        }
        if (!empty($req->input('optTipoProd'))) {
            $fatList->where('u_statfatt_art.prodotto', $req->input('optTipoProd'));
        }
        $fatList->groupBy('codicearti');
        if($limitVal!=null) { $fatList->havingRaw('fatN >= ?', [$limitVal]); }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        $title = "Scheda Confronto Anni";
        $subTitle = ($customer) ? $customer->descrizion : "NONE";
        $view = '_exports.pdf.schedaFatArtPdf';
        $data = [
            'customer' => $customer,
            'fatList' => $fatList,
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
            'mese' => $meseRif,
            'onlyMese' => $onlyMese,
            'pariperiodo' => $isPariPeriodo
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title . '-' . $subTitle . '.pdf');
    }

    public function downloadXLS(Request $req, $codicecf = null)
    {
        $codCli = ($req->input('codicecf')) ? $req->input('codicecf') : $codicecf;
        $thisYear = (Carbon::now()->year);
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : null;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);

        $querySelect_qta = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.qta_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.qta_tot';
        $querySelect_qtaN = ($isPariPeriodo ? $querySelect_qta : 'u_statfatt_art.qta_tot');
        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->leftjoin('magart', 'magart.codice', '=', 'u_statfatt_art.codicearti')
            ->leftJoin('maggrp', 'maggrp.codice', '=', 'u_statfatt_art.gruppo')
            ->leftjoin('maggrp as macrogrp', function ($join) {
                $join->on('macrogrp.codice', '=', 'u_statfatt_art.macrogrp')
                    ->whereRaw('LENGTH(macrogrp.codice) = ?', [3]);
            })
            ->select('u_statfatt_art.codicearti')
            ->selectRaw('MAX(magart.descrizion) as descrArt')
            ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
            ->selectRaw('MAX(macrogrp.descrizion) as descrMacrogrp')
            ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
            ->selectRaw('MAX(maggrp.descrizion) as descrGruppo')
            ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
            ->selectRaw('MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qta . ', 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fat . ')/(' . $querySelect_qta . '), 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.codicecf = ?', [$codCli]);
        $fatList->whereRaw('u_statfatt_art.esercizio >= ?', [$thisYear - $yearBack]);
        $fatList->whereRaw('(LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ?)', ['CAMP', 'NOTA', 'BONU']);
        $fatList->whereRaw('(LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,3) != ?)', ['C', '2', 'DIC']);
        if ($req->input('grpPrdSelected')) {
            $fatList->whereIn('u_statfatt_art.gruppo', $req->input('grpPrdSelected'));
        }
        if (!empty($req->input('optTipoProd'))) {
            $fatList->where('u_statfatt_art.prodotto', $req->input('optTipoProd'));
        }
        $fatList->groupBy('codicearti');
        if ($limitVal != null) {
            $fatList->havingRaw('fatN >= ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        return Excel::download(new StatFatArtExport($fatList, $thisYear, $yearBack), 'ConfrontoAnni_'.$codCli.'.xlsx');
    }

    public function downloadPDFTot(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
        $thisYear = (Carbon::now()->year);
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : null;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);

        $querySelect_qta = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.qta_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.qta_tot';
        $querySelect_qtaN = ($isPariPeriodo ? $querySelect_qta : 'u_statfatt_art.qta_tot');
        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->leftjoin('magart', 'magart.codice', '=', 'u_statfatt_art.codicearti')
            ->leftJoin('maggrp', 'maggrp.codice', '=', 'u_statfatt_art.gruppo')
            ->leftjoin('maggrp as macrogrp', function ($join) {
                $join->on('macrogrp.codice', '=', 'u_statfatt_art.macrogrp')
                    ->whereRaw('LENGTH(macrogrp.codice) = ?', [3]);
            })
            ->select('u_statfatt_art.codicearti')
            ->selectRaw('MAX(magart.descrizion) as descrArt')
            ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
            ->selectRaw('MAX(macrogrp.descrizion) as descrMacrogrp')
            ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
            ->selectRaw('MAX(maggrp.descrizion) as descrGruppo')
            ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
            ->selectRaw('MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qta . ', 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fat . ')/(' . $querySelect_qta . '), 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.esercizio >= ?', [$thisYear - $yearBack]);
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
        $fatList->groupBy('codicearti');
        if ($limitVal != null) {
            $fatList->havingRaw('fatN >= ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        $title = "Scheda Confronto Anni";
        $subTitle = "NONE";
        $view = '_exports.pdf.schedaFatArtPdf';
        $data = [
            'customer' => null,
            'fatList' => $fatList,
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
            'mese' => $meseRif,
            'onlyMese' => $onlyMese,
            'pariperiodo' => $isPariPeriodo
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title . '-' . $subTitle . '.pdf');
    }

    public function downloadXLSTot(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
        $thisYear = (Carbon::now()->year);
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : null;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);

        $querySelect_qta = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.qta_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.qta_tot';
        $querySelect_qtaN = ($isPariPeriodo ? $querySelect_qta : 'u_statfatt_art.qta_tot');
        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->leftjoin('magart', 'magart.codice', '=', 'u_statfatt_art.codicearti')
            ->leftJoin('maggrp', 'maggrp.codice', '=', 'u_statfatt_art.gruppo')
            ->leftjoin('maggrp as macrogrp', function ($join) {
                $join->on('macrogrp.codice', '=', 'u_statfatt_art.macrogrp')
                    ->whereRaw('LENGTH(macrogrp.codice) = ?', [3]);
            })
            ->select('u_statfatt_art.codicearti')
            ->selectRaw('MAX(magart.descrizion) as descrArt')
            ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
            ->selectRaw('MAX(macrogrp.descrizion) as descrMacrogrp')
            ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
            ->selectRaw('MAX(maggrp.descrizion) as descrGruppo')
            ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
            ->selectRaw('MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qta . ', 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fat . ')/(' . $querySelect_qta . '), 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.esercizio >= ?', [$thisYear - $yearBack]);
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
        $fatList->groupBy('codicearti');
        if ($limitVal != null) {
            $fatList->havingRaw('fatN >= ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        return Excel::download(new StatFatArtExport($fatList, $thisYear, $yearBack), 'ConfrontoAnni_Tot.xlsx');
    }

    public function downloadPDFListaCli(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
        $thisYear = (Carbon::now()->year);
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : 500;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);
        // dd($req->input('limitVal'));

        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicecf')
            ->selectRaw('MAX(anagrafe.descrizion) as ragionesociale, MAX(settori.descrizion) as settore, MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        // $fatList->whereRaw('anagrafe.agente = ? AND LENGTH(anagrafe.agente) = ?', [$agente, strlen($agente)]);
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
        $fatList->orderBy('fatN', 'desc');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        // dd($fatList->toSql());

        $title = "Scheda Confronto Anni";
        $subTitle = "Tabella Clienti";
        $view = '_exports.pdf.schedaFatArtPdfTot';
        $data = [
            'fatList' => $fatList,
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
            'mese' => $meseRif,
            'onlyMese' => $onlyMese,
            'pariperiodo' => $isPariPeriodo
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title . '-' . $subTitle . '.pdf');
    }

    public function downloadXLSListaCli(Request $req, $codAg = null)
    {
        $agentList = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agentList->first()->codice));
        $thisYear = (Carbon::now()->year);
        $settoreSelected = ($req->input('settoreSelected')) ? $req->input('settoreSelected') : null;
        $zoneSelected = ($req->input('zoneSelected')) ? $req->input('zoneSelected') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = ($req->input('limitVal') || $req->input('limitVal') == '0') ? $req->input('limitVal') : 500;
        $meseSelected = $req->input('mese');
        $onlyMese = $req->input('onlyMese') ? $req->input('onlyMese') : false;
        $isPariPeriodo = $onlyMese ? $onlyMese : ($req->input('pariperiodo') ? $req->input('pariperiodo') : false);
        // dd($req->input('limitVal'));

        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicecf')
            ->selectRaw('MAX(anagrafe.descrizion) as ragionesociale, MAX(settori.descrizion) as settore, MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        // $fatList->whereRaw('anagrafe.agente = ? AND LENGTH(anagrafe.agente) = ?', [$agente, strlen($agente)]);
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
        $fatList->orderBy('fatN', 'desc');
        $fatList = $fatList->get();
        // dd($fatList->toSql());

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        // dd($fatList->toSql());

        return Excel::download(new StatFatArtListCliExport($fatList, $thisYear, $yearBack, $meseRif, $onlyMese, $isPariPeriodo), 'ListaCli_ConfrAnni.xlsx');
    }


    private function buildQueryPeriodo($prefColumn, $mese, $onlyMese)
    {
        $q = '';
        if ($onlyMese) {
            $q = $prefColumn . str_pad(strval($mese), 2, "0", STR_PAD_LEFT);
        } else {
            for ($i = 1; $i <= $mese; $i++) {
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

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
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
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
        if($limitVal!=null) { $fatList->havingRaw('fatN > ?', [$limitVal]); }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');

        // dd($fatList->toSql());

        $title = "Scheda Confronto Anni";
        $subTitle = ($customer) ? $customer->descrizion : "NONE";
        $view = '_exports.pdf.schedaFatArtPdf';
        $data = [
            'customer' => $customer,
            'fatList' => $fatList->get(),
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
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
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
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
            $fatList->havingRaw('fatN > ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');

        return Excel::download(new StatFatArtExport($fatList->get(),$thisYear, $yearBack), 'ConfrontoAnni_'.$codCli.'.xlsx');
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
        // dd($req->input('limitVal'));

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
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
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
            $fatList->havingRaw('fatN > ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');

        // dd($fatList->toSql());

        $title = "Scheda Confronto Anni";
        $subTitle = "NONE";
        $view = '_exports.pdf.schedaFatArtPdf';
        $data = [
            'customer' => null,
            'fatList' => $fatList->get(),
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
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
        // dd($req->input('limitVal'));

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
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN', [$thisYear])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0), 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
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
            $fatList->havingRaw('fatN > ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');

        return Excel::download(new StatFatArtExport($fatList->get(), $thisYear, $yearBack), 'ConfrontoAnni_Tot.xlsx');
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
        // dd($req->input('limitVal'));

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicecf')
            ->selectRaw('MAX(anagrafe.descrizion) as ragionesociale, MAX(settori.descrizion) as settore, MIN(u_statfatt_art.mese_parz) as meseRif')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
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
        $fatList->havingRaw('fatN > ?', [$limitVal]);

        // dd($fatList->toSql());

        $title = "Scheda Confronto Anni";
        $subTitle = "Tabella Clienti";
        $view = '_exports.pdf.schedaFatArtPdfTot';
        $data = [
            'fatList' => $fatList->get(),
            'thisYear' => $thisYear,
            'yearback' => $yearBack,
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title . '-' . $subTitle . '.pdf');
    }
}

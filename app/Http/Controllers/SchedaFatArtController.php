<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\Helpers\RedisUser;
use knet\ArcaModels\Client;
use knet\ArcaModels\Product;

class SchedaFatArtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codicecf = null)
    {
        $codCli = ($req->input('codicecf')) ? $req->input('codicecf') : $codicecf;
        $customer = Client::select('codice', 'descrizion')->where('codice', $codCli)->get();
        $thisYear = (Carbon::now()->year);
        // $settori = ($req->input('settori')) ? $req->input('settori') : null;
        $yearBack = ($req->input('yearback')) ? $req->input('yearback') : 3; // 2->3AnniView; 3->4AnniView; 4->5AnniView

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
            ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
            ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
            ->select('u_statfatt_art.codicearti')
            ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
            ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
            ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN', [$thisYear])
            ->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN', [$thisYear])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN1', [$thisYear - 1])
            ->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN1', [$thisYear - 1])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN2', [$thisYear - 2])
            ->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN2', [$thisYear - 2])
            ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN2', [$thisYear - 2]);

        switch ($yearBack) {
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.qta_tot, 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('AVG(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot/u_statfatt_art.qta_tot, 0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?, u_statfatt_art.val_tot, 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.codicecf = ?', [$codCli]);
        $fatList->groupBy('codicearti');
        $fatList->orderBy('codGruppo')->orderBy('codicearti');

        dd($fatList->get());

        return view('stFatt.idxAg', [
            'agentList' => $agentList,
            'agente' => $agente,
            'descrAg' => $descrAg,
            'thisYear' => $thisYear,
        ]);
    }
}

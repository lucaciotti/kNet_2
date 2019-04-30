<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\StatABC;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;

use knet\Helpers\PdfReport;

class SchedaAbcController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codAg){
        $agente = Agent::select('codice', 'descrizion')->where('codice', $codAg)->where(DB::raw('LENGTH(codice)'), strlen($codAg))->orderBy('codice')->first();
        
        $thisYear =  Carbon::now()->year;
        $prevYear = $thisYear-1;
        $thisMonth = Carbon::now()->month;
        // (Legenda PY -> Previous Year ; TY -> This Year)
        $AbcProds = StatABC::select('articolo', 'codag',
                        DB::raw('MAX(prodotto) as prodotto'),
                        DB::raw('MAX(gruppo) as gruppo'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta, 0)) as qta_TY'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta, 0)) as qta_PY'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta1, 0)) as qta_TY_1'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta2, 0)) as qta_TY_2'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta3, 0)) as qta_TY_3'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta4, 0)) as qta_TY_4'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta5, 0)) as qta_TY_5'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta6, 0)) as qta_TY_6'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta7, 0)) as qta_TY_7'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta8, 0)) as qta_TY_8'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta9, 0)) as qta_TY_9'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta10, 0)) as qta_TY_10'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta11, 0)) as qta_TY_11'),
                        DB::raw('SUM(IF(esercizio='.$thisYear.', qta12, 0)) as qta_TY_12'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta1, 0)) as qta_PY_1'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta2, 0)) as qta_PY_2'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta3, 0)) as qta_PY_3'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta4, 0)) as qta_PY_4'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta5, 0)) as qta_PY_5'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta6, 0)) as qta_PY_6'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta7, 0)) as qta_PY_7'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta8, 0)) as qta_PY_8'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta9, 0)) as qta_PY_9'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta10, 0)) as qta_PY_10'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta11, 0)) as qta_PY_11'),
                        DB::raw('SUM(IF(esercizio='.$prevYear.', qta12, 0)) as qta_PY_12')
                        )
                        ->where('codag', $codAg)
                        ->where('isomaggio', false)
                        ->whereIn('esercizio', [''.$thisYear.'', ''.$prevYear.'']);

        $AbcProds = $AbcProds->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET']);

        $AbcProds = $AbcProds->groupBy(['articolo', 'codag'])
                    ->with([
                    'agent' => function($query){
                        $query->select('codice', 'descrizion');
                    }, 
                    'grpProd' => function($query){
                        $query->select('codice', 'descrizion');
                    }, 
                    'product' => function($query){
                        $query->select('codice', 'descrizion', 'unmisura');
                    }
                    ])
                    ->orderBy('qta_TY', 'DESC')
                    ->get();

        $title = "Scheda Abc Articolo Agente";
        $subTitle = ($agente) ? $agente->descrizion : "NONE";
        $view = '_exports.pdf.schedaAbcPdf';
        $data = [
            'agente' => $agente,
            'AbcProds' => $AbcProds,
            'thisYear' => $thisYear,
            'prevYear' => $prevYear,
            'thisMonth' => $thisMonth,
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

}

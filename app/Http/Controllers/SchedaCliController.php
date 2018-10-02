<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
// use PDF;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\Client;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;
use knet\ArcaModels\StatFatt;
use knet\ArcaModels\StatABC;

use knet\Helpers\PdfReport;

class SchedaCliController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codice){
        $client = Client::with(['agent', 'detNation', 'detZona', 'detSect', 'clasCli', 'detPag', 'detStato'])->findOrFail($codice);
        $scadToPay = ScadCli::where('codcf', $codice)->where('pagato',0)->whereIn('tipoacc', ['F', ''])->orderBy('datascad','desc')->get();
        $visits = wVisit::where('codicecf', $codice)->with('user')->take(3)->orderBy('data', 'desc')->orderBy('id')->get();
        $thisYear = (string)(Carbon::now()->year);
        $prevYear = (string)((Carbon::now()->year)-1);
        $thisMonth = Carbon::now()->month;
                        //   ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA'])
        $fatThisYear = StatFatt::select('codicecf', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', $codice)
                          ->where('tipologia', 'FATTURATO')
                          ->where('esercizio', $thisYear)
                          ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                          ->groupBy(['codicecf', 'tipologia'])
                          ->get();
        $fatPrevYear = StatFatt::select('codicecf', 'tipologia',
                                  DB::raw('ROUND(SUM(valore1),2) as valore1'),
                                  DB::raw('ROUND(SUM(valore2),2) as valore2'),
                                  DB::raw('ROUND(SUM(valore3),2) as valore3'),
                                  DB::raw('ROUND(SUM(valore4),2) as valore4'),
                                  DB::raw('ROUND(SUM(valore5),2) as valore5'),
                                  DB::raw('ROUND(SUM(valore6),2) as valore6'),
                                  DB::raw('ROUND(SUM(valore7),2) as valore7'),
                                  DB::raw('ROUND(SUM(valore8),2) as valore8'),
                                  DB::raw('ROUND(SUM(valore9),2) as valore9'),
                                  DB::raw('ROUND(SUM(valore10),2) as valore10'),
                                  DB::raw('ROUND(SUM(valore11),2) as valore11'),
                                  DB::raw('ROUND(SUM(valore12),2) as valore12'),
                                  DB::raw('ROUND(SUM(fattmese),2) as fattmese')
                                )
                          ->where('codicecf', $codice)
                          ->where('tipologia', 'FATTURATO')
                          ->where('esercizio', $prevYear)
                          ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                          ->groupBy(['codicecf', 'tipologia'])
                          ->get();

        $AbcItems = StatABC::select('articolo', 'codicecf',
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
                      ->where('codicecf', $codice)
                      ->where('isomaggio', false)
                      ->whereIn('esercizio', [$thisYear, $prevYear])
                      ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBICA', 'PLANET'])
                      ->groupBy(['articolo', 'codicecf'])
                      ->with([
                          'grpProd' => function($query){
                          $query->select('codice', 'descrizion');
                        }, 'product' => function($query){
                          $query->select('codice', 'descrizion', 'unmisura');
                        }
                        ])
                      ->orderBy('qta_TY', 'DESC')
                      ->get();

        $prevMonth = (Carbon::now()->month);
        $valMese = 'valore' . $prevMonth;
        $prevMonth = $fatThisYear->isEmpty() ? $prevMonth : (($fatThisYear->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth);
        
        $visits = wVisit::where('codicecf', $codice)->with('user')->take(3)->orderBy('data', 'desc')->orderBy('id')->get();

        $title = "Scheda Cliente";
        $subTitle = $client->descrizion;
        $view = '_exports.pdf.schedaCliPdf';
        $data = [
            'client' => $client,
            'scads' => $scadToPay,
            'visits' => $visits,
            'fatThisYear' => $fatThisYear,
            'fatPrevYear' => $fatPrevYear,
            'AbcItems' => $AbcItems,
            'thisYear' => $thisYear,
            'prevYear' =>$prevYear,
            'thisMonth' => $thisMonth,
            'visits' => $visits,
            'dateNow' => Carbon::now(),
        ];
        $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);

        return $pdf->stream($title.'-'.$subTitle.'.pdf');

    }

}

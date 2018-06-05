<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\Client;
use knet\ArcaModels\Nazione;
use knet\ArcaModels\Settore;
use knet\ArcaModels\Zona;
use knet\ArcaModels\ScadCli;
use knet\WebModels\wVisit;
use knet\ArcaModels\StatFatt;
use knet\ArcaModels\StatABC;

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
                          ->groupBy(['codicecf', 'tipologia'])
                          ->get();

        $AbcItems = StatABC::select('articolo', 'codag',
                                  DB::raw('MAX(prodotto) as prodotto'),
                                  DB::raw('MAX(gruppo) as gruppo'),
                                  DB::raw('SUM(IF(esercizio='.$thisYear.', qta, 0)) as qtaN'),
                                  DB::raw('SUM(IF(esercizio='.$prevYear.', qta, 0)) as qtaN1')
                                )
                          ->where('codicecf', $codice)
                          ->where('isomaggio', false)
                          ->whereIn('esercizio', [$thisYear, $prevYear])
                          ->groupBy(['articolo', 'codag'])
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'grpProd' => function($query){
                              $query->select('codice', 'descrizion');
                            }, 'product' => function($query){
                              $query->select('codice', 'descrizion', 'unmisura');
                            }
                            ])
                          ->orderBy('qtaN', 'DESC')
                          ->get();

        $pdf = PDF::loadView('_exports.pdf.schedaCliPdf', [
            'client' => $client,
            'scads' => $scadToPay,
            'visits' => $visits,
            'fatThisYear' => $fatThisYear,
            'fatPrevYear' => $fatPrevYear,
            'AbcItems' => $AbcItems

        ])
        ->setOption('header-html', view('_exports.pdf.masterPage.headerPdf', ['pageTitle' => "Scheda Cliente", 'pageSubTitle' => $client->descrizion]))
        ->setOption('footer-html', view('_exports.pdf.masterPage.footerPdf'))
        ->setPaper('a4');
        return $pdf->stream('test.pdf');
    }
}

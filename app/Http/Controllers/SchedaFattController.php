<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\StatFatt;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;

use knet\Helpers\PdfReport;

class SchedaFattController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codAg){
        $agente = Agent::select('codice', 'descrizion')->where('codice', $codAg)->where(DB::raw('LENGTH(codice)'), strlen($codAg))->orderBy('codice')->first();
        
        $thisYear = (string)(Carbon::now()->year);
        $prevYear = (string)((Carbon::now()->year)-1);

        // (Legenda PY -> Previous Year ; TY -> This Year)
        $fat_TY = StatFatt::select('agente', 'tipologia',
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
                            ->where('codicecf', 'CTOT')
                            ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                            ->where('esercizio', $thisYear)
                            ->where('tipologia', 'FATTURATO')
                            ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                            ->groupBy(['agente', 'tipologia'])
                            ->with([
                                'agent' => function($query){
                                $query->select('codice', 'descrizion');
                                }
                                ])
                            ->get();
        
        $fat_PY = StatFatt::select('agente', 'tipologia',
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
                            ->where('codicecf', 'CTOT')
                            ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                            ->where('esercizio', $prevYear)
                            ->where('tipologia', 'FATTURATO')
                            ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                            ->groupBy(['agente', 'tipologia'])
                            ->with([
                                'agent' => function($query){
                                $query->select('codice', 'descrizion');
                                }
                                ])
                            ->get();
        $prevMonth = (Carbon::now()->month);
        $valMese = 'valore' . $prevMonth;
        if(!empty($fat_TY->first())){
            $prevMonth = ($fat_TY->first()->$valMese == 0) ? $prevMonth-1 : $prevMonth;
        }

        //FATTURATO PER ZONA 
        // (Sub-Legenda KR = KRONA, KO = KOBLENZ, KU = KUBICA, AT = ATOMIKA, PL = PLANET)
        $fatZone = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', "!=", 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                    ->groupBy(['codicecf'])
                    ->with([ 
                        'client' => function($query){
                            $query->select('codice', 'descrizion', 'settore', 'zona')
                            ->withoutGlobalScope('agent')
                            ->withoutGlobalScope('superAgent')
                            ->withoutGlobalScope('client')
                            ->with(['detZona', 'detSect']);
                            }
                        ])
                    ->get();
        $fatZone = $fatZone->groupBy('client.zona');
        
        $fatTot = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KRONA', 'KOBLENZ', 'KUBIKA', 'PLANET'])
                    ->groupBy(['codicecf'])->get();

        $title = "Scheda Fatturato Agente";
        $subTitle = ($agente) ? $agente->descrizion : "NONE";
        $view = '_exports.pdf.schedaFatPdf';
        $data = [
            'agente' => $agente,
            'fat_TY' => $fat_TY,
            'fat_PY' => $fat_PY,
            'fatZone' => $fatZone,
            'fatTot' => $fatTot->first(),
            'prevMonth' => $prevMonth,
            'descrAg' => $subTitle,
            'thisYear' => $thisYear,
            'prevYear' => $prevYear
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

    public function downloadZonePDF(Request $req, $codAg){
        $agente = Agent::select('codice', 'descrizion')->where('codice', $codAg)->where(DB::raw('LENGTH(codice)'), strlen($codAg))->orderBy('codice')->first();
        
        $thisYear = (string)(Carbon::now()->year);
        $prevYear = (string)((Carbon::now()->year)-1);

        
        //FATTURATO PER ZONA 
        // (Sub-Legenda KR = KRONA, KO = KOBLENZ, KU = KUBICA, AT = ATOMIKA, PL = PLANET)
        $fatZone_KR = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', "!=", 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KRONA'])
                    ->groupBy(['codicecf'])
                    ->with([ 
                        'client' => function($query){
                            $query->select('codice', 'descrizion', 'settore', 'zona')
                            ->withoutGlobalScope('agent')
                            ->withoutGlobalScope('superAgent')
                            ->withoutGlobalScope('client')
                            ->with(['detZona', 'detSect']);
                            }
                        ])
                    ->get();
        $fatZone_KR = $fatZone_KR->groupBy('client.zona');
        
        $fatTot_KR = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KRONA'])
                    ->groupBy(['codicecf'])->get();
        
        //KOBLENZ
        $fatZone_KO = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', "!=", 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KOBLENZ'])
                    ->groupBy(['codicecf'])
                    ->with([ 
                        'client' => function($query){
                            $query->select('codice', 'descrizion', 'settore', 'zona')
                            ->withoutGlobalScope('agent')
                            ->withoutGlobalScope('superAgent')
                            ->withoutGlobalScope('client')
                            ->with(['detZona', 'detSect']);
                            }
                        ])
                    ->get();
        $fatZone_KO = $fatZone_KO->groupBy('client.zona');
        
        $fatTot_KO = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KOBLENZ'])
                    ->groupBy(['codicecf'])->get();
        
        //KUBICA
        $fatZone_KU = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', "!=", 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KUBIKA'])
                    ->groupBy(['codicecf'])
                    ->with([ 
                        'client' => function($query){
                            $query->select('codice', 'descrizion', 'settore', 'zona')
                            ->withoutGlobalScope('agent')
                            ->withoutGlobalScope('superAgent')
                            ->withoutGlobalScope('client')
                            ->with(['detZona', 'detSect']);
                            }
                        ])
                    ->get();
        $fatZone_KU = $fatZone_KU->groupBy('client.zona');
        
        $fatTot_KU = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['KUBIKA'])
                    ->groupBy(['codicecf'])->get();

        // PLANET        
        $fatZone_PL = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', "!=", 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['PLANET'])
                    ->groupBy(['codicecf'])
                    ->with([ 
                        'client' => function($query){
                            $query->select('codice', 'descrizion', 'settore', 'zona')
                            ->withoutGlobalScope('agent')
                            ->withoutGlobalScope('superAgent')
                            ->withoutGlobalScope('client')
                            ->with(['detZona', 'detSect']);
                            }
                        ])
                    ->get();
        $fatZone_PL = $fatZone_PL->groupBy('client.zona');
        
        $fatTot_PL = StatFatt::select('codicecf',
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore1,2), 0)) as val_TY_1'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore2,2), 0)) as val_TY_2'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore3,2), 0)) as val_TY_3'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore4,2), 0)) as val_TY_4'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore5,2), 0)) as val_TY_5'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore6,2), 0)) as val_TY_6'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore7,2), 0)) as val_TY_7'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore8,2), 0)) as val_TY_8'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore9,2), 0)) as val_TY_9'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore10,2), 0)) as val_TY_10'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore11,2), 0)) as val_TY_11'),
                    DB::raw('SUM(IF(esercizio="'.$thisYear.'", ROUND(valore12,2), 0)) as val_TY_12'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore1,2), 0)) as val_PY_1'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore2,2), 0)) as val_PY_2'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore3,2), 0)) as val_PY_3'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore4,2), 0)) as val_PY_4'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore5,2), 0)) as val_PY_5'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore6,2), 0)) as val_PY_6'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore7,2), 0)) as val_PY_7'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore8,2), 0)) as val_PY_8'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore9,2), 0)) as val_PY_9'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore10,2), 0)) as val_PY_10'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore11,2), 0)) as val_PY_11'),
                    DB::raw('SUM(IF(esercizio="'.$prevYear.'", ROUND(valore12,2), 0)) as val_PY_12')               
                    )
                    ->where('agente', $codAg)->where(DB::raw('LENGTH(agente)'), strlen($codAg))
                    ->whereIn('esercizio', [$thisYear, $prevYear])
                    ->where('codicecf', 'CTOT')
                    ->where('tipologia', 'FATTURATO')
                    ->whereIn('prodotto', ['PLANET'])
                    ->groupBy(['codicecf'])->get();

        $title = "Scheda Fatturato Agente";
        $subTitle = ($agente) ? $agente->descrizion : "NONE";
        $view = '_exports.pdf.schedaFatZonePdf';
        $data = [
            'agente' => $agente,
            'fatZone_KR' => $fatZone_KR,
            'fatTot_KR' => $fatTot_KR->first(),
            'fatZone_KO' => $fatZone_KO,
            'fatTot_KO' => $fatTot_KO->first(),
            'fatZone_KU' => $fatZone_KU,
            'fatTot_KU' => $fatTot_KU->first(),
            'fatZone_PL' => $fatZone_PL,
            'fatTot_PL' => $fatTot_PL->first(),
            'descrAg' => $subTitle,
            'thisYear' => $thisYear,
            'prevYear' => $prevYear
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

        return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }
}

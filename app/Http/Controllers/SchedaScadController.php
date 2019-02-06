<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\ScadCli;

use knet\Helpers\PdfReport;

class SchedaScadController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function downloadProvPDF(Request $req, $codAg, $year){
      //Let's Set the Date
      $thisYear = Carbon::now()->year;
      $startDate = Carbon::createFromDate($year, 1, 1);
      if($year==$thisYear){
        $endDateFT = new Carbon('last day of last month');
      } else {
        $endDateFT = Carbon::createFromDate($year, 12, 31);
      }
      $endDateScad = new Carbon('last day of last month');

      $agente = Agent::select('codice', 'descrizion')->where('codice', $codAg)->where(DB::raw('LENGTH(codice)'), strlen($codAg))->orderBy('codice')->first();

      $provv_TY = ScadCli::select('id', 'id_doc', 'numfatt', 
                'datafatt', 'datascad', 'codcf', 'tipomod', 
                'tipo', 'insoluto', 'u_insoluto', 'pagato', 
                'impeffval', 'importopag', 'idragg', 'tipoacc',
                'impprovlit', 'impprovliq', 'liquidate', DB::raw('MONTH(datafatt) as Mese')
              )
              ->whereBetween('datafatt', array($startDate, $endDateFT))
              ->where(function ($q) use ($startDate, $endDateScad) {
                $q->whereBetween('datapag', array($startDate, $endDateScad))
                ->orWhere(function ($query) use ($startDate, $endDateScad) {
                  $query->whereBetween('datascad', array($startDate, $endDateScad))->where('pagato', false);
                });
              })
              ->where('codag', $codAg)->where(DB::raw('LENGTH(codag)'), strlen($codAg))
              ->whereIn('tipoacc', ['F', ''])
              ->with(array('client' => function($query) {
                $query->select('codice', 'descrizion')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
              }))
              ->orderBy('datafatt', 'asc')->orderBy('datascad', 'asc')->orderBy('id', 'desc')
              ->get();
      $provv_TY = $provv_TY->groupBy('Mese');
      // dd($provv_TY);
              // ->whereRaw("`pagato` = 1")
              // ->where('datapag', '<=', $endDate)

      $title = "Scheda Provvigioni Agente - ".(string)$year;
      $subTitle = $agente->descrizion;
      $view = '_exports.pdf.schedaProvPdf';
      $data = [
          'agente' => $agente,
          'descrAg' => $subTitle,
          'thisYear' => $thisYear,
          'provv_TY' => $provv_TY
      ];
      $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

      return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }

    public function downloadScadPDF(Request $req, $codAg=null){
      $thisYear = Carbon::now()->year;
      if($codAg){
        $agente = Agent::select('codice', 'descrizion')->where('codice', $codAg)->where(DB::raw('LENGTH(codice)'), strlen($codAg))->orderBy('codice')->first();
      }

      $startDate = Carbon::createFromDate($thisYear, 1, 1);
      $endDate = Carbon::now();

      $scads_TY = ScadCli::select('id', 'id_doc', 'numfatt', 
                'datafatt', 'datascad', 'codcf', 'tipomod', 
                'tipo', 'insoluto', 'u_insoluto', 'pagato', 
                'impeffval', 'importopag', 'idragg', 'tipoacc',
                'impprovlit', 'impprovliq', 'liquidate', DB::raw('MONTH(datascad) as Mese')
              )
              ->whereBetween('datascad', array($startDate, $endDate));
      if($codAg){
        $scads_TY->where('codag', $codAg)->where(DB::raw('LENGTH(codag)'), strlen($codAg));
      }
      $scads_TY = $scads_TY->whereIn('tipoacc', ['M', ''])
              ->whereRaw("`pagato` = 0")
              ->with(array('client' => function($query) {
                $query->select('codice', 'descrizion')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
              }))
              ->orderBy('datascad', 'asc')->orderBy('id', 'desc')
              ->get();
      $scads_TY = $scads_TY->groupBy('Mese');
      // dd($provv_TY);
      
      //ANNO PRECEDENTE
      $startDate = Carbon::createFromDate($thisYear-1, 1, 1);
      $endDate = Carbon::createFromDate($thisYear-1, 12, 31);

      $scads_PY = ScadCli::select('id', 'id_doc', 'numfatt', 
                'datafatt', 'datascad', 'codcf', 'tipomod', 
                'tipo', 'insoluto', 'u_insoluto', 'pagato', 
                'impeffval', 'importopag', 'idragg', 'tipoacc',
                'impprovlit', 'impprovliq', 'liquidate', DB::raw('MONTH(datascad) as Mese')
              )
              ->whereBetween('datascad', array($startDate, $endDate));
      if($codAg){
        $scads_PY->where('codag', $codAg)->where(DB::raw('LENGTH(codag)'), strlen($codAg));
      }
      $scads_PY = $scads_PY->whereIn('tipoacc', ['M', ''])
              ->whereRaw("`pagato` = 0")
              ->with(array('client' => function($query) {
                $query->select('codice', 'descrizion')
                ->withoutGlobalScope('agent')
                ->withoutGlobalScope('superAgent')
                ->withoutGlobalScope('client');
              }))
              ->orderBy('datascad', 'asc')->orderBy('id', 'desc')
              ->get();
      $scads_PY = $scads_PY->groupBy('Mese');

      $title = "Scheda Scadenze";
      if($codAg){
        $subTitle = $agente->descrizion;
      } else {
        $subTitle = "Generale";
      }
      $view = '_exports.pdf.schedaScadPdf';
      $data = [
          'descrAg' => $subTitle,
          'thisYear' => $thisYear,
          'scads_TY' => $scads_TY,
          'scads_PY' => $scads_PY
      ];
      $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

      return $pdf->stream($title.'-'.$subTitle.'.pdf');
    }
}

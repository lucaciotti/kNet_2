<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;

use knet\ExportsXLS\DocHeadExport;
use knet\ExportsXLS\DocRowsExport;

use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow;

class DocExport implements WithMultipleSheets
{
    use Exportable;

    // public $id = 0;

    public function __construct($id) {
        $this->id = $id;
    }

    public function sheets(): array
    {
        $tipoDoc = DocCli::select('tipomodulo')->findOrFail($this->id);
        $head = DocCli::select(DB::raw('concat(tipodoc, " ", numerodoc) as doc'), 'datadoc', 'esercizio',
                                'codicecf', 'numrighepr', 'valuta', 'sconti', 'scontocass',
                                'cambio', 'numerodocf', 'datadocfor', 'tipomodulo',
                                'pesolordo', 'pesonetto', 'volume', 'v1data', 'v1ora', 'vettore1', 'destdiv', 'aspbeni',
                                'colli', DB::raw('speseim+spesetr as spesetras'), 'totmerce',
                                'totsconto', 'totimp', 'totiva', 'totdoc');
        if ($tipoDoc->tipomodulo=='B') {
            $head = $head->with('vettore', 'detBeni');
        }
        $head = $head->findOrFail($this->id);
        if ($tipoDoc->tipomodulo == 'B'){
        $destDiv = Destinaz::where('codicecf', $head->codicecf)->where('codicedes', $head->destdiv)->first();
        } else {
        $destDiv = null;
        }

        $rows = DocRow::select('numeroriga', 'codicearti', 'descrizion', 'unmisura', 'fatt',
                                'quantita', 'quantitare', 'sconti', 'prezzoun', 'prezzotot', 'aliiva',
                                'ommerce', 'lotto', 'matricola', 'dataconseg', 'u_dtpronto');
        $rows = $rows->where('id_testa', $this->id)->orderBy('numeroriga', 'asc')->get();

        //Stampo sui Fogli
        $sheets = [];

        $sheets[] = new DocHeadExport($head, $destDiv);
        $sheets[] = new DocRowsExport($head, $rows);

        return $sheets;
    }
    
}
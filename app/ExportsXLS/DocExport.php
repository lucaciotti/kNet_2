<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow;

class DocExport implements FromView, WithHeadings
{
    use Exportable;

    // public $id = 0;

    public function __construct($id) {
        $this->id = $id;
    }

    public function view(): View
    {
        $tipoDoc = DocCli::select('tipomodulo')->findOrFail($this->id);
        $head = DocCli::select(DB::raw('concat(tipodoc, " ", numerodoc) as doc'), 'datadoc', 'esercizio',
                                'codicecf', 'numrighepr', 'valuta', 'sconti', 'scontocass',
                                'cambio', 'numerodocf', 'datadocfor', 'tipomodulo',
                                'pesolordo', 'pesonetto', 'volume', 'v1data', 'v1ora',
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

        return view('_exports.xls.doc', [
            'head' => $head,
            'rows' => $rows
        ]);
    }

    public function headings(): array
    {
        /* return [
            '#',
            'Date',
        ]; */
    }

}
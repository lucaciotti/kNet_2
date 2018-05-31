<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class DocHeadExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $head;
    protected $destDiv;

    public function __construct($head, $destDiv) {
        $this->head = $head;
        $this->destDiv = $destDiv;
    }

    public function view(): View
    {
        return view('_exports.xls.docHead', [
            'head' => $this->head,
            'destDiv' => $this->destDiv
        ]);
    }

    public function title(): string
    {
        return 'Head';
    }

    
}
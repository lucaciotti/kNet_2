<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class DocRowsExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $head;
    protected $rows;

    public function __construct($head, $rows) {
        $this->head = $head;
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('_exports.xls.docRows', [
            'head' => $this->head,
            'rows' => $this->rows
        ]);
    }

    public function title(): string
    {
        return 'Rows';
    }

    
}
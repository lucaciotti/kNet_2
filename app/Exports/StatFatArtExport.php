<?php

namespace knet\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StatFatArtExport implements FromView, ShouldAutoSize
{
    protected $fatList;
    protected $thisYear;
    protected $yearback;

    public function __construct($fatList, $thisYear, $yearback)
    {
        $this->fatList = $fatList;
        $this->thisYear = $thisYear;
        $this->yearback = $yearback;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return $this->fatList;
    }

    public function view(): View
    {
        return view('_exports.xls.schedaFatArt.tblDetail', [
            'fatList' => $this->fatList,
            'thisYear' => $this->thisYear,
            'yearBack' => $this->yearback,
            ]
        );
    }

}

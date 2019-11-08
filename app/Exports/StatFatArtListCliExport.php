<?php

namespace knet\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StatFatArtListCliExport implements FromView, ShouldAutoSize
{
    protected $fatList;
    protected $thisYear;
    protected $yearback;
    protected $meseRif;
    protected $onlyMese;
    protected $isPariPeriodo;

    public function __construct($fatList, $thisYear, $yearback, $meseRif, $onlyMese, $isPariPeriodo)
    {
        $this->fatList = $fatList;
        $this->thisYear = $thisYear;
        $this->yearback = $yearback;
        $this->meseRif = $meseRif;
        $this->onlyMese = $onlyMese;
        $this->isPariPeriodo = $isPariPeriodo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function view(): View
    {
        return view(
            '_exports.xls.schedaFatArt.tblCustomers',
            [
                'fatList' => $this->fatList,
                'thisYear' => $this->thisYear,
                'yearBack' => $this->yearback,
                'mese' => $this->meseRif,
                'onlyMese' => $this->onlyMese,
                'pariperiodo' => $this->isPariPeriodo,
            ]
        );
    }
}

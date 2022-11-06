<?php

namespace knet\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StatFatArtExport implements FromView, WithColumnFormatting, ShouldAutoSize
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

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00,
	    'E' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'G' => NumberFormat::FORMAT_NUMBER_00,
	    'H' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'I' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'J' => NumberFormat::FORMAT_NUMBER_00,
	    'K' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'M' => NumberFormat::FORMAT_NUMBER_00,
	    'N' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'O' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }

}

<?php

namespace knet\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EnasarcoExport implements FromView, ShouldAutoSize
{
    protected $ritana;
    protected $ritena;
    protected $thisYear;
    protected $ritmov;
    protected $user;

    public function __construct($ritana, $ritena, $year, $ritmov, $user)
    {
        $this->ritana = $ritana;
        $this->ritena = $ritena;
        $this->thisYear = $year;
        $this->ritmov = $ritmov;
        $this->user = $user;
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
        return view('_exports.xls.enasarco', [
            'ritana' => $this->ritana,
            'ritena' => $this->ritena,
            'year' => $this->thisYear,
            'ritmov' => $this->ritmov,
            'user' => $this->user
            ]
        );
    }

}

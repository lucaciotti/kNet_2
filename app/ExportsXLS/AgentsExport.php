<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Concerns\FromCollection;
use knet\ArcaModels\Agent;

class AgentsExport implements FromCollection
{
    public function collection()
    {
        return Agent::all();
    }
}

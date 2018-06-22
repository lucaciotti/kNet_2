<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;

/* 
use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow; 
implements WithMapping, ToCollection*/

class LEADImport 
{
    use Importable;

    // public $id = 0;

    public function __construct() {
        /*  */
    }
    
}
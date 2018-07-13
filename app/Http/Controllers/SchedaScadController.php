<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use knet\ArcaModels\Client;
use knet\ArcaModels\ScadCli;

use knet\Helpers\PdfReport;

class SchedaScadController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function downloadPDF(Request $req, $codice){
    }
}

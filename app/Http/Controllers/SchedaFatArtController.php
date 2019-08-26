<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;

class SchedaFatArtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
}

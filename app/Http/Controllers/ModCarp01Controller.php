<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use knet\WebModels\wModCarp01;
use knet\WebModels\wSysMkt;
use knet\WebModels\wRubrica;

class ModCarp01Controller extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function createModule(Request $req, $rubri_id){
        return view('modCarp01.create', [
            'contact' => wRubrica::find($rubri_id)->first(),
            'sysMkt' => wSysMkt::all(),
        ]);
    }
    
    public function store(Request $req){
        dd($req);
        return ;
    }

}

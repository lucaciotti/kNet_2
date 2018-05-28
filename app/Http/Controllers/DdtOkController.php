<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use knet\Http\Requests;
use knet\WebModels\wDdtOk;
use Auth;

class DdtOkController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function store(Request $req, $id){
      $ddt = wDdtOk::create([
        'firma'  =>$req->input('firma'),
        'note' => $req->input('note'),
        'id_testa' => $id,
        'user_id' => Auth::user()->id,
      ]);
      $ddt->save();

      return Redirect::route('doc::detail', $id);
    }
}

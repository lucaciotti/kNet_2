<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use knet\ExportsXLS\LEADImport;

class RubriController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function showImport(Request $req){
      return view('rubri.import');
    }

    public function doImport(Request $req){
      $destinationPath = storage_path('app')."/upload/LEAD/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = time() . '_file.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName);
      if(!((new LEADImport($fileName, $req->input('country'), $req->input('mese')))->getResult())){  
        Session::flash('fail', 'Import failed');
      } else {
        Session::flash('success', 'Import successfull');
      }
      return Redirect::back();
    }
}

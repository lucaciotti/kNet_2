<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use knet\Jobs\ImportLEADExcel;
use Excel;
use Session;
use knet\ExportsXLS\LEADImport;

use knet\WebModels\wRubrica;

class RubriController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function showImport(Request $req){
      return view('rubri.import');
    }

    public function doImport(Request $req){
      $destinationPath = public_path()."/upload/LEAD/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = time() . '_file.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName);
      dd((new LEADImport())->import($destinationPath.$fileName));
      /* $destinationPath = public_path()."/upload/LEAD/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = time() . '_file.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
      // sending back with message
      Session::flash('success', 'Upload successfully');
      ImportLEADExcel::dispatch($fileName, 'IT', 1);
      return Redirect::back(); */
    }
}

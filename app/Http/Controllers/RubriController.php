<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use knet\Jobs\ImportUsersExcel;
use Excel;
use Session;

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
      $destinationPath = 'usersFiles'; // upload path
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = rand(11111,99999).'.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
      // sending back with message
      Session::flash('success', 'Upload successfully');
      $this->dispatch(new ImportUsersExcel($fileName));
      return Redirect::back();
    }
}

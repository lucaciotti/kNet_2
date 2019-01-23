<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use knet\WebModels\wSysMkt;

class SysMktController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index() {
        return view('sysMkt.index', [
            'sysMkt' => wSysMkt::orderBy('codice')->get(),
        ]);
    }

    public function store(){
        $this->validate(request(), [
            'codice' => 'required|unique:kNet_it.w_system_mkt|max:6',
            'descrizione' => 'required|max:100',
            'url' => 'nullable|max:255'
        ]);
        wSysMkt::create([
            'codice' => request('codice'),
            'descrizione' => request('descrizione'),
            'url' => request('url')
        ]);
        return ['message' => 'System Mkt creato!'];
    }

    public function destroy(Request $req, $codice){
      wSysMkt::where('codice', $codice)->delete();
      return Redirect::route('sysMkt::sysMkt.index');
    }
}

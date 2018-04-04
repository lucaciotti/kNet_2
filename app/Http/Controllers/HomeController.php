<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace knet\Http\Controllers;

use knet\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;

use knet\ArcaModels\DocCli;
use knet\ArcaModels\ScadCli;
use knet\ArcaModels\Product;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        //dd(session('user.ditta_DB'));
        $dt = Carbon::now();
        $lastMonth = new Carbon('first day of last month');
        $ordini = DocCli::where('tipomodulo', 'O')->where('numrighepr', '>', 0)->count();
        $bolle = DocCli::where('tipomodulo', 'B')->where('datadoc', '>=', $lastMonth)->doesntHave('wDdtOk')->count();
        $scadenze = ScadCli::where('datascad', '<', $dt)
                      ->whereRaw("(`insoluto` = 1 OR `u_insoluto` = 1) AND `pagato` = 0")
                      ->whereIn('tipoacc', ['M', ''])
                      ->whereHas('client', function($query){
                        $query->whereNotIn('statocf', ['C', 'S', 'L'])
                              ->withoutGlobalScope('agent')
                              ->withoutGlobalScope('superAgent')
                              ->withoutGlobalScope('client');
                            })
                      ->count();

        $articoli = Product::whereIn('statoart', ['1','8'])
                      ->where('classe', 'NOT LIKE', 'DIC%')
                      ->where('gruppo', 'NOT LIKE', 'DIC%')
                      ->where('gruppo', 'NOT LIKE', '1%')
                      ->where('gruppo', 'NOT LIKE', 'C%')
                      ->where('gruppo', 'NOT LIKE', '2%')
                      ->where('u_compl', '=', 1)
                      ->where('codice', 'NOT LIKE', 'TKK%')
                      ->where('codice', 'NOT LIKE', '#%')
                      ->where('u_perscli', 0)
                      ->where('gruppo', '!=','')
                      ->where('u_datacrea', '>', Carbon::create($dt->year, 1, 1, 0));
        $articoli = $articoli->count();

        return view('home', [
          'nOrdini' => $ordini,
          'nBolle' => $bolle,
          'nScadenze' => $scadenze,
          'nArticoli' => $articoli,
        ]);
    }
}

<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use knet\Http\Requests;
use knet\ArcaModels\Product;
use knet\ArcaModels\SubGrpProd;

use Auth;
use knet\User;

class ProductController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

  public function index (Request $req){

    return redirect()->action('HomeController@index');

    $products = Product::select('codice', 'descrizion', 'unmisura', 'gruppo', 'classe', 'listino1', 'listino6', 'u_perscli')
                  ->whereIn('statoart', ['1','8'])
                  ->where('classe', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', '1%')
                  ->where('gruppo', 'NOT LIKE', '2%')
                  ->where('u_compl', '=', 1)
                  ->where('codice', 'NOT LIKE', 'TKK%')
                  ->where('codice', 'NOT LIKE', '#%')
                  ->where('codice', 'NOT LIKE', 'CAMP%')
                  ->where('u_perscli', 0)
                  ->where('gruppo', '!=','')
                  ->orderBy('gruppo')->orderBy('codice');
    $products = $products->with('grpProd')->get();

    $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();
    // $settori = Settore::all();
    // $zone = Zona::all();

    // dd($gruppi);
    return view('prods.index', [
      'products' => $products,
      'gruppi' => $gruppi,
    ]);
  }

  public function fltIndex (Request $req)
  {

    return redirect()->action('HomeController@index');
    
    // dd($req);
    $products = Product::select('codice', 'descrizion', 'unmisura', 'gruppo', 'classe', 'listino1', 'listino6', 'u_perscli')
                  ->whereIn('statoart', ['1','8'])
                  ->where('classe', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', '1%')
                  ->where('gruppo', '!=','')
                  ->where('codice', 'NOT LIKE', 'TKK%')
                  ->where('codice', 'NOT LIKE', '#%')
                  ->where('u_compl', '=', 1);

    if($req->input('codArt')) {
      if($req->input('codArtOp')=='eql'){
        $products = $products->where('codice', strtoupper($req->input('codArt')));
      }
      if($req->input('codArtOp')=='stw'){
        $products = $products->where('codice', 'LIKE', strtoupper($req->input('codArt')).'%');
      }
      if($req->input('codArtOp')=='cnt'){
        $products = $products->where('codice', 'LIKE', '%'.strtoupper($req->input('codArt')).'%');
      }
    }

    if($req->input('descrArt')) {
      if($req->input('descrOp')=='eql'){
        $products = $products->where('descrizion', strtoupper($req->input('descrArt')));
      }
      if($req->input('descrOp')=='stw'){
        $products = $products->where('descrizion', 'LIKE', strtoupper($req->input('descrArt')).'%');
      }
      if($req->input('descrOp')=='cnt'){
        $products = $products->where('descrizion', 'LIKE', '%'.strtoupper($req->input('descrArt')).'%');
      }
    }

    if($req->input('gruppo')) {
      $products = $products->whereIn('gruppo', $req->input('gruppo'));
    }

    // if($req->input('chkTipo') && !$req->input('chkCamp')) {
    //   if(in_array("KR",$req->input('chkTipo'))){
    //     $products = $products->where('gruppo', 'LIKE', 'A%');
    //   }
    //   if (in_array("KO",$req->input('chkTipo')) && !in_array("KU",$req->input('chkTipo'))){
    //     $products = $products->where('gruppo', 'LIKE', 'B%')->where('gruppo', 'NOT LIKE', 'B06%');
    //   } elseif (in_array("KO",$req->input('chkTipo')) && in_array("KU",$req->input('chkTipo'))){
    //     $products = $products->where('gruppo', 'LIKE', 'B%');
    //   }
    //   if (in_array("KU",$req->input('chkTipo'))){
    //     $products = $products->where('gruppo', 'LIKE', 'B06%');
    //   }
    //   if (in_array("GR",$req->input('chkTipo'))){
    //     $products = $products->where('gruppo', 'LIKE', 'C%');
    //   }
    // }

    if($req->input('chkCamp')) {
      $products = $products->where('gruppo', 'LIKE', '2%');
    } else {
      $products = $products->where('gruppo', 'NOT LIKE', '2%');
    }

    if($req->input('chkPers')) {
      $products = $products->where('u_perscli', $req->input('chkPers'));
    } else {
      $products = $products->where('u_perscli', 0);
    }

    $products = $products->orderBy('gruppo')
                  ->orderBy('codice')
                  ->with('grpProd')->get();

    $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
      ->where('codice', 'NOT LIKE', 'DIC%')
      ->where('codice', 'NOT LIKE', '0%')
      ->where('codice', 'NOT LIKE', '2%')
      ->orderBy('codice')
      ->get();

    return view('prods.index', [
      'products' => $products,
      'gruppi' => $gruppi,
      'codArt' => $req->input('codArt'),
      'descrArt' => $req->input('descrArt'),
      'grpSelected' => $req->input('gruppo'),
      'chkTipo' => $req->input('chkTipo'),
      'chkPers' => $req->input('chkPers'),
      'chkCamp' => $req->input('chkCamp'),
    ]);
  }

  public function showNewProducts (Request $req){

    return redirect()->action('HomeController@index');
    

    $dt = Carbon::now();

    $products = Product::select('codice', 'descrizion', 'unmisura', 'gruppo', 'classe', 'listino1', 'listino6')
                  ->whereIn('statoart', ['1','8'])
                  ->where('classe', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', 'DIC%')
                  ->where('gruppo', 'NOT LIKE', '1%')
                  ->where('gruppo', 'NOT LIKE', 'C%')
                  ->where('gruppo', 'NOT LIKE', '2%')
                  ->where('u_compl', '=', 1)
                  ->where('codice', 'NOT LIKE', 'TKK%')
                  ->where('codice', 'NOT LIKE', '#%')
                  ->where('codice', 'NOT LIKE', 'CAMP%')
                  ->where('u_perscli', 0)
                  ->where('gruppo', '!=','')
                  ->where('u_datacrea', '>', Carbon::create($dt->year, 1, 1, 0))
                  ->orderBy('gruppo');
    $products = $products->with('grpProd')->get();

    $gruppi = SubGrpProd::where('codice', 'NOT LIKE', '1%')
                ->where('codice', 'NOT LIKE', 'DIC%')
                ->where('codice', 'NOT LIKE', '0%')
                ->where('codice', 'NOT LIKE', '2%')
                ->orderBy('codice')
                ->get();

    // $nazioni = Nazione::all();
    // $settori = Settore::all();
    // $zone = Zona::all();

    // dd($products);
    return view('prods.index', [
      'products' => $products,
      'gruppi' => $gruppi,
    ]);
  }

  public function showDetail (Request $req, $codArt){

    return redirect()->action('HomeController@index');
    
    $product = Product::with(['grpProd', 'clasProd'])->findOrFail($codArt);
    // dd($product);
    return view('prods.detail', [
      'prod' => $product,
    ]);
  }


  // API Function
  public function show (Request $req, $codArt) {

    return redirect()->action('HomeController@index');
    
    $product = Product::with(['grpProd', 'clasProd'])->findOrFail($codArt);
    // dd($product);
    return response()->json([
      'prod' => $product,
    ]);
  }

}

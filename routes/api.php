<?php

use Illuminate\Http\Request;
use knet\Http\Resources\CustomerCollection;
use knet\ArcaModels\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {
    //    Route::resource('task', 'TasksController');

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_api_routes
});

Route::get('users', 'UserController@allUsers')->middleware('auth:api');
Route::get('clients', 'ClientController@allCustomers')->middleware('auth:api');

Route::middleware('auth:api')->get('customer', function (Request $request) {
    $customers = Client::select('codice', 'descrizion')->paginate();
    return new CustomerCollection($customers);
});

Route::get('formCustomRequest', function (Request $request) {
    $it_forms = DB::connection('kNet_it')->table('w_modricfat')->select('*')->get();
    $es_forms = DB::connection('kNet_es')->table('w_modricfat')->select('*')->get();
    $fr_forms = DB::connection('kNet_fr')->table('w_modricfat')->select('*')->get();
    $merged = $it_forms->merge($es_forms);
    $merged = $merged->merge($fr_forms);
    return array(
        'data' => $merged,
        'meta' => []
    );
    
} );
<?php

use Illuminate\Http\Request;
use knet\Http\Resources\CustomerCollection;
use knet\ArcaModels\Client;
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
    
    return array(
        'data' => array(
            array(
                'data_ricezione' => Carbon::now()->format('d/m/Y'),
                'richiedente' => 'Adolf Haefele',
                'email_richiedente' => 'ced-it@k-group.com',
                'ragione_sociale' => 'Haefele DE',
                'codicecf' => 'C04173',
                'tipologia_prodotto' => 'Cerniera',
                'descr_pers' => 'Personalizzazione richiesta su gamma hybrid kombi',
                'url_pers' => '',
                'system_kk' => 'System Kombi Hybrid K1019',
                'system_other' => '',
                'info_tecn_comm' => 'Vendita per settore industrie Tedesche per porte >80Kg',
                'imballaggio' => 'Scatola Haefele (3 cerniere per scatola con mostrine e viti)',
                'um' => 'CF',
                'quantity' => 1200,
                'periodo_ordinativi' => 'Trimestrale',
                'target_price' => 3.20,
                'id_knet' => 1,
                'ditta' => 'it'
            ),
            array(
                'data_ricezione' => Carbon::now()->format('d/m/Y'),
                'richiedente' => 'Gustavo Frings',
                'email_richiedente' => 'ced-it@k-group.com',
                'ragione_sociale' => 'Ferrete',
                'codicecf' => 'C00011',
                'tipologia_prodotto' => 'Cerniera',
                'descr_pers' => 'Personalizzazione richiesta su system scorrevole',
                'url_pers' => '',
                'system_kk' => 'System scorrevole 0500',
                'system_other' => '',
                'info_tecn_comm' => 'Vendita per settore industrie Spagnole per porte >80Kg',
                'imballaggio' => 'Scatola (3 cerniere per scatola con mostrine e viti)',
                'um' => 'CF',
                'quantity' => 1200,
                'periodo_ordinativi' => 'Trimestrale',
                'target_price' => 4.20,
                'id_knet' => 2,
                'ditta' => 'es'
            ),
        ),
        'meta' => []
    );
});
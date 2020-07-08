<?php

use Illuminate\Http\Request;
use knet\Http\Resources\CustomerCollection;
use knet\ArcaModels\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


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
    $it_forms = DB::connection('kNet_it')->table('w_modricfat')->selectRaw('*, id as id_knet')->get();
    $es_forms = DB::connection('kNet_es')->table('w_modricfat')->selectRaw('*, id as id_knet')->get();
    $fr_forms = DB::connection('kNet_fr')->table('w_modricfat')->selectRaw('*, id as id_knet')->get();
    $merged = $it_forms->merge($es_forms);
    $merged = $merged->merge($fr_forms);
    return array(
        'data' => $merged,
        'meta' => []
    );
} );


Route::get('suppliers', function (Request $request) {
    $suppliers = DB::connection('kNet_it')
        ->table('anagrafe')
        ->selectRaw('codice, descrizion, partiva, indirizzo, cap, localita, prov, codnazione')
        ->where('codice', 'like', 'F%')
        ->get();
    return array(
        'data' => $suppliers,
        'meta' => []
    );
});

// KAUDIT
Route::group(['middleware' => 'throttle:60,1'], function () {
    Route::get('auditRispTeste', 'Audit\AuditRispTesteController@all');
    Route::get('auditRispTeste/{id}', 'Audit\AuditRispTesteController@show');
    Route::post('auditRispTeste', 'Audit\AuditRispTesteController@store');
    Route::put('auditRispTeste/{id}', 'Audit\AuditRispTesteController@update');
    Route::delete('auditRispTeste/{id}', 'Audit\AuditRispTesteController@delete');

    Route::get('auditRispRighe/{id_testa}', 'Audit\AuditRispRigheController@all');
    Route::get('auditRispRiga/{id}', 'Audit\AuditRispRigheController@show');
    Route::post('auditRispRighe', 'Audit\AuditRispRigheController@store');
    Route::put('auditRispRighe/{id}', 'Audit\AuditRispRigheController@update');
    Route::delete('auditRispRighe/{id}', 'Audit\AuditRispRigheController@delete');

    Route::get('auditModel', 'Audit\AuditModelsController@all');
    Route::get('auditModel/{codice}', 'Audit\AuditModelsController@show');
    Route::post('auditModel', 'Audit\AuditModelsController@store');
    Route::put('auditModel/{codice}', 'Audit\AuditModelsController@update');
    Route::delete('auditModel/{codice}', 'Audit\AuditModelsController@delete');

    Route::get('auditDomande/{codice}', 'Audit\AuditDomandeController@all');
    Route::get('auditDomanda/{id}', 'Audit\AuditDomandeController@show');
    Route::post('auditDomande', 'Audit\AuditDomandeController@store');
    Route::put('auditDomande/{id}', 'Audit\AuditDomandeController@update');
    Route::delete('auditDomande/{id}', 'Audit\AuditDomandeController@delete');
    Route::delete('auditDomandeForModello/{codice_modello}', 'Audit\AuditDomandeController@deleteForModello');
    Route::delete('auditDomandeAll', 'Audit\AuditDomandeController@deleteAll');

    // KAUDIT_DEV
    Route::group(['prefix' => 'dev'], function () {
        Route::get('auditRispTeste', 'Audit\_Dev\AuditRispTesteController_Dev@all');
        Route::get('auditRispTeste/{id}', 'Audit\_Dev\AuditRispTesteController_Dev@show');
        Route::post('auditRispTeste', 'Audit\_Dev\AuditRispTesteController_Dev@store');
        Route::put('auditRispTeste/{id}', 'Audit\_Dev\AuditRispTesteController_Dev@update');
        Route::delete('auditRispTeste/{id}', 'Audit\_Dev\AuditRispTesteController_Dev@delete');

        Route::get('auditRispRighe/{id_testa}', 'Audit\_Dev\AuditRispRigheController_Dev@all');
        Route::get('auditRispRiga/{id}', 'Audit\_Dev\AuditRispRigheController_Dev@show');
        Route::post('auditRispRighe', 'Audit\_Dev\AuditRispRigheController_Dev@store');
        Route::put('auditRispRighe/{id}', 'Audit\_Dev\AuditRispRigheController_Dev@update');
        Route::delete('auditRispRighe/{id}', 'Audit\_Dev\AuditRispRigheController_Dev@delete');

        Route::get('auditModel', 'Audit\_Dev\AuditModelsController_Dev@all');
        Route::get('auditModel/{codice}', 'Audit\_Dev\AuditModelsController_Dev@show');
        Route::post('auditModel', 'Audit\_Dev\AuditModelsController_Dev@store');
        Route::put('auditModel/{codice}', 'Audit\_Dev\AuditModelsController_Dev@update');
        Route::delete('auditModel/{codice}', 'Audit\_Dev\AuditModelsController_Dev@delete');

        Route::get('auditDomande/{codice}', 'Audit\_Dev\AuditDomandeController_Dev@all');
        Route::get('auditDomanda/{id}', 'Audit\_Dev\AuditDomandeController_Dev@show');
        Route::post('auditDomande', 'Audit\_Dev\AuditDomandeController_Dev@store');
        Route::put('auditDomande/{id}', 'Audit\_Dev\AuditDomandeController_Dev@update');
        Route::delete('auditDomande/{id}', 'Audit\_Dev\AuditDomandeController_Dev@delete');
        Route::delete('auditDomandeForModello/{codice_modello}', 'Audit\_Dev\AuditDomandeController_Dev@deleteForModello');
        Route::delete('auditDomandeAll', 'Audit\_Dev\AuditDomandeController_Dev@deleteAll');

        Route::get('suppliers', function (Request $request) {
            $suppliers = DB::connection('kNet_it')
            ->table('anagrafe')
            ->selectRaw('codice, descrizion, partiva, indirizzo, cap, localita, prov, codnazione')
            ->where('codice', 'like', 'F%')
            ->whereIn('statocf', ['N', 'U'])
            ->get();
            return array(
                    'data' => $suppliers,
                    'meta' => []
                );
        });
    });
});

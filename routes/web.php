<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blankPage', function () {
    return view('vendor._blankPage');
});

Route::get('/todo', function () {
    return view('todo');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});


//OLD Routes

Route::group(['as' => 'user::'], function () {
  Route::resource('users', 'UserController');
  Route::get('/cli_users', [
    'as' => 'usersCli',
    'uses' => 'UserController@indexCli'
  ]);
  Route::get('/users_import', [
    'as' => 'import',
    'uses' => 'UserController@showImport'
  ]);
  Route::post('/users_import', [
    'as' => 'import',
    'uses' => 'UserController@doImport'
  ]);
  Route::get('/actLike/{id}', [
    'as' => 'actLike',
    'uses' => 'UserController@actLike'
  ]);
  Route::post('/user_changeDB', [
    'as' => 'changeDB',
    'uses' => 'UserController@changeSelfDitta'
  ]);
  Route::post('/user_changeLang', [
    'as' => 'changeLang',
    'uses' => 'UserController@changeSelfLang'
  ]);
});

Route::group(['as' => 'client::'], function () {
  Route::get('/clients', [
    'as' => 'list',
    'uses' => 'ClientController@index'
  ]);
  Route::get('/client/{codice}', [
    'as' => 'detail',
    'uses' => 'ClientController@detail'
  ]);
  Route::post('/clients/filter', [
    'as' => 'fltList',
    'uses' => 'ClientController@fltIndex'
  ]);
});

Route::group(['as' => 'doc::'], function () {
  Route::get('/docs/{tipomodulo?}', [
    'as' => 'list',
    'uses' => 'DocCliController@index'
  ]);
  Route::post('/docs/filter', [
    'as' => 'fltList',
    'uses' => 'DocCliController@fltIndex'
  ]);
  Route::get('/client/{codice}/doc/{tipomodulo?}', [
    'as' => 'client',
    'uses' => 'DocCliController@docCli'
  ]);
  Route::get('/doc/{id_testa}', [
    'as' => 'detail',
    'uses' => 'DocCliController@showDetail'
  ]);

  Route::get('/docs_deliver', [
    'as' => 'orderDeliver',
    'uses' => 'DocCliController@showOrderToDeliver'
  ]);
  Route::get('/docs_receive', [
    'as' => 'ddtReceive',
    'uses' => 'DocCliController@showDdtToReceive'
  ]);  

  Route::get('/docXML/{id_testa?}', [
    'as' => 'downloadXML',
    'uses' => 'DocCliController@downloadXML'
  ]);
  Route::get('/docXLS/{id_testa?}', [
    'as' => 'downloadXLS',
    'uses' => 'DocCliController@downloadExcel'
  ]);

});

Route::group(['as' => 'prod::'], function () {
  Route::get('/prods', [
    'as' => 'list',
    'uses' => 'ProductController@index'
  ]);
  Route::post('/prods/filter', [
    'as' => 'fltList',
    'uses' => 'ProductController@fltIndex'
  ]);
  Route::get('/prod/{codice}', [
    'as' => 'detail',
    'uses' => 'ProductController@showDetail'
  ]);

  Route::get('/prods_new', [
    'as' => 'newProd',
    'uses' => 'ProductController@showNewProducts'
  ]);
});

Route::group(['as' => 'scad::'], function () {
  Route::get('/scads', [
    'as' => 'list',
    'uses' => 'ScadCliController@index'
  ]);
  Route::post('/scads/filter', [
    'as' => 'fltList',
    'uses' => 'ScadCliController@fltIndex'
  ]);
  Route::get('/client/{codice}/scads', [
    'as' => 'client',
    'uses' => 'ScadCliController@scadCli'
  ]);
  Route::get('/scad/{id_scad}', [
    'as' => 'detail',
    'uses' => 'ScadCliController@showDetail'
  ]);
});

Route::post('ddtConfirm/{id}', [
  'as' => 'ddtConfirm',
  'uses' => 'DdtOkController@store'
]);

Route::group(['as' => 'visit::'], function(){
  Route::get('/visit/insert/{codice?}', [
    'as' => 'insert',
    'uses' => 'VisitController@index'
  ]);
  Route::get('/visit/{codice}', [
    'as' => 'show',
    'uses' => 'VisitController@show'
  ]);
  Route::post('/visit/store', [
    'as' => 'store',
    'uses' => 'VisitController@store'
  ]);
});

Route::group(['as' => 'stFatt::'], function(){
  Route::get('/stFattAg/{codag?}', [
    'as' => 'idxAg',
    'uses' => 'StFattController@idxAg'
  ]);
  Route::post('/stFattAg', [
    'as' => 'idxAg',
    'uses' => 'StFattController@idxAg'
  ]);
  Route::get('/stFattCli', [
    'as' => 'idxCli',
    'uses' => 'StFattController@idxCli'
  ]);
  Route::get('/stFattCli/{codcli}', [
    'as' => 'fltCli',
    'uses' => 'StFattController@idxCli'
  ]);
  Route::post('/stFattCli', [
    'as' => 'idxCli',
    'uses' => 'StFattController@idxCli'
  ]);
  Route::get('/stFattZone', [
    'as' => 'idxZone',
    'uses' => 'StFattController@idxZone'
  ]);
  Route::post('/stFattZone', [
    'as' => 'idxZone',
    'uses' => 'StFattController@idxZone'
  ]);
  Route::get('/stFattManager', [
    'as' => 'idxManager',
    'uses' => 'StFattController@idxManager'
  ]);
  Route::post('/stFattManager', [
    'as' => 'idxManager',
    'uses' => 'StFattController@idxManager'
  ]);
});

Route::group(['as' => 'stAbc::'], function(){
  Route::get('/stAbcAg/{codag?}', [
    'as' => 'idxAg',
    'uses' => 'StAbcController@idxAg'
  ]);
  Route::post('/stAbcAg', [
    'as' => 'idxAg',
    'uses' => 'StAbcController@idxAg'
  ]);
  Route::get('/stAbcCli', [
    'as' => 'idxCli',
    'uses' => 'StAbcController@idxCli'
  ]);
  Route::get('/stAbcCli/{codcli}', [
    'as' => 'fltCli',
    'uses' => 'StAbcController@idxCli'
  ]);
  Route::post('/stAbcCli', [
    'as' => 'idxCli',
    'uses' => 'StAbcController@idxCli'
  ]);
  Route::get('/stAbcZone', [
    'as' => 'idxZone',
    'uses' => 'StAbcController@idxZone'
  ]);
  Route::post('/stAbcZone', [
    'as' => 'idxZone',
    'uses' => 'StAbcController@idxZone'
  ]);
  Route::get('/stAbcManager', [
    'as' => 'idxManager',
    'uses' => 'StAbcController@idxManager'
  ]);
  Route::post('/stAbcManager', [
    'as' => 'idxManager',
    'uses' => 'StAbcController@idxManager'
  ]);
});

Route::group(['as' => 'schedaCli::'], function () {
  Route::get('/schedaCliPDF/{codice}', [
    'as' => 'PDF',
    'uses' => 'SchedaCliController@downloadPDF'
  ]);
});

Route::group(['as' => 'Portfolio::'], function () {
  Route::get('/portfolioAg/{codice?}', [
    'as' => 'idxAg',
    'uses' => 'PortfolioController@idxAg'
  ]);
});
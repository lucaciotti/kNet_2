<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use knet\Jobs\FetchReportToSend;

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
    Route::get('myRoutes', function()
    {
      header('Content-Type: application/excel');
      header('Content-Disposition: attachment; filename="routes.csv"');
      $routes = Route::getRoutes();
      $fp = fopen('php://output', 'w');
      fputcsv($fp, ['METHOD', 'URI', 'NAME', 'ACTION']);
      foreach ($routes as $route) {
          fputcsv($fp, [head($route->methods()) , $route->uri(), $route->getName(), $route->getActionName()]);
      }
      fclose($fp);
    });
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
  Route::get('/enasarcoXLS/{id?}', [
    'as' => 'enasarcoXLS',
    'uses' => 'UserController@enasarcoXLS'
  ]);
});

Route::group(['as' => 'user::'], function () {
  Route::get('events', [
    'as' => 'events',
    'uses' => 'UserController@events'
  ]);
  Route::post('events', [
    'as' => 'events',
    'uses' => 'UserController@events'
  ]);
  Route::post('eventsAjax', [
    'as' => 'eventsAjax',
    'uses' => 'UserController@eventsAjax'
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

Route::group(['as' => 'supplier::'], function () {
  Route::get('/suppliers', [
    'as' => 'list',
    'uses' => 'SupplierController@index'
  ]);
  Route::get('/supplier/{codice}', [
    'as' => 'detail',
    'uses' => 'SupplierController@detail'
  ]);
  Route::post('/supplier/filter', [
    'as' => 'fltList',
    'uses' => 'SupplierController@fltIndex'
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
  Route::get('/docPDF/{id_testa}', [
    'as' => 'downloadPDF',
    'uses' => 'DocCliController@downloadPDF'
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

Route::group(['as' => 'rnc::'], function () {
  Route::get('/rncs', [
    'as' => 'list',
    'uses' => 'RNCController@list'
  ]);
  Route::post('/rncs/filter', [
    'as' => 'fltList',
    'uses' => 'RNCController@fltList'
  ]);
  Route::get('/client/{codice}/rncs', [
    'as' => 'client',
    'uses' => 'RNCController@rncCli'
  ]);
  Route::get('/rnc/{id_rnc}', [
    'as' => 'detail',
    'uses' => 'RNCController@showDetail'
  ]);
});

Route::group(['as' => 'listini::'], function () {
  Route::get('/listCli/{codicecf?}', [
    'as' => 'idxCli',
    'uses' => 'ListiniController@idxCli'
  ]);
  Route::post('/listCli/{codicecf?}', [
    'as' => 'idxCli',
    'uses' => 'ListiniController@idxCli'
  ]);
  Route::get('/listGpr/{grpCli?}', [
    'as' => 'grpCli',
    'uses' => 'ListiniController@idxGrpCli'
  ]);
  Route::post('/listGpr/{grpCli?}', [
    'as' => 'grpCli',
    'uses' => 'ListiniController@idxGrpCli'
  ]);
  Route::post('/listOk/{id?}', [
    'as' => 'wListOk',
    'uses' => 'ListiniController@setListOk'
  ]);
  Route::get('/cliListScad', [
    'as' => 'cliListScad',
    'uses' => 'ListiniController@cliListScad'
  ]);
  Route::get('/grpListScad', [
    'as' => 'grpListScad',
    'uses' => 'ListiniController@grpListScad'
  ]);
});

Route::group(['as' => 'promo::'], function () {
  Route::get('/promoList', [
    'as' => 'idx',
    'uses' => 'PromoController@idx'
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
  Route::get('/visit/edit/{id}', [
    'as' => 'edit',
    'uses' => 'VisitController@edit'
  ]);
  Route::post('/visit/delete/{id}', [
    'as' => 'delete',
    'uses' => 'VisitController@delete'
  ]);
  Route::get('/visitsupplier/insert/{codice?}', [
    'as' => 'insertSupplier',
    'uses' => 'VisitController@indexSupplier'
  ]);
  Route::get('/visitsupplier/edit/{id}', [
    'as' => 'editSupplier',
    'uses' => 'VisitController@editSupplier'
  ]);
  Route::post('/visitsupplier/delete/{id}', [
    'as' => 'deleteSupplier',
    'uses' => 'VisitController@deleteSupplier'
  ]);
  Route::get('/visit/insertRubri/{rubri_id?}', [
    'as' => 'insertRubri',
    'uses' => 'VisitController@indexRubri'
  ]);
  Route::get('/visit/{codice}', [
    'as' => 'show',
    'uses' => 'VisitController@show'
  ]);
  Route::get('/visitsupplier/{codice}', [
    'as' => 'showSupplier',
    'uses' => 'VisitController@showSupplier'
  ]);
  Route::get('/visitRubri/{rubri_id}', [
    'as' => 'showRubri',
    'uses' => 'VisitController@showRubri'
  ]);
  Route::post('/visit/store', [
    'as' => 'store',
    'uses' => 'VisitController@store'
  ]);

  Route::get('/visit_report', [
    'as' => 'report',
    'uses' => 'VisitController@report'
  ]);
  Route::post('/visit_report', [
    'as' => 'report',
    'uses' => 'VisitController@report'
  ]);
  Route::get('/visit_reportPDF', [
    'as' => 'reportPDF',
    'uses' => 'VisitController@reportPDF'
  ]);
  Route::get('/visit_countPDF', [
    'as' => 'countPDF',
    'uses' => 'VisitController@countPDF'
  ]);
  

  Route::get('/visit_importXls', [
    'as' => 'importXls',
    'uses' => 'VisitController@showImportXls'
  ]);
  Route::post('/visit_importXls', [
    'as' => 'importXls',
    'uses' => 'VisitController@doImportXls'
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
  Route::get('/stAbcCli/{codcli?}', [
    'as' => 'idxCli',
    'uses' => 'StAbcController@idxCli'
  ]);
  Route::post('/stAbcCli', [
    'as' => 'idxCli',
    'uses' => 'StAbcController@idxCli'
  ]);
  Route::get('/stAbcArt', [
    'as' => 'idxArt',
    'uses' => 'StAbcController@idxArt'
  ]);
  Route::post('/stAbcArt', [
    'as' => 'idxArt',
    'uses' => 'StAbcController@idxArt'
  ]);
  // ------------------------------
  Route::get('/stAbc/{codArt}', [
    'as' => 'detailArt',
    'uses' => 'StAbcController@detailArt'
  ]);
  Route::post('/stAbc/{codArt}', [
    'as' => 'detailArt',
    'uses' => 'StAbcController@detailArt'
  ]);
  Route::get('/stAbc/{codArt}/{codcli}', [
    'as' => 'docsArtCli',
    'uses' => 'StAbcController@docsArtCli'
  ]);
  // ------------------------------
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

Route::group(['as' => 'stFattArt::'], function () {
  Route::get('/stFattArt/{codag?}', [
    'as' => 'idxAg',
    'uses' => 'StFattArtController@idxAg'
  ]);
  Route::post('/stFattArt', [
    'as' => 'idxAg',
    'uses' => 'StFattArtController@idxAg'
  ]);
  Route::get('/stFattArtZone', [
    'as' => 'idxZone',
    'uses' => 'StFattArtController@idxZone'
  ]);
  Route::post('/stFattArtZone', [
    'as' => 'idxZone',
    'uses' => 'StFattArtController@idxZone'
  ]);
});
Route::group(['as' => 'schedaFatArt::'], function () {
  Route::get('/schedaFatArtPDF/{codicecf}', [
    'as' => 'PDF',
    'uses' => 'SchedaFatArtController@downloadPDF'
  ]);
  Route::get('/schedaFatArtPDF', [
    'as' => 'PDFTot',
    'uses' => 'SchedaFatArtController@downloadPDFTotByCustomer'
  ]);
  Route::get('/schedaFatArtPDFCli', [
    'as' => 'PDFListaCli',
    'uses' => 'SchedaFatArtController@downloadPDFListaCli'
  ]);
  Route::get('/schedaFatArtXls/{codicecf}', [
    'as' => 'XLS',
    'uses' => 'SchedaFatArtController@downloadXLS'
  ]);
  Route::get('/schedaFatArtXls', [
    'as' => 'XLSTot',
    'uses' => 'SchedaFatArtController@downloadXLSTot'
  ]);
  Route::get('/schedaFatArtXLSCli', [
    'as' => 'XLSListaCli',
    'uses' => 'SchedaFatArtController@downloadXLSListaCli'
  ]);
});

Route::group(['as' => 'schedaCli::'], function () {
  Route::get('/schedaCliPDF/{codice}', [
    'as' => 'PDF',
    'uses' => 'SchedaCliController@downloadPDF'
  ]);
});
Route::group(['as' => 'schedaFat::'], function () {
  Route::get('/schedaFatPDF/{codAg?}', [
    'as' => 'PDF',
    'uses' => 'SchedaFattController@downloadPDF'
  ]);
  Route::get('/schedaFatZonePDF/{codAg?}', [
    'as' => 'ZonePDF',
    'uses' => 'SchedaFattController@downloadZonePDF'
  ]);
});
Route::group(['as' => 'schedaAbc::'], function () {
  Route::get('/schedaAbcPDF/{codAg}', [
    'as' => 'PDF',
    'uses' => 'SchedaAbcController@downloadPDF'
  ]);
});
Route::group(['as' => 'schedaScad::'], function () {
  Route::get('/schedaProvPDF/{codAg}/{year?}', [
    'as' => 'ProvPDF',
    'uses' => 'SchedaScadController@downloadProvPDF'
  ]);
  Route::get('/schedaProvPP_PDF/{codAg}/{year?}', [
    'as' => 'ProvPP_PDF',
    'uses' => 'SchedaScadController@downloadProvPP_PDF'
  ]);
  Route::get('/schedaScadPDF/{codAg?}', [
    'as' => 'ScadPDF',
    'uses' => 'SchedaScadController@downloadScadPDF'
  ]);
});

Route::group(['as' => 'Portfolio::'], function () {
  Route::get('/portfolioAg/{codice?}', [
    'as' => 'idxAg',
    'uses' => 'PortfolioController@idxAg'
  ]);
  Route::post('/portfolioAg', [
    'as' => 'idxAg',
    'uses' => 'PortfolioController@idxAg'
  ]);


  Route::get('/portfolioAgByCustomer/{codice?}', [
    'as' => 'portfolioAgByCustomer',
    'uses' => 'PortfolioController@portfolioAgByCustomer'
  ]);
  Route::post('/portfolioAgByCustomer', [
    'as' => 'portfolioAgByCustomer',
    'uses' => 'PortfolioController@portfolioAgByCustomer'
  ]);

  Route::get('/portfolioAgByCustomerPDF', [
    'as' => 'portfolioAgByCustomerPDF',
    'uses' => 'PortfolioController@portfolioAgByCustomerPDF'
  ]);
  Route::get('/portfolioListOCandXC', [
    'as' => 'portfolioListOCandXC',
    'uses' => 'PortfolioController@portfolioListOCandXC'
  ]);


  Route::get('/orders_toDispach', [
    'as' => 'ordersDispach',
    'uses' => 'DocCliController@showOrderDispachMonth'
  ]);
  Route::get('/ddts_toInvoice', [
    'as' => 'ddtInvoice',
    'uses' => 'DocCliController@showDdtToInvoice'
  ]); 
  Route::get('/invoice_month', [
    'as' => 'invoiceMonth',
    'uses' => 'DocCliController@showInvoiceMonth'
  ]);

  Route::get('/portfolioPDF/{codAg}/{mese?}/{year?}', [
    'as' => 'portfolioPDF',
    'uses' => 'PortfolioController@portfolioPDF'
  ]);
});

Route::group(['as' => 'rubri::'], function () {
  Route::get('/rubri_import', [
    'as' => 'import',
    'uses' => 'RubriController@showImport'
  ]);
  Route::post('/rubri_import', [
    'as' => 'import',
    'uses' => 'RubriController@doImport'
  ]);
  Route::get('/rubrica', [
    'as' => 'list',
    'uses' => 'RubriController@index'
  ]);
  Route::get('/contact/{rubri_id}', [
    'as' => 'detail',
    'uses' => 'RubriController@detail'
  ]);
  Route::post('/rubrica/filter', [
    'as' => 'fltList',
    'uses' => 'RubriController@fltIndex'
  ]);
  Route::post('/closeContact/{rubri_id}', [
    'as' => 'close',
    'uses' => 'RubriController@closeContact'
  ]);
  Route::get('/rubri_insertOrEdit/{rubri_id?}', [
    'as' => 'insertOrEdit',
    'uses' => 'RubriController@insertOrEdit'
  ]);
  Route::post('/rubri_store', [
    'as' => 'store',
    'uses' => 'RubriController@store'
  ]);
});

Route::group(['as' => 'sysMkt::'], function () {
  Route::resource('sysMkt', 'SysMktController')->only([
    'index', 'store', 'destroy'
  ]);
});

Route::group(['as' => 'ModCarp01::'], function () {
  Route::get('/createModule/{rubri_id}', [
    'as' => 'create',
    'uses' => 'ModCarp01Controller@createModule'
  ]);  
  Route::post('/storeModCarp01', [
    'as' => 'store',
    'uses' => 'ModCarp01Controller@store'
  ]);
  Route::get('/editModule/{rubri_id}', [
    'as' => 'edit',
    'uses' => 'ModCarp01Controller@edit'
  ]);
  Route::post('/updModCarp01', [
    'as' => 'update',
    'uses' => 'ModCarp01Controller@update'
  ]);
  Route::delete('/delModCarp01/{rubri_id}', [
    'as' => 'delete',
    'uses' => 'ModCarp01Controller@delete'
  ]);
});

Route::group(['as' => 'ModRicFatt::'], function () {
  Route::get('/createRicFatt/{codicecf}', [
    'as' => 'create',
    'uses' => 'ModRicFattController@createModule'
  ]);
  Route::post('/storeRicFatt', [
    'as' => 'store',
    'uses' => 'ModRicFattController@store'
  ]);
  Route::get('/editRicFatt/{id}', [
    'as' => 'edit',
    'uses' => 'ModRicFattController@edit'
  ]);
  Route::post('/updRicFatt', [
    'as' => 'update',
    'uses' => 'ModRicFattController@update'
  ]);
  Route::delete('/delRicFatt/{id}', [
    'as' => 'delete',
    'uses' => 'ModRicFattController@delete'
  ]);
});

Route::group(['as' => 'manuale::'], function () {
  Route::get('/manuale_agente', [
    'as' => 'agente',
    'uses' => function (Request $request) {
      $fileName = public_path('manuali/ManualeAgente-kNet.pdf');
      return response()->download($fileName);
    }
  ]);
});

Route::group(['as' => 'jobs::'], function () {
  Route::get('jobReportWeekly', [
    'as' => 'reportWeekly',
    'uses' => function (Request $req) {
      $job = (new FetchReportToSend('weekly'))->onQueue('jobs');
      $key = Bus::dispatch($job);
      Log::info('Dispached FetchReportToSend weekly '. $key);
      return Redirect::back()->withErrors(['msg' => 'Job Created']);
    }
  ]);
  Route::get('jobReportMonthly', [
    'as' => 'reportMonthly',
    'uses' => function (Request $req) {
      $job = (new FetchReportToSend('monthly'))->onQueue('jobs');
      $key = Bus::dispatch($job);
      Log::info('Dispached FetchReportToSend monthly '. $key);
      return Redirect::back()->withErrors(['msg' => 'Job Created']);
    }
  ]);
  Route::get('jobReportQuarterly', [
    'as' => 'reportQuarterly',
    'uses' => function (Request $req) {
      $job = (new FetchReportToSend('quarterly'))->onQueue('jobs');
      $key = Bus::dispatch($job);
      Log::info('Dispached FetchReportToSend quarterly '. $key);
      return Redirect::back()->withErrors(['msg' => 'Job Created']);
    }
  ]);
});


Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
// Route::any('zipcode', function() {

//     echo
//         Form::open(array('url' => 'zipcode')) .
//         Form::select('country', ZipCode::getAvailableCountries(), Input::get('country')) .
//         Form::text('zipcode', Input::get('zipcode')) .
//         Form::submit('go!') .
//         Form::close();

//     if (Input::get('country'))
//     {
//         ZipCode::setCountry(Input::get('country'));
//         /* ZipCode::setPreferredWebService('Geonames');
//         ZipCode::setQueryParameter('geonames_username', 'lucac18i'); */

//         //$webService = ZipCode::getWebServiceByName('Geonames');
//         //$webService->setUrl('http://api.zippopotam.ca');
//         $result = ZipCode::find(Input::get('zipcode'));

//         echo '<pre>';

//         // var_dump($result->toArray());
//         if($result->getSuccess() && $result->getWebService()=="Geonames"){
//           echo "Città: ".($result->getAddresses())[0]['city'];
//           echo '<br>';
//           echo "Provincia: ".($result->getAddresses())[0]['department'];
//           echo '<br>';
//           echo "PROV: ".($result->getAddresses())[0]['department_id'];
//           echo '<br>';
//           echo "Regione: ".($result->getAddresses())[0]['state_name'];
//           echo '<br>';
//           echo ($result->getCountryId());
//           echo '<br>';
//           echo ($result->getCountryName());
//         } else {
//           echo 'NOT FOUND!!';
//         }

//         echo '</pre>';
//     }

// });
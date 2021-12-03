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

Route::get('test', function() {
    dd (DB::connection()->getPdo());
});

//Route::get('bienes-servicios', 'SoapController@BienesServicios');
Route::get('integracion', 'SoapController@index')->name('admin.sic.integracion');


//Route::get('solicitudAceptada', 'SoapController@index');

Route::get('/prueba', function () {
    $opts = array(
        'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false)
    );
    $params = array (
        'encoding'          => 'UTF-8', 
        'verifypeer'        => false, 
        'verifyhost'        => false, 
        'soap_version'      => SOAP_1_2, 
        'trace'             => 1, 
        'exceptions'        => 1, 
        "connection_timeout" => 180, 
        //  'stream_context' => stream_context_create($opts),
        'login'          => "DerivIquique",
        'password'       => "DerivIquiquePRO"
    );
    $url = "https://prepplataformadeintegraciones.saludenred.cl:8292/WSNotificacionesInterconsulta/Derivacion.svc?wsdl";

    try{
        $client = new SoapClient($url, $params);
 
        dd($client->__getTypes());
        //dd($client->GetCitiesByCountry(['CountryName' => 'Peru'])->GetCitiesByCountryResult);
    }
    catch(SoapFault $fault) {
        echo '<br>'.$fault;
    }

});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::get('/ser', 'HomeController@index');



Route::get('/sigte', 'SigteController@index');

//Route::get('/monitor_sic', 'SicController@index');
Route::get('/monitor_sic', 'SicController@index')->name('admin.sic.index');
//Route::get('/sic/{rut}', 'SicController@show');
Route::get('/sic/buscar', 'SicController@show')->name('admin.sic.show');   //PANTALLA

Route::get('/sic', function () {
    return view('sic.integracion');
});


Route::get('/listaespera/{listaespera}', 'ListaEsperaController@show')->name('admin.listaespera.show');
Route::post('/sigte-ingreso', 'SigteController@ingreso')->name('admin.sigte.importar-ingreso');
Route::post('/sigte-egreso', 'SigteController@egreso')->name('admin.sigte.importar-egreso');
Route::post('/sigte-edicion', 'SigteController@edicion')->name('admin.sigte.importar-edicion');

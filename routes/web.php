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
    return redirect('https://ludcis.com');
});
Route::get('documentos/{hash}', 'ControladorGeneral@checkDocumento');
// Route::get('consulta/{word}', 'ControladorBusquedas@buscarDocumentos');
Route::get('payu/retorno', 'PayUController@goBack');
Route::post('pago', 'PayUController@redirigirPago');

// Route::get('testbill/{id}', 'ControladorDocumentos@pdfFactura');

// Route::get('testdoc', 'ControladorGeneral@vistaPrueba');

Route::get('ptransito', 'ControladorGeneral@vistaTransito');
Route::post('ptransito', 'ControladorDocumentos@pdfTransito');

Route::get('prejuridico', 'ControladorGeneral@vistaCobro');
Route::post('prejuridico', 'ControladorDocumentos@pdfCobro');

// Route::get('pagare', 'ControladorGeneral@vistaPagare');
// Route::post('pagare', 'ControladorDocumentos@pdfPagare');

Route::get('confidencialidad', 'ControladorGeneral@vistaConfidencialidad');
Route::post('confidencialidad', 'ControladorDocumentos@pdfConfidencialidad');

Route::get('ctrabajo', 'ControladorGeneral@vistaTrabajo');
Route::post('ctrabajo', 'ControladorDocumentos@pdfTrabajo');

// Route::get('cservicios', 'ControladorGeneral@vistaServicios');
// Route::post('cservicios', 'ControladorDocumentos@pdfServicios');

Route::get('cdomestico', 'ControladorGeneral@vistaDomestico');
Route::post('cdomestico', 'ControladorDocumentos@pdfDomestico');

// Route::get('carrendamiento', 'ControladorGeneral@vistaArrendamiento');
// Route::post('carrendamiento', 'ControladorDocumentos@pdfArrendamiento');

Route::get('ccesion', 'ControladorGeneral@vistaCesion');
Route::post('ccesion', 'ControladorDocumentos@pdfCesion');

Route::get('pnatural', 'ControladorGeneral@vistaPoderNatural');
Route::post('pnatural', 'ControladorDocumentos@pdfPoderNatural');

Route::get('pceo', 'ControladorGeneral@vistaPoderCEO');
Route::post('pceo', 'ControladorDocumentos@pdfPoderCEO');

Route::get('comodato', 'ControladorGeneral@vistaComodato');
Route::post('comodato', 'ControladorDocumentos@pdfComodato');

Route::get('compraventa', 'ControladorGeneral@vistaCompraventa');
Route::post('compraventa', 'ControladorDocumentos@pdfCompraventa');

// Route::get('test', function () {

// return view('layouts.payu_send');

// });

Route::get('/{any}', function ($any) {

return redirect('https://ludcis.com');

})->where('any', '.*');


// Route::get('probar_correo', 'ControladorGeneral@correoPrueba');
// Route::get('prueba', 'ControladorGeneral@pruebaIp');
<?php

use Illuminate\Http\Request;

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
Route::resource('confirmations/payu', 'PayUController')->only([
    'store'
]);
Route::resource('confirmations/epayco', 'EpaycoController')->only([
    'store'
]);
Route::resource('disputes/payu', 'ControladorDisputas')->only([
    'store'
]);
Route::post('epayco/retorno', 'EpaycoController@goBack');
Route::get('consulta/{word}', 'ControladorBusquedas@buscarDocumentos');

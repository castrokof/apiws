<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('menu.submenu');
});

Auth::routes(['verify' => true]);

//Ruta para consultar lo direccionado por la EPS
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo direccionado por la EPS por documento
Route::get('/direccionado', 'HomeController@direccionado')->name('direccionado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a programar las prescripciones desde la function index
Route::post('/programar', 'HomeController@Programarm')->name('programar')->middleware('verified');

//Ruta para consultar lo programado por el regente o administrador
Route::get('/programado', 'HomeController@indexp')->name('programado')->middleware('verified');

//Ruta que se usa para enviar por el servicio put a anular lo programado desde la function indexp
Route::post('/a-programar', 'HomeController@Anularprogramacion')->name('a-programar')->middleware('verified');


//Ruta que se usa para enviar por el servicio put el reporte de la dispensación desde la function indexd
Route::post('/dispensado', 'HomeController@Reportardispensacion')->name('dispensado')->middleware('verified');

//Ruta que se usa para direccionar a la vista del ingreso del token hercules
Route::get('/tokenhercules', 'HomeController@tokenherculesindex')->name('tokenhercules')->middleware('verified');

//Ruta que se usa para enviar por el servicio put a generar el token del ws
Route::post('/tokenhercules_token', 'HomeController@tokenhercules')->name('tokenhercules1')->middleware('verified');

//Ruta para consultar lo entregado
Route::get('/entregado', 'HomeController@indexe')->name('entregado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular lo entregado desde la function indexe
Route::post('/a-entrega', 'HomeController@Anularentrega')->name('a-entrega')->middleware('verified');

//Ruta para consultar lo reportado y entregado
Route::get('/repentregado', 'HomeController@indexrepe')->name('repentregado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put el reporte de lo entregado desde la function indexe
Route::post('/r-entrega', 'HomeController@Reportarentrega')->name('r-entrega')->middleware('verified');

//Ruta que se usa para enviar por el servicio put a anular el reporte de entrega desde la function indexrepe
Route::post('/a-rentrega', 'HomeController@Anularrentrega')->name('a-rentrega')->middleware('verified');

//Ruta que se usa para enviar por el servicio put el reporte de lo entregado desde la function indexe
Route::post('/r-factura', 'HomeController@Reportarfactura')->name('r-factura')->middleware('verified');

//Ruta para consultar lo facturado
Route::get('/facturado', 'HomeController@indexf')->name('facturado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular el reporte de lo facturado desde la function indexf
Route::post('/a-facturado', 'HomeController@Anularfactura')->name('a-facturado')->middleware('verified');



// Nuevo
//Ruta para direccionar los diferentes modulos

Route::get('/submenu', 'MenuContoller@index')->name('submenu')->middleware('verified');
Route::get('/usuariosapi', 'UsuarioApiContoller@index')->name('usuariosapi')->middleware('verified');
Route::get('/guardar_usuario', 'UsuarioApiContoller@createuserapi')->name('guardar_usuario')->middleware('verified');

//Rutas de tablas de pendientes MEDCOL 2

Route::get('/pendientes', 'PendienteApiController@index')->name('pendientes')->middleware('verified');
Route::get('/porentregar', 'PendienteApiController@porentregar')->name('porentregar')->middleware('verified');
Route::get('/entregados', 'PendienteApiController@entregados')->name('entregados')->middleware('verified');
Route::get('/desabastecidos', 'PendienteApiController@getDesabastecidos')->name('desabastecidos')->middleware('verified');
Route::get('/anulados', 'PendienteApiController@getAnulados')->name('anulados')->middleware('verified');
Route::get('/guardar_observacion', 'PendienteApiController@guardar')->name('guardar_observacion')->middleware('verified');

Route::get('editpendientes/{id}', 'PendienteApiController@edit')->name('pendientes-edit')->middleware('verified');
Route::get('showpendientes/{id}', 'PendienteApiController@show')->name('pendientes-show')->middleware('verified');
Route::put('pendientes/{id}', 'PendienteApiController@update')->name('actualizar_pendientes')->middleware('verified');
Route::post('pendientes', 'PendienteApiController@saveObs')->name('crear_observacion')->middleware('verified');

Route::get('observaciones', 'PendienteApiController@getObservaciones')->name('observaciones')->middleware('verified');

Route::get('/syncapi', 'PendienteApiController@createapendientespi')->name('syncapi')->middleware('verified');

Route::get('informe', 'PendienteApiController@informes')->name('informe')->middleware('verified');

/*
**
**
*/
//Rutas de tablas de pendientes MEDCOL 3

Route::get('medcol3/pendientes', 'Medcol3\PendienteApiMedcol3Controller@index')->name('medcol3.pendientes')->middleware('verified');
Route::get('medcol3/porentregar', 'Medcol3\PendienteApiMedcol3Controller@porentregar')->name('medcol3.porentregar')->middleware('verified');
Route::get('medcol3/entregados', 'Medcol3\PendienteApiMedcol3Controller@entregados')->name('medcol3.entregados')->middleware('verified');
Route::get('medcol3/desabastecidos', 'Medcol3\PendienteApiMedcol3Controller@getDesabastecidos')->name('medcol3.desabastecidos')->middleware('verified');
Route::get('medcol3/anulados', 'Medcol3\PendienteApiMedcol3Controller@getAnulados')->name('medcol3.anulados')->middleware('verified');
Route::get('medcol3/guardar_observacion', 'Medcol3\PendienteApiMedcol3Controller@guardar')->name('medcol3.guardar_observacion')->middleware('verified');

Route::get('medcol3/editpendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@edit')->name('medcol3.pendientes-edit')->middleware('verified');
Route::get('medcol3/showpendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@show')->name('medcol3.pendientes-show')->middleware('verified');
Route::put('medcol3/pendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@update')->name('medcol3.actualizar_pendientes')->middleware('verified');
Route::post('medcol3/pendientes', 'Medcol3\PendienteApiMedcol3Controller@saveObs')->name('medcol3.crear_observacion')->middleware('verified');

Route::get('medcol3/observaciones', 'Medcol3\PendienteApiMedcol3Controller@getObservaciones')->name('medcol3.observaciones')->middleware('verified');

Route::get('medcol3/syncapi', 'Medcol3\PendienteApiMedcol3Controller@createapendientespi')->name('medcol3.syncapi')->middleware('verified');

Route::get('medcol3/informe', 'Medcol3\PendienteApiMedcol3Controller@informes')->name('medcol3.informe')->middleware('verified');

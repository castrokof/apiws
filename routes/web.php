<?php

use Illuminate\Support\Facades\Route;

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

/*Route::prefix('usuarios')->group(function() {
  Auth::routes();
});*/

Auth::routes(['register' => false]);

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('registrar', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('verified')->middleware('verifyuser');
Route::post('registrar', 'Auth\RegisterController@register')->middleware('verified')->middleware('verifyuser');
Route::get('usuariosapiws', 'Auth\RegisterController@usuariosApiws')->name('usuariosapiws')->middleware('verified')->middleware('verifyuser');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
Route::emailVerification();

//Auth::routes(['verify' => true, 'verifyuser' => true]);

//Ruta para consultar lo direccionado por la EPS
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo direccionado por la EPS por documento
Route::get('/direccionado', 'HomeController@direccionado')->name('direccionado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a programar las prescripciones desde la function index
Route::post('/programar', 'HomeController@Programarm')->name('programar')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo programado por el regente o administrador
Route::get('/programado', 'HomeController@indexp')->name('programado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular lo programado desde la function indexp
Route::post('/a-programar', 'HomeController@Anularprogramacion')->name('a-programar')->middleware('verified')->middleware('verifyuser');


//Ruta que se usa para enviar por el servicio put el reporte de la dispensación desde la function indexd
Route::post('/dispensado', 'HomeController@Reportardispensacion')->name('dispensado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para direccionar a la vista del ingreso del token hercules
Route::get('/tokenhercules', 'HomeController@tokenherculesindex')->name('tokenhercules')->middleware('verified');

//Ruta que se usa para enviar por el servicio put a generar el token del ws
Route::post('/tokenhercules_token', 'HomeController@tokenhercules')->name('tokenhercules1')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo entregado
Route::get('/entregado', 'HomeController@indexe')->name('entregado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular lo entregado desde la function indexe
Route::post('/a-entrega', 'HomeController@Anularentrega')->name('a-entrega')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo reportado y entregado
Route::get('/repentregado', 'HomeController@indexrepe')->name('repentregado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put el reporte de lo entregado desde la function indexe
Route::post('/r-entrega', 'HomeController@Reportarentrega')->name('r-entrega')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular el reporte de entrega desde la function indexrepe
Route::post('/a-rentrega', 'HomeController@Anularrentrega')->name('a-rentrega')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put el reporte de lo entregado desde la function indexe
Route::post('/r-factura', 'HomeController@Reportarfactura')->name('r-factura')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo facturado
Route::get('/facturado', 'HomeController@indexf')->name('facturado')->middleware('verified')->middleware('verifyuser')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a anular el reporte de lo facturado desde la function indexf
Route::post('/a-facturado', 'HomeController@Anularfactura')->name('a-facturado')->middleware('verified')->middleware('verifyuser');


// Nuevo
//Ruta para direccionar los diferentes modulos

Route::get('/submenu', 'MenuContoller@index')->name('submenu')->middleware('verified');
Route::get('/menudispensado', 'MenuContoller@index1')->name('dismenu')->middleware('verified');
Route::get('/usuariosapi', 'UsuarioApiContoller@index')->name('usuariosapi')->middleware('verified')->middleware('verifyuser');
Route::get('/guardar_usuario', 'UsuarioApiContoller@createuserapi')->name('guardar_usuario')->middleware('verified')->middleware('verifyuser');

//Rutas de tablas de pendientes MEDCOL 2

Route::get('/pendientes', 'PendienteApiController@index')->name('pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('/pendientes1', 'PendienteApiController@index1')->name('pendientes1')->middleware('verified')->middleware('verifyuser');
Route::post('/porentregar', 'PendienteApiController@porentregar')->name('porentregar')->middleware('verified')->middleware('verifyuser');
Route::post('/entregados', 'PendienteApiController@entregados')->name('entregados')->middleware('verified')->middleware('verifyuser');
Route::post('/desabastecidos', 'PendienteApiController@getDesabastecidos')->name('desabastecidos')->middleware('verified')->middleware('verifyuser');
Route::post('/anulados', 'PendienteApiController@getAnulados')->name('anulados')->middleware('verified')->middleware('verifyuser');
Route::get('/guardar_observacion', 'PendienteApiController@guardar')->name('guardar_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('editpendientes/{id}', 'PendienteApiController@edit')->name('pendientes-edit')->middleware('verified')->middleware('verifyuser');
Route::get('showpendientes/{id}', 'PendienteApiController@show')->name('pendientes-show')->middleware('verified')->middleware('verifyuser');
Route::put('pendientes/{id}', 'PendienteApiController@update')->name('actualizar_pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('pendientes', 'PendienteApiController@saveObs')->name('crear_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('observaciones', 'PendienteApiController@getObservaciones')->name('observaciones')->middleware('verified')->middleware('verifyuser');

Route::get('/syncapi', 'PendienteApiController@createapendientespi')->name('syncapi')->middleware('verified')->middleware('verifyuser');

Route::get('informe', 'PendienteApiController@informes')->name('informe')->middleware('verified')->middleware('verifyuser');

Route::get('informepedientes', 'PendienteApiController@informepedientes')->name('informepedientes')->middleware('verified')->middleware('verifyuser');


//Rutas de tablas de Dispensado MEDCOL 2

Route::get('medcol2/dispensado', 'Medcol2\DispensadoApiMedcol2Controller@index')->name('medcol2.dispensado')->middleware('verified')->middleware('verifyuser');
Route::post('medcol2/dispensado1', 'Medcol2\DispensadoApiMedcol2Controller@index1')->name('medcol2.dispensado1')->middleware('verified')->middleware('verifyuser');
Route::post('medcol2/disrevisado', 'Medcol2\DispensadoApiMedcol2Controller@disrevisado')->name('medcol2.disrevisado')->middleware('verified')->middleware('verifyuser');
Route::put('medcol2/dispensado/{id}', 'Medcol2\DispensadoApiMedcol2Controller@update')->name('medcol2.actualizar_dispensado')->middleware('verified')->middleware('verifyuser');

Route::get('medcol2/dispensado/syncdisapi', 'Medcol2\DispensadoApiMedcol2Controller@createdispensadoapi')->name('medcol2.dispensadosyncapi')->middleware('verified')->middleware('verifyuser');
Route::get('medcol2/dispensado/anuladosapi', 'Medcol2\DispensadoApiMedcol2Controller@updateanuladosapi')->name('medcol2.anuladosapi')->middleware('verified')->middleware('verifyuser');
Route::post('medcol2/add_dispensado', 'Medcol2\DispensadoApiMedcol2Controller@adddispensacionarray')->name('add_dispensacion2')->middleware('verified')->middleware('verifyuser');
Route::get('medcol2/informedis', 'Medcol2\DispensadoApiMedcol2Controller@informes')->name('medcol2.informedis')->middleware('verified')->middleware('verifyuser');

Route::post('medcol2/disanulado', 'Medcol2\DispensadoApiMedcol2Controller@disanulado')->name('medcol2.disanulado')->middleware('verified')->middleware('verifyuser');
Route::post('medcol2/update', 'Medcol2\DispensadoApiMedcol2Controller@actualizarDispensacion')->name('dispensado2.guardar')->middleware('verified')->middleware('verifyuser');

Route::get('buscar/{factura}', 'Medcol2\DispensadoApiMedcol2Controller@buscar')->name('dispensado2.buscar')->middleware('verified')->middleware('verifyuser');

/*
**
**
*/
//Rutas de tablas de pendientes MEDCOL 4

Route::get('medcol3/pendientes', 'Medcol3\PendienteApiMedcol3Controller@index')->name('medcol3.pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/pendientes1', 'Medcol3\PendienteApiMedcol3Controller@index1')->name('medcol3.pendientes1')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/porentregar', 'Medcol3\PendienteApiMedcol3Controller@porentregar')->name('medcol3.porentregar')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/entregados', 'Medcol3\PendienteApiMedcol3Controller@entregados')->name('medcol3.entregados')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/desabastecidos', 'Medcol3\PendienteApiMedcol3Controller@getDesabastecidos')->name('medcol3.desabastecidos')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/anulados', 'Medcol3\PendienteApiMedcol3Controller@getAnulados')->name('medcol3.anulados')->middleware('verified')->middleware('verifyuser');
Route::get('medcol3/guardar_observacion', 'Medcol3\PendienteApiMedcol3Controller@guardar')->name('medcol3.guardar_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/editpendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@edit')->name('medcol3.pendientes-edit')->middleware('verified')->middleware('verifyuser');
Route::get('medcol3/showpendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@show')->name('medcol3.pendientes-show')->middleware('verified')->middleware('verifyuser');
Route::put('medcol3/pendientes/{id}', 'Medcol3\PendienteApiMedcol3Controller@update')->name('medcol3.actualizar_pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/pendientes', 'Medcol3\PendienteApiMedcol3Controller@saveObs')->name('medcol3.crear_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/observaciones', 'Medcol3\PendienteApiMedcol3Controller@getObservaciones')->name('medcol3.observaciones')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/syncapi', 'Medcol3\PendienteApiMedcol3Controller@createapendientespi')->name('medcol3.syncapi')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/informe', 'Medcol3\PendienteApiMedcol3Controller@informes')->name('medcol3.informe')->middleware('verified')->middleware('verifyuser');

Route::get('informepedientes3', 'Medcol3\PendienteApiMedcol3Controller@informepedientes')->name('informepedientes3')->middleware('verified')->middleware('verifyuser');


//Rutas de tablas de Dispensado MEDCOL 4

Route::get('medcol3/dispensado', 'Medcol3\DispensadoApiMedcol4Controller@index')->name('medcol3.dispensado')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/dispensado1', 'Medcol3\DispensadoApiMedcol4Controller@index1')->name('medcol3.dispensado1')->middleware('verified')->middleware('verifyuser');

Route::post('medcol3/disrevisado', 'Medcol3\DispensadoApiMedcol4Controller@disrevisado')->name('medcol3.disrevisado')->middleware('verified')->middleware('verifyuser');
Route::put('medcol3/dispensado/{id}', 'Medcol3\DispensadoApiMedcol4Controller@update')->name('medcol3.actualizar_dispensado')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/dispensado/syncdisapi', 'Medcol3\DispensadoApiMedcol4Controller@createdispensadoapi')->name('medcol3.dispensadosyncapi')->middleware('verified')->middleware('verifyuser');
Route::get('medcol3/dispensado/anuladosapi', 'Medcol3\DispensadoApiMedcol4Controller@updateanuladosapi')->name('medcol3.anuladosapi')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/add_dispensado', 'Medcol3\DispensadoApiMedcol4Controller@adddispensacionarray')->name('add_dispensacion')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/update', 'Medcol3\DispensadoApiMedcol4Controller@actualizarDispensacion')->name('dispensado.guardar')->middleware('verified')->middleware('verifyuser');

Route::get('medcol3/informedis', 'Medcol3\DispensadoApiMedcol4Controller@informes')->name('medcol3.informedis')->middleware('verified')->middleware('verifyuser');
Route::post('medcol3/disanulado', 'Medcol3\DispensadoApiMedcol4Controller@disanulado')->name('medcol3.disanulado')->middleware('verified')->middleware('verifyuser');

Route::get('buscar/{factura}', 'Medcol3\DispensadoApiMedcol4Controller@buscar')->name('dispensado.buscar')->middleware('verified')->middleware('verifyuser');



/*
**
**
*/

//Rutas de tablas de pendientes MEDCOL DOLOR

Route::get('medcold/pendientes', 'Medcold\PendienteApiMedcoldController@index')->name('medcold.pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/pendientes1', 'Medcold\PendienteApiMedcoldController@index1')->name('medcold.pendientes1')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/porentregar', 'Medcold\PendienteApiMedcoldController@porentregar')->name('medcold.porentregar')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/entregados', 'Medcold\PendienteApiMedcoldController@entregados')->name('medcold.entregados')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/desabastecidos', 'Medcold\PendienteApiMedcoldController@getDesabastecidos')->name('medcold.desabastecidos')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/anulados', 'Medcold\PendienteApiMedcoldController@getAnulados')->name('medcold.anulados')->middleware('verified')->middleware('verifyuser');
Route::get('medcold/guardar_observacion', 'Medcold\PendienteApiMedcoldController@guardar')->name('medcold.guardar_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcold/editpendientes/{id}', 'Medcold\PendienteApiMedcoldController@edit')->name('medcold.pendientes-edit')->middleware('verified')->middleware('verifyuser');
Route::get('medcold/showpendientes/{id}', 'Medcold\PendienteApiMedcoldController@show')->name('medcold.pendientes-show')->middleware('verified');
Route::put('medcold/pendientes/{id}', 'Medcold\PendienteApiMedcoldController@update')->name('medcold.actualizar_pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/pendientes', 'Medcold\PendienteApiMedcoldController@saveObs')->name('medcold.crear_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcold/observaciones', 'Medcold\PendienteApiMedcoldController@getObservaciones')->name('medcold.observaciones')->middleware('verified')->middleware('verifyuser');

Route::get('medcold/syncapi', 'Medcold\PendienteApiMedcoldController@createapendientespi')->name('medcold.syncapi')->middleware('verified')->middleware('verifyuser');

Route::get('medcold/informe', 'Medcold\PendienteApiMedcoldController@informes')->name('medcold.informe')->middleware('verified')->middleware('verifyuser');

Route::get('informepedientesd', 'Medcold\PendienteApiMedcoldController@informepedientes')->name('informepedientesd')->middleware('verified')->middleware('verifyuser');



//Rutas de tablas de Dispensado MEDCOL DOLOR

Route::get('medcold/dispensado', 'Medcold\DispensadoApiMedcoldController@index')->name('medcold.dispensado')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/dispensado1', 'Medcold\DispensadoApiMedcoldController@index1')->name('medcold.dispensado1')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/disrevisado', 'Medcold\DispensadoApiMedcoldController@disrevisado')->name('medcold.disrevisado')->middleware('verified')->middleware('verifyuser');

Route::put('medcold/dispensado/{id}', 'Medcold\DispensadoApiMedcoldController@update')->name('medcold.actualizar_dispensado')->middleware('verified')->middleware('verifyuser');
Route::get('medcold/dispensado/syncdisapi', 'Medcold\DispensadoApiMedcoldController@createdispensadoapi')->name('medcold.dispensadosyncapi')->middleware('verified')->middleware('verifyuser');
Route::get('medcold/dispensado/anuladosapi', 'Medcold\DispensadoApiMedcoldController@updateanuladosapi')->name('medcold.anuladosapi')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/add_dispensado', 'Medcold\DispensadoApiMedcoldController@adddispensacionarray')->name('add_dispensaciond')->middleware('verified')->middleware('verifyuser');

Route::get('medcold/informedis', 'Medcold\DispensadoApiMedcoldController@informes')->name('medcold.informedis')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/disanulado', 'Medcold\DispensadoApiMedcoldController@disanulado')->name('medcold.disanulado')->middleware('verified')->middleware('verifyuser');
Route::post('medcold/update', 'Medcold\DispensadoApiMedcoldController@actualizarDispensacion')->name('dispensadod.guardar')->middleware('verified')->middleware('verifyuser');

Route::get('buscar/{factura}', 'Medcold\DispensadoApiMedcoldController@buscar')->name('dispensadod.buscar')->middleware('verified')->middleware('verifyuser');

//Rutas de views de pendientes MEDCOL CLIENTES

Route::get('medcolcli/pendientes', 'Medcolcli\PendienteMedcolCliController@index')->name('medcolCli.pendientes')->middleware('verified');
Route::post('medcolcli/pendientes1', 'Medcolcli\PendienteMedcolCliController@index1')->name('medcolCli.pendientes1')->middleware('verified');

Route::get('medcolcli/dispensado', 'Medcolcli\DispensadoMedcolCliController@index')->name('medcolCli.dispensado')->middleware('verified');
Route::post('medcolcli/dispensado1', 'Medcolcli\DispensadoMedcolCliController@index1')->name('medcolCli.dispensado1')->middleware('verified');

Route::get('selectcie10', 'DiagnosticosCie10Controller@selectcie10')->name('selectcie10')->middleware('verified');



//RUTA PARA LISTAS 

Route::get('/listas-index', 'Listas\ListasController@index')->name('listasIndex')->middleware('verified')->middleware('verifyuser');
Route::post('/crear-listas', 'Listas\ListasController@store')->name('crearlistas')->middleware('verified')->middleware('verifyuser');
Route::get('/editar-listas/{id}', 'Listas\ListasController@show')->name('editar-listas')->middleware('verified')->middleware('verifyuser');
Route::put('/actualizar-listas/{id}', 'Listas\ListasController@update')->name('actualizar-listas')->middleware('verified')->middleware('verifyuser');
Route::delete('/borrar-listas/{id}', 'Listas\ListasController@destroy')->name('borrar-listas')->middleware('verified')->middleware('verifyuser');

Route::post('/listas-estado', 'Listas\ListasController@updateestado')->name('lisestado')->middleware('verified')->middleware('verifyuser');

//RUTA PARA LISTAS DETALLE 

Route::get('/detallelistas', 'Listas\ListasDetalleController@indexDetalle')->name('listasdetalledetalle')->middleware('verified')->middleware('verifyuser');
Route::post('/detallecrear-listas', 'Listas\ListasDetalleController@store')->name('crearlistasdetalle')->middleware('verified')->middleware('verifyuser');
Route::get('/detalleeditar-listas/{id}', 'Listas\ListasDetalleController@show')->name('editar-listasdetalle')->middleware('verified')->middleware('verifyuser');
Route::put('/detalleactualizar-listas/{id}', 'Listas\ListasDetalleController@update')->name('actualizar-listasdetalle')->middleware('verified')->middleware('verifyuser');
Route::delete('/detalleborrar-listas/{id}', 'Listas\ListasDetalleController@destroy')->name('borrar-listasdetalle')->middleware('verified')->middleware('verifyuser');

Route::post('/detalle-estado', 'Listas\ListasDetalleController@updateestado')->name('detestado')->middleware('verified')->middleware('verifyuser');




//SELECT DE LISTAS

route::get('selectlist', 'Listas\ListasDetalleController@select')->name('selectlist')->middleware('verified')->middleware('verifyuser');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Medcol6\ExportarExcel\ExportController;
use App\Http\Controllers\Scann\ScannController;
use App\Http\Controllers\Medcol6\OrdenCompraApiMedcol6Controller;


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
    return redirect()->route('dashboard');
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
Route::get('usuario/{id}/editar', 'Auth\RegisterController@editar')->name('usuarioeditar')->middleware('verified')->middleware('verifyuser');
Route::put('usuarioupdate/{id}', 'Auth\RegisterController@actualizar')->name('usuarioupdate')->middleware('verified')->middleware('verifyuser');


// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
Route::emailVerification();

//Auth::routes(['verify' => true, 'verifyuser' => true]);

//Rutas del Dashboard
Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('verified')->middleware('verifyuser');
Route::get('/dashboard/estadisticas-ajax', 'DashboardController@getEstadisticasAjax')->name('dashboard.estadisticas-ajax')->middleware('verified')->middleware('verifyuser');
Route::get('/dashboard/top-medicamentos-datatable', 'DashboardController@getTopMedicamentosDataTable')->name('dashboard.top-medicamentos-datatable')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo direccionado por la EPS
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo direccionado por la EPS
Route::get('/homeapi', 'HomeController@index')->name('homeapi')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo direccionado por la EPS por documento
Route::get('/direccionado', 'HomeController@direccionado')->name('direccionado')->middleware('verified')->middleware('verifyuser');

//Ruta que se usa para enviar por el servicio put a programar las prescripciones desde la function index
Route::post('/programar', 'HomeController@Programarm')->name('programar')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo programado por el regente o administrador
Route::get('/programado', 'HomeController@indexp')->name('programado')->middleware('verified')->middleware('verifyuser');

//Ruta para consultar lo programado por el regente o administrador
Route::post('/programado1', 'HomeController@indexp')->name('programado1')->middleware('verified')->middleware('verifyuser');

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
Route::get('/menucompras', 'MenuContoller@index2')->name('comprmenu')->middleware('verified');
Route::get('/menucotizaciones', 'MenuContoller@index3')->name('menucotizaciones')->middleware('verified');
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
Route::get('medcol2/pendientes/anuladosapi', 'PendienteApiController@updateanuladosapi')->name('medcol2.pendientesanulados')->middleware('verified')->middleware('verifyuser');


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


Route::get('buscar-medcol2/{factura}', 'Medcol2\\DispensadoApiMedcol2Controller@buscar')->name('dispensado.medcol2')->middleware('verified')->middleware('verifyuser');

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
Route::get('medcol3/pendientes/anuladosapi', 'Medcol3\PendienteApiMedcol3Controller@updateanuladosapi')->name('medcol3.pendientesanulados')->middleware('verified')->middleware('verifyuser');

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

Route::get('buscar-medcol3/{factura}', 'Medcol3\\DispensadoApiMedcol4Controller@buscar')->name('dispensado.medcol3')->middleware('verified')->middleware('verifyuser');



//Rutas de tablas de pendientes MEDCOL 5 - EMCALI

Route::get('medcol5/pendientes', 'Medcol5\PendienteApiMedcol5Controller@index')->name('medcol5.pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/pendientes1', 'Medcol5\PendienteApiMedcol5Controller@index1')->name('medcol5.pendientes1')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/porentregar', 'Medcol5\PendienteApiMedcol5Controller@porentregar')->name('medcol5.porentregar')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/entregados', 'Medcol5\PendienteApiMedcol5Controller@entregados')->name('medcol5.entregados')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/desabastecidos', 'Medcol5\PendienteApiMedcol5Controller@getDesabastecidos')->name('medcol5.desabastecidos')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/anulados', 'Medcol5\PendienteApiMedcol5Controller@getAnulados')->name('medcol5.anulados')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/vencidos', 'Medcol5\PendienteApiMedcol5Controller@getVencidos')->name('medcol5.vencidos')->middleware('verified')->middleware('verifyuser');
Route::get('medcol5/guardar_observacion', 'Medcol5\PendienteApiMedcol5Controller@guardar')->name('medcol5.guardar_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/editpendientes/{id}', 'Medcol5\PendienteApiMedcol5Controller@edit')->name('medcol5.pendientes-edit')->middleware('verified')->middleware('verifyuser');
Route::get('medcol5/showpendientes/{id}', 'Medcol5\PendienteApiMedcol5Controller@show')->name('medcol5.pendientes-show')->middleware('verified')->middleware('verifyuser');
Route::put('medcol5/pendientes/{id}', 'Medcol5\PendienteApiMedcol5Controller@update')->name('medcol5.actualizar_pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/pendientes', 'Medcol5\PendienteApiMedcol5Controller@saveObs')->name('medcol5.crear_observacion')->middleware('verified')->middleware('verifyuser');
Route::get('medcol5/pendientes/anuladosapi', 'Medcol5\PendienteApiMedcol5Controller@updateanuladosapi')->name('medcol5.pendientesanulados')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/observaciones', 'Medcol5\PendienteApiMedcol5Controller@getObservaciones')->name('medcol5.observaciones')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/syncapi', 'Medcol5\PendienteApiMedcol5Controller@createapendientespi')->name('medcol5.syncapi')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/informe', 'Medcol5\PendienteApiMedcol5Controller@informes')->name('medcol5.informe')->middleware('verified')->middleware('verifyuser');

Route::get('informepedientes5', 'Medcol5\PendienteApiMedcol5Controller@informepedientes')->name('informepedientes5')->middleware('verified')->middleware('verifyuser');


//Rutas de tablas de Dispensado MEDCOL 5 - EMCALI

Route::get('medcol5/dispensado', 'Medcol5\DispensadoApiMedcol5Controller@index')->name('medcol5.dispensado')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/dispensado1', 'Medcol5\DispensadoApiMedcol5Controller@index1')->name('medcol5.dispensado1')->middleware('verified')->middleware('verifyuser');

Route::post('medcol5/disrevisado', 'Medcol5\DispensadoApiMedcol5Controller@disrevisado')->name('medcol5.disrevisado')->middleware('verified')->middleware('verifyuser');
Route::put('medcol5/dispensado/{id}', 'Medcol5\DispensadoApiMedcol5Controller@update')->name('medcol5.actualizar_dispensado')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/dispensado/syncdisapi', 'Medcol5\DispensadoApiMedcol5Controller@createdispensadoapi')->name('medcol5.dispensadosyncapi')->middleware('verified')->middleware('verifyuser');
Route::get('medcol5/dispensado/anuladosapi', 'Medcol5\DispensadoApiMedcol5Controller@updateanuladosapi')->name('medcol5.anuladosapi')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/add_dispensado', 'Medcol5\DispensadoApiMedcol5Controller@adddispensacionarray')->name('medcol5.add_dispensacion')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/update', 'Medcol5\DispensadoApiMedcol5Controller@actualizarDispensacion')->name('dispensado5.guardar')->middleware('verified')->middleware('verifyuser');

Route::get('medcol5/informedis', 'Medcol5\DispensadoApiMedcol5Controller@informes')->name('medcol5.informedis')->middleware('verified')->middleware('verifyuser');
Route::post('medcol5/disanulado', 'Medcol5\DispensadoApiMedcol5Controller@disanulado')->name('medcol5.disanulado')->middleware('verified')->middleware('verifyuser');

Route::get('buscar-medcol5/{factura}', 'Medcol5\\DispensadoApiMedcol5Controller@buscar')->name('dispensado.medcol5')->middleware('verified')->middleware('verifyuser');


//Rutas de tablas de pendientes MEDCOL 6 SOS y JAMUNDI

Route::get('medcol6/pendientes', 'Medcol6\PendienteApiMedcol6Controller@index')->name('medcol6.pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/pendientes1', 'Medcol6\PendienteApiMedcol6Controller@index1')->name('medcol6.pendientes1')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/porentregar', 'Medcol6\PendienteApiMedcol6Controller@porentregar')->name('medcol6.porentregar')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/entregados', 'Medcol6\PendienteApiMedcol6Controller@entregados')->name('medcol6.entregados')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/sincontacto', 'Medcol6\PendienteApiMedcol6Controller@sincontacto')->name('medcol6.sincontacto')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/desabastecidos', 'Medcol6\PendienteApiMedcol6Controller@getDesabastecidos')->name('medcol6.desabastecidos')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/anulados', 'Medcol6\PendienteApiMedcol6Controller@getAnulados')->name('medcol6.anulados')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/vencidos', 'Medcol6\PendienteApiMedcol6Controller@getVencidos')->name('medcol6.vencidos')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/guardar_observacion', 'Medcol6\PendienteApiMedcol6Controller@guardarObservacion')->name('medcol6.guardar_observacion')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/editpendientes/{id}', 'Medcol6\PendienteApiMedcol6Controller@edit')->name('medcol6.pendientes-edit')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/showpendientes/{id}', 'Medcol6\PendienteApiMedcol6Controller@show')->name('medcol6.pendientes-show')->middleware('verified')->middleware('verifyuser');
Route::put('medcol6/pendientes/{id}', 'Medcol6\PendienteApiMedcol6Controller@update')->name('medcol6.actualizar_pendientes')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/pendientes', 'Medcol6\PendienteApiMedcol6Controller@saveObs')->name('medcol6.crear_observacion')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/pendientes/anuladosapi', 'Medcol6\PendienteApiMedcol6Controller@updateanuladosapi')->name('medcol6.pendientesanulados')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/pendientes/getreport', 'Medcol6\PendienteApiMedcol6Controller@getreport')->name('medcol6.getreport')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/pendientes/pendientes-vs-saldos', 'Medcol6\PendienteApiMedcol6Controller@informePendientesVsSaldos')->name('medcol6.pendientes_vs_saldos')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/pendientes/medicamentos-farmacia', 'Medcol6\PendienteApiMedcol6Controller@getMedicamentosPorFarmacia')
    ->name('medcol6.getMedicamentosPorFarmacia')
    ->middleware('verified')
    ->middleware('verifyuser');
Route::post('medcol6/saldo-medicamento', 'Medcol6\PendienteApiMedcol6Controller@getSaldoMedicamento')->name('medcol6.saldo_medicamento')->middleware('verified')->middleware('verifyuser');


Route::get('medcol6/observaciones', 'Medcol6\PendienteApiMedcol6Controller@getObservaciones')->name('medcol6.observaciones')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/syncapi', 'Medcol6\PendienteApiMedcol6Controller@createapendientespi')->name('medcol6.syncapi')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/informe', 'Medcol6\PendienteApiMedcol6Controller@informes')->name('medcol6.informe')->middleware('verified')->middleware('verifyuser');

Route::get('informepedientes6', 'Medcol6\PendienteApiMedcol6Controller@informepedientes')->name('informepedientes6')->middleware('verified')->middleware('verifyuser');


// Nuevas rutas para gestión de pendientes por paciente
Route::post('medcol6/pendientes-por-paciente', 'Medcol6\PendienteApiMedcol6Controller@getPendientesPorPaciente')->name('medcol6.pendientes_por_paciente')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/pendientes-paciente-detalle', 'Medcol6\PendienteApiMedcol6Controller@getPendientesPacienteDetalle')->name('medcol6.pendientes_paciente_detalle')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/update-multiples-pendientes', 'Medcol6\PendienteApiMedcol6Controller@updateMultiplesPendientes')->name('medcol6.update_multiples_pendientes')->middleware('verified')->middleware('verifyuser');

// Rutas para gestión de pendientes - validación y entrega
Route::post('medcol6/buscar-validacion', 'Medcol6\PendienteApiMedcol6Controller@buscarValidacion')->name('medcol6.buscar_validacion')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/procesar-entregas', 'Medcol6\PendienteApiMedcol6Controller@procesarEntregas')->name('medcol6.procesar_entregas')->middleware('verified')->middleware('verifyuser');


//Rutas de tablas de Dispensado MEDCOL 6 SOS y JAMUNDI

Route::get('medcol6/dispensado', 'Medcol6\DispensadoApiMedcol6Controller@index')->name('medcol6.dispensado')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/dispensado1', 'Medcol6\DispensadoApiMedcol6Controller@index1')->name('medcol6.dispensado1')->middleware('verified')->middleware('verifyuser');

Route::post('medcol6/disrevisado', 'Medcol6\DispensadoApiMedcol6Controller@disrevisado')->name('medcol6.disrevisado')->middleware('verified')->middleware('verifyuser');
Route::put('medcol6/dispensado/{id}', 'Medcol6\DispensadoApiMedcol6Controller@update')->name('medcol6.actualizar_dispensado')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/dispensado/syncdisapi', 'Medcol6\DispensadoApiMedcol6Controller@createdispensadoapi')->name('medcol6.dispensadosyncapi')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/dispensado/syncdisapiunico', 'Medcol6\DispensadoApiMedcol6Controller@createdispensadoapiunico')->name('medcol6.dispensadosyncapiunico')->middleware('verified')->middleware('verifyuser');


Route::get('medcol6/dispensado/anuladosapi', 'Medcol6\DispensadoApiMedcol6Controller@updateanuladosapi')->name('medcol6.anuladosapi')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/add_dispensado', 'Medcol6\DispensadoApiMedcol6Controller@adddispensacionarray')->name('medcol6.add_dispensacion')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/update', 'Medcol6\DispensadoApiMedcol6Controller@actualizarDispensacion')->name('dispensado6.guardar')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/showdispensado/{id}', 'Medcol6\DispensadoApiMedcol6Controller@showdis')->name('medcol6.dispensado-show')->middleware('verified')->middleware('verifyuser');
Route::put('medcol6/editdispensado/{id}', 'Medcol6\DispensadoApiMedcol6Controller@updatedis')->name('medcol6.dispensado_update')->middleware('verified')->middleware('verifyuser');

Route::get('medcol6/informedis', 'Medcol6\DispensadoApiMedcol6Controller@informes')->name('medcol6.informedis')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/gestionsdis', 'Medcol6\DispensadoApiMedcol6Controller@gestionsdis')->name('medcol6.gestionsdis')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/forgif', 'Medcol6\DispensadoApiMedcol6Controller@gestionForgif')->name('medcol6.forgif')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/disanulado', 'Medcol6\DispensadoApiMedcol6Controller@disanulado')->name('medcol6.disanulado')->middleware('verified')->middleware('verifyuser');

Route::get('buscar-medcol6/{factura}', 'Medcol6\DispensadoApiMedcol6Controller@buscar')->name('dispensado.medcol6')->middleware('verified')->middleware('verifyuser');

Route::post('medcol6/exportar-excel', [ExportController::class, 'exportExcel'])->name('exportar.excel');

//Rutas para gestión de saldos MEDCOL 6
Route::get('medcol6/saldos', 'Medcol6\SaldosMedcol6Controller@index')->name('medcol6.saldos')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/saldos/data', 'Medcol6\SaldosMedcol6Controller@getSaldos')->name('medcol6.saldos.data')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/saldos/sincronizar', 'Medcol6\SaldosMedcol6Controller@sincronizarSaldos')->name('medcol6.saldos.sincronizar')->middleware('verified')->middleware('verifyuser');
Route::post('medcol6/saldos/probar-api', 'Medcol6\SaldosMedcol6Controller@probarApi')->name('medcol6.saldos.probar-api')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/saldos/estadisticas', 'Medcol6\SaldosMedcol6Controller@getEstadisticas')->name('medcol6.saldos.estadisticas')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/saldos/filtros', 'Medcol6\SaldosMedcol6Controller@getOpcionesFiltros')->name('medcol6.saldos.filtros')->middleware('verified')->middleware('verifyuser');
Route::get('medcol6/saldos/{id}', 'Medcol6\SaldosMedcol6Controller@show')->name('medcol6.saldos.show')->middleware('verified')->middleware('verifyuser');



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
    
    Route::get('buscar-medcold/{factura}', 'Medcold\\DispensadoApiMedcoldController@buscar')->name('dispensado.medcold')->middleware('verified')->middleware('verifyuser');
    
    
    // ruta de terceros y medicamentos MEDCOL 3
    
    Route::get('medcol3/medicamentos/syncmedicamentosapi', 'Compras\Medcol3\ControllerMedcol3@createmedicamentosapi')->name('medcol3.syncmedicamentosapi')->middleware('verified')->middleware('verifyuser');
    Route::get('medcol3/terceros/synctercerosapi', 'Compras\Medcol3\ControllerMedcol3@createterceroapi')->name('medcol3.synctercerosapi')->middleware('verified')->middleware('verifyuser');
    
    
    
    //Rutas de tablas de compras MEDCOL 2
    Route::get('medcol2/compras', 'Compras\Medcol2\ControllerMedcol2@index')->name('compras.medcol2')->middleware('verified')->middleware('verifyuser');
    
    Route::get('selectarticulo2', 'Compras\Medcol2\ControllerMedcol2@articulos')->name('selectarticulo2')->middleware('verified');
     Route::get('selectarticulo3add', 'Compras\Medcol3\ControllerMedcol3@articulosadd')->name('selectarticulo3add')->middleware('verified');
    Route::get('proveedoreslist2', 'Compras\Medcol2\ControllerMedcol2@proveedores')->name('proveedoreslist2')->middleware('verified');
    Route::get('documentoslist2', 'Compras\Medcol2\ControllerMedcol2@documentos')->name('documentoslist2')->middleware('verified');
    Route::get('/detalledocumento2/{id}', 'Compras\Medcol2\ControllerMedcol2@consecutivo')->name('detalledocumento2')->middleware('verified');
    Route::get('comprasli2', 'Compras\Medcol2\ControllerMedcol2@Ordcompras')->name('comprasli2')->middleware('verified');
    Route::get('compras_store2', 'Compras\Medcol2\ControllerMedcol2@Ordcompras')->name('compras_store2')->middleware('verified');
    Route::get('proveedores_store2', 'Compras\Medcol2\ControllerMedcol2@Ordcompras')->name('proveedores_store2')->middleware('verified');
    Route::get('detalleproveedores2/{id}', 'Compras\Medcol2\ControllerMedcol2@showproveedor')->name('proveedor2')->middleware('verified')->middleware('verifyuser');
    
    
    //Rutas de tablas de compras MEDCOL 3
    Route::get('medcol3/compras', 'Compras\Medcol3\ControllerMedcol3@index')->name('compras.medcol3');
    Route::get('selectarticulo3add', 'Compras\Medcol3\ControllerMedcol3@articulosadd')->name('selectarticulo3add')->middleware('verified');
    Route::get('/articulo/{codigo}', 'Compras\Medcol3\ControllerMedcol3@obtenerArticulo')->name('articulos.obtener');
    Route::get('selectarticulo3', 'Compras\Medcol3\ControllerMedcol3@articulos')->name('selectarticulo3')->middleware('verified');
    Route::get('detallearticulos3/{id}', 'Compras\Medcol3\ControllerMedcol3@showarticulos')->name('detallearticulos3')->middleware('verified')->middleware('verifyuser');
    Route::get('proveedoreslist3', 'Compras\Medcol3\ControllerMedcol3@proveedores')->name('proveedoreslist3')->middleware('verified')->middleware('verifyuser');
    Route::get('documentoslist3', 'Compras\Medcol3\ControllerMedcol3@documentos')->name('documentoslist3')->middleware('verified')->middleware('verifyuser');
    Route::get('detalledocumento3/{id}', 'Compras\Medcol3\ControllerMedcol3@consecutivo')->name('detalledocumento3')->middleware('verified')->middleware('verifyuser');
    Route::post('entradasstore3', 'Compras\Medcol3\ControllerMedcol3@guardarDetalles')->name('entradasstore_3')->middleware('verified')->middleware('verifyuser');
    Route::post('import_archivo3', 'Compras\Medcol3\ControllerMedcol3@importOrders')->name('importarchivo3')->middleware('verified')->middleware('verifyuser');
    
    
    Route::get('comprasli3', 'Compras\Medcol3\ControllerMedcol3@Ordcompras')->name('comprasli3')->middleware('verified');
    Route::get('compras_store3', 'Compras\Medcol3\ControllerMedcol3@Ordcompras')->name('compras_store3')->middleware('verified');
    Route::get('proveedores_store3', 'Compras\Medcol3\ControllerMedcol3@Ordcompras')->name('proveedores_store3')->middleware('verified');
    Route::get('detalleproveedores3/{id}', 'Compras\Medcol3\ControllerMedcol3@showproveedor')->name('proveedor3')->middleware('verified')->middleware('verifyuser');
    
    
    //Rutas de tablas de compras MEDCOL 4
    Route::get('medcol4/compras', 'Compras\Medcol4\ControllerMedcol4@index')->name('compras.medcol4')->middleware('verified')->middleware('verifyuser');
    
    Route::get('selectarticulo4', 'Compras\Medcol4\ControllerMedcol4@articulos')->name('selectarticulo4')->middleware('verified');
    Route::get('proveedoreslist4', 'Compras\Medcol4\ControllerMedcol4@proveedores')->name('proveedoreslist4')->middleware('verified');
    Route::get('documentoslist4', 'Compras\Medcol4\ControllerMedcol4@documentos')->name('documentoslist4')->middleware('verified');
    Route::get('/detalledocumento4/{id}', 'Compras\Medcol4\ControllerMedcol4@consecutivo')->name('detalledocumento4')->middleware('verified');
    Route::get('comprasli4', 'Compras\Medcol4\ControllerMedcol4@Ordcompras')->name('comprasli4')->middleware('verified');
    Route::get('compras_store4', 'Compras\Medcol4\ControllerMedcol4@Ordcompras')->name('compras_store4')->middleware('verified');
    Route::get('proveedores_store4', 'Compras\Medcol4\ControllerMedcol4@Ordcompras')->name('proveedores_store4')->middleware('verified');
    Route::get('detalleproveedores4/{id}', 'Compras\Medcol4\ControllerMedcol4@showproveedor')->name('proveedor4')->middleware('verified')->middleware('verifyuser');
    
    //Rutas de tablas de cotizaciones MEDCOL 2
    Route::get('medcol2/cotizaciones', 'Compras\Medcol2\ControllerMedcolCotizaciones2@indexCotizaciones')->name('medcol2.listascotizaciones')->middleware('verified')->middleware('verifyuser');
    Route::post('subir_archivo2', 'Compras\Medcol2\ControllerMedcolCotizaciones2@import')->name('subirarchivo2')->middleware('verified')->middleware('verifyuser');
    Route::get('medcol2/detallecotizaciones', 'Compras\Medcol2\ControllerMedcolCotizaciones2@indexDetalleCotizaciones')->name('medcol2.indexDetalleCotizaciones')->middleware('verified')->middleware('verifyuser');
    
    
    //Rutas de tablas de cotizaciones MEDCOL 3
    Route::get('medcol3/cotizaciones', 'Compras\Medcol3\ControllerMedcolCotizaciones3@indexCotizaciones')->name('medcol3.listascotizaciones')->middleware('verified')->middleware('verifyuser');
    Route::post('subir_archivo3', 'Compras\Medcol3\ControllerMedcolCotizaciones3@import')->name('subirarchivo3')->middleware('verified')->middleware('verifyuser');
    Route::get('medcol3/detallecotizaciones1', 'Compras\Medcol3\ControllerMedcolCotizaciones3@indexDetalleCotizaciones')->name('medcol3.indexDetalleCotizaciones1')->middleware('verified')->middleware('verifyuser');
    Route::get('detallecotizaciones3/{id}', 'Compras\Medcol3\ControllerMedcolCotizaciones3@show')->name('indexDetalleCotizaciones3')->middleware('verified')->middleware('verifyuser');
  
    
    
    //Rutas de tablas de cotizaciones MEDCOL 4
    Route::get('medcol4/cotizaciones', 'Compras\Medcol4\ControllerMedcolCotizaciones4@indexCotizaciones')->name('medcol4.listascotizaciones')->middleware('verified')->middleware('verifyuser');
    Route::post('subir_archivo4', 'Compras\Medcol4\ControllerMedcolCotizaciones2@import')->name('subirarchivo4')->middleware('verified')->middleware('verifyuser');
    Route::get('medcol4/detallecotizaciones', 'Compras\Medcol4\ControllerMedcolCotizaciones4@indexDetalleCotizaciones')->name('medcol4.indexDetalleCotizaciones')->middleware('verified')->middleware('verifyuser');

    
    
     //Rutas de views de pendientes MEDCOL CLIENTES

    Route::get('medcolcli/pendientes', 'Medcolcli\PendienteMedcolCliController@index')->name('medcolCli.pendientes')->middleware('verified');
    Route::post('medcolcli/pendientes1', 'Medcolcli\PendienteMedcolCliController@index1')->name('medcolCli.pendientes1')->middleware('verified');
    
    Route::get('medcolcli/dispensado', 'Medcolcli\DispensadoMedcolCliController@index')->name('medcolCli.dispensado')->middleware('verified');
    Route::post('medcolcli/dispensado1', 'Medcolcli\DispensadoMedcolCliController@index1')->name('medcolCli.dispensado1')->middleware('verified');
    Route::get('medcolcli/drogueria', 'Medcolcli\DispensadoMedcolCliController@drogueria')->name('medcolCli.drogueria')->middleware('verified');
    
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
    route::get('selectcont', 'Listas\ListasDetalleController@selectCont')->name('selectcont')->middleware('verified')->middleware('verifyuser');
    
    //DOCUMENTOS CONSECUTIVOS
    
    Route::get('/documentos', 'Compras\ControllerDocumentos@index')->name('documentos')->middleware('verifyuser');
    //Route::post('/documentos1', 'Compras\ControllerDocumentos@index')->name('documentos')->middleware('verifyuser');
    Route::post('/crear-documentos', 'Compras\ControllerDocumentos@store')->name('creardocumento')->middleware('verifyuser');
    Route::get('/editar-documentos/{id}', 'Compras\ControllerDocumentos@show')->name('editar-documentos')->middleware('verifyuser');
    Route::put('/actualizar-documentos/{id}', 'Compras\ControllerDocumentos@update')->name('actualizar-documentos')->middleware('verifyuser');
    Route::delete('/borrar-documentos/{id}', 'Compras\ControllerDocumentos@destroy')->name('borrar-documentos')->middleware('verifyuser');
    
    Route::get('/documentoslist','Compras\ControllerDocumentos@select')->name('documentoslist')->middleware('verifyuser');
    Route::get('/detalledocumento/{id}', 'Compras\ControllerDocumentos@consecutivo')->name('detalledocumento')->middleware('verifyuser');
    
    
    //SERVICIOS WEB SOS
    
    //SERVICIO SOAP DE CONSULTA USUARIO
    
    Route::get('/indexSosValidarDerechos', 'MedcolSos\DerechosSosController@index')->name('indexsos')->middleware('verified')->middleware('verifyuser');
    Route::post('/dataValidarDerechos', 'MedcolSos\DerechosSosController@consultarAfiliado')->name('dataSos')->middleware('verified')->middleware('verifyuser');
    Route::post('/dataValidarDerechos1', 'MedcolSos\DerechosSosController@consultarAfiliadoMasivo')->name('dataSos1')->middleware('verified')->middleware('verifyuser');
    
    //SERVICIO REST DE CONSULTA FORMULAS
    
    Route::get('/indexSosFormulasSos', 'MedcolSos\OrdenesSosController@index')->name('indexformulas')->middleware('verified')->middleware('verifyuser');
    Route::post('/dataFormulasSos', 'MedcolSos\OrdenesSosController@formulasAfiliado')->name('dataFormulasSos')->middleware('verified')->middleware('verifyuser');
    
    
    Route::get('/indexScann', [ScannController::class, 'Index'])->name('indexscann')->middleware('verified')->middleware('verifyuser');
    Route::get('/indexDetalleScann', [ScannController::class, 'detalleScann'])->name('indexDetalleScann')->middleware('verified')->middleware('verifyuser');
    Route::get('/imagenes', [ScannController::class, 'listarImagenes'])->name('imagenes.listar')->middleware('verified')->middleware('verifyuser');
    Route::post('/subirImagenes', [ScannController::class, 'subirImagenes'])->name('uploadScann')->middleware('verified')->middleware('verifyuser');
    Route::post('/mover-imagen', [ScannController::class, 'moverImagen'])->name('mover.imagen')->middleware('verified')->middleware('verifyuser');
    Route::delete('/ordenes/{id}', [ScannController::class, 'destroy'])->middleware('verified')->middleware('verifyuser');
    Route::get('/detalleScann/{id}', [ScannController::class, 'detalleScann'])->name('detalleScann')->middleware(['verified', 'verifyuser']);
    
    Route::get('/indexScannPdfs', [ScannController::class, 'Index2'])->name('indexscannpdfs')->middleware('verified')->middleware('verifyuser');
    
    Route::get('/scann/filtro', [ScannController::class, 'filtrarComprobantes'])->name('scann.filtro');
    Route::get('/scann/exportar-pdf', [ScannController::class, 'exportarPDF'])->name('exportar.pdf');

    
     //Compras
    Route::get('/ordenes', [OrdenCompraApiMedcol6Controller::class, 'index'])->name('ordenes.index');
    Route::get('/medcol3/ordenes/{id}/detalle', [OrdenCompraApiMedcol6Controller::class, 'detalle'])->name('ordenes.detalle');
    Route::get('informeTarjetasCompras', [OrdenCompraApiMedcol6Controller::class, 'resumenOrdenesCompra'])->name('ordenes.resumen');
    Route::get('BuscarOrdenesDeCompra', [OrdenCompraApiMedcol6Controller::class, 'listarOrdenesCompra'])->name('buscar.ordenes.compra');
    Route::post('/orden/actualizar-detalle', [OrdenCompraApiMedcol6Controller::class, 'actualizarDetalle'])->name('orden.actualizarDetalle');
    Route::post('/orden/actualizar-estado', [OrdenCompraApiMedcol6Controller::class, 'actualizarEstado'])->name('orden.actualizarEstado');
    Route::post('/guardar-comentario', [OrdenCompraApiMedcol6Controller::class, 'guardarComentario'])->name('guardar.comentario');
    Route::get('/ordenes/{orden}/editar', [OrdenCompraApiMedcol6Controller::class, 'edit'])->name('ordenes.editar');
    Route::put('/ordenes/{orden}', [OrdenCompraApiMedcol6Controller::class, 'update'])->name('ordenes.update');
    Route::delete('/ordenes/{id}', [OrdenCompraApiMedcol6Controller::class, 'destroy'])->name('ordenes.destroy');
    Route::delete('/detalleOrdenes/{id}', [OrdenCompraApiMedcol6Controller::class, 'destroyDetalleOrdenes'])->name('detalleOrdenes.destroy');
    Route::get('/ordenesDetalles/{detalle}/editar', [OrdenCompraApiMedcol6Controller::class, 'editDetalle'])->name('ordenesDetalle.editar');
    Route::put('/ordenesDetalles/{detalle}', [OrdenCompraApiMedcol6Controller::class, 'updateDetalles'])->name('ordenesDetalle.update');
    Route::get('/ordenes/{numeroOrden}/pdf', [OrdenCompraApiMedcol6Controller::class, 'exportarPDF'])->name('ordenes.exportar.pdf');
    Route::delete('/ordenesDetalle/{id}', [OrdenCompraApiMedcol6Controller::class, 'destroyDetallesAjax'])->name('ordenesDetalle.eliminar');
    Route::post('/guardar-factura', [OrdenCompraApiMedcol6Controller::class, 'guardarFactura'])->name('guardar.factura');
    Route::post('/guardar-articulo', [OrdenCompraApiMedcol6Controller::class, 'AgregarMolecula'])->name('guardar.articulo');

    // Smart Pendi Routes - Predictive Analysis Module
    Route::get('/smart/pendi', 'SmartPendiController@index')->name('smart.pendi')->middleware('verified')->middleware('verifyuser');
    Route::get('/smart/pendi/analysis', 'SmartPendiController@getPendientesAnalysis')->name('smart.pendi.analysis')->middleware('verified')->middleware('verifyuser');
    Route::get('/smart/pendi/suggestions', 'SmartPendiController@getPredictiveSuggestions')->name('smart.pendi.suggestions')->middleware('verified')->middleware('verifyuser');
    Route::get('/smart/pendi/statistics', 'SmartPendiController@getStatistics')->name('smart.pendi.statistics')->middleware('verified')->middleware('verifyuser');
    Route::get('/smart/pendi/summary', 'SmartPendiController@getSummary')->name('smart.pendi.summary')->middleware('verified')->middleware('verifyuser');

    // Análisis NT Routes - Gestión de Medicamentos por Contrato/Nota Técnica
    Route::get('/analisis-nt', 'AnalisisNtController@index')->name('analisis-nt.index')->middleware('verified')->middleware('verifyuser');
    Route::get('/analisis-nt/create', 'AnalisisNtController@create')->name('analisis-nt.create')->middleware('verified')->middleware('verifyuser');
    Route::post('/analisis-nt', 'AnalisisNtController@store')->name('analisis-nt.store')->middleware('verified')->middleware('verifyuser');
    Route::get('/analisis-nt/{id}', 'AnalisisNtController@show')->name('analisis-nt.show')->middleware('verified')->middleware('verifyuser');
    Route::get('/analisis-nt/{id}/edit', 'AnalisisNtController@edit')->name('analisis-nt.edit')->middleware('verified')->middleware('verifyuser');
    Route::put('/analisis-nt/{id}', 'AnalisisNtController@update')->name('analisis-nt.update')->middleware('verified')->middleware('verifyuser');
    Route::delete('/analisis-nt/{id}', 'AnalisisNtController@destroy')->name('analisis-nt.destroy')->middleware('verified')->middleware('verifyuser');
    Route::post('/analisis-nt/import', 'AnalisisNtController@importExcel')->name('analisis-nt.import')->middleware('verified')->middleware('verifyuser');
    Route::get('/analisis-nt/datatable/data', 'AnalisisNtController@getDataTable')->name('analisis-nt.datatable')->middleware('verified')->middleware('verifyuser');

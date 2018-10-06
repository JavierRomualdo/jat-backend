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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::group(['prefix'=>'v1', 'middleware'=>'cors'], function(){
    
});*/

Route::group(['middleware' => ['cors']], function(){
    
    /* Route::resource('roles', 'Jat\RolController', ['except' => [
        'create', 'edit'
    ]]);*/
});
// roles
Route::resource('roles','Jat\RolController');
Route::post('buscarrol', 'Jat\RolController@busqueda');
// servicios
Route::resource('servicios', 'Jat\ServiciosController');
Route::post('buscarservicio', 'Jat\ServiciosController@busqueda');
// usuarios
Route::resource('usuarios','Jat\UsuarioController');
Route::post('buscarusuario', 'Jat\UsuarioController@busqueda');
// personas
Route::resource('personas', 'Jat\PersonaController');
Route::post('buscarpersona', 'Jat\PersonaController@busqueda');
// Route::resource('casa.casaservicio', 'Jat\CasaServicioController');
// empresa
Route::resource('empresa', 'Jat\EmpresaController');
// ubigeo
Route::resource('ubigeos', 'Jat\UbigeoController');
Route::get('buscarubigeo/{id}', 'Jat\UbigeoController@buscarubigeo');
Route::get('mostrarubigeos/{tipoubigeo_id}/{codigo}', 'Jat\UbigeoController@mostrarubigeos');
Route::post('buscarubigeos', 'Jat\UbigeoController@buscarubigeos');
// tipo ubigeo
Route::resource('tipoubigeos', 'Jat\UbigeoTipoController');
Route::post('buscartipoubigeo', 'Jat\UbigeoTipoController@buscartipoubigeo');
// foto
Route::resource('fotos', 'Jat\FotoController');

/**Casas */
Route::resource('casas', 'Jat\CasaController');
Route::post('buscarcasa', 'Jat\CasaController@busqueda');
Route::post('mostrarpropiedades', 'Jat\CasaController@mostrarpropiedades'); // welcome
Route::resource('casaservicio', 'Jat\CasaServicioController');
Route::resource('casafoto', 'Jat\CasaFotoController');
Route::resource('casamensaje', 'Jat\CasaMensajeController');
Route::get('mostrarcasamensajes/{casa_id}/{estado}', 'Jat\CasaMensajeController@mostrarcasamensajes');
Route::get('cambiarestadocasa/{casa_id}/{nmensajes}/{estado}', 'Jat\CasaMensajeController@cambiarestado');
/**Lotes */
Route::resource('lotes', 'Jat\LoteController');
Route::post('buscarlote', 'Jat\LoteController@busqueda');
Route::post('mostrarlotes', 'Jat\LoteController@mostrarlotes'); // welcome
Route::resource('loteservicio', 'Jat\LoteServicioController');
Route::resource('lotefoto', 'Jat\LoteFotoController');
Route::resource('lotemensaje', 'Jat\LoteMensajeController');
Route::get('mostrarlotemensajes/{lote_id}/{estado}', 'Jat\LoteMensajeController@mostrarlotemensajes');
Route::get('cambiarestadolote/{lote_id}/{nmensajes}/{estado}', 'Jat\LoteMensajeController@cambiarestado');
/**Habitaciones */
Route::resource('habitaciones', 'Jat\HabitacionController');
Route::post('buscarhabitacion', 'Jat\HabitacionController@busqueda');
Route::post('mostrarhabitaciones', 'Jat\HabitacionController@mostrarhabitaciones'); // welcome
Route::resource('habitacionservicio', 'Jat\HabitacionController');
Route::resource('habitacionfoto', 'Jat\HabitacionFotoController');
Route::resource('habitacionmensaje', 'Jat\HabitacionMensajeController');
Route::get('mostrarhabitacionmensajes/{habitacion_id}/{estado}', 
    'Jat\HabitacionMensajeController@mostrarhabitacionmensajes');
Route::get('cambiarestadohabitacion/{habitacion_id}/{nmensajes}/{estado}', 
    'Jat\HabitacionMensajeController@cambiarestado');
/**Locales */
Route::resource('locales', 'Jat\LocalController');
Route::post('buscarlocal', 'Jat\LocalController@busqueda');
Route::post('mostrarlocales', 'Jat\LocalController@mostrarlocales'); // welcome
Route::resource('loteservicio', 'Jat\LocalServicioController');
Route::resource('localfoto', 'Jat\LocalFotoController');
Route::resource('localmensaje', 'Jat\LocalMensajeController');
Route::get('mostrarlocalmensajes/{local_id}/{estado}', 
    'Jat\LocalMensajeController@mostrarlocalmensajes');
Route::get('cambiarestadolocal/{local_id}/{nmensajes}/{estado}', 
    'Jat\LocalMensajeController@cambiarestado');
/**Cochera */
Route::resource('cocheras', 'Jat\Cochera\CocheraController');
// Route::post('buscarcochera', 'Jat\Cochera\CocheraController@busqueda');
// Route::post('mostrarcocheras', 'Jat\Cochera\CocheraController@mostrarcocheras'); // welcome
Route::resource('cocheraservicio', 'Jat\Cochera\CocheraServicioController');
Route::resource('cocherafoto', 'Jat\Cochera\CocheraFotoController');
Route::get('mostrarcocheramensajes/{cochera_id}/{estado}', 
    'Jat\Cochera\CocheraMensajeController@mostrarcocheramensajes');
Route::get('cambiarestadocochera/{cochera_id}/{nmensajes}/{estado}', 
    'Jat\Cochera\CocheraMensajeController@cambiarestado');
/**Apartamento */
Route::resource('apartamentos', 'Jat\Apartamento\ApartamentoController');
// Route::post('buscarapartamento', 'Jat\Apartamento\ApartamentoController@busqueda');
// Route::post('mostrarapartamentos', 'Jat\Apartamento\ApartamentoController@mostrarapartamentos'); // welcome
Route::resource('apartamentoservicio', 'Jat\Apartamento\ApartamentoServicioController');
Route::resource('apartamentofoto', 'Jat\Apartamento\ApartamentoFotoController');
Route::resource('apartamentomensaje', 'Jat\Apartamento\ApartamentoMensajeController');
Route::get('mostrarapartamentomensajes/{apartamento_id}/{estado}', 
    'Jat\Apartamento\ApartamentoMensajeController@mostrarapartamentomensajes');
Route::get('cambiarestadoapartamento/{apartamento_id}/{nmensajes}/{estado}', 
    'Jat\ApartamentoMensajeController@cambiarestado');
/**Apartamento Cuarto */
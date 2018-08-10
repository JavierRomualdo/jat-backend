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
// tipo ubigeo
Route::resource('tipoubigeos', 'Jat\UbigeoTipoController');
// foto
Route::resource('fotos', 'Jat\FotoController');
// casas
Route::resource('casas', 'Jat\CasaController');
Route::post('buscarcasa', 'Jat\CasaController@busqueda');
Route::resource('casaservicio', 'Jat\CasaServicioController');
Route::resource('casafoto', 'Jat\CasaFotoController');
// lotes
Route::resource('lotes', 'Jat\LoteController');
Route::post('buscarlote', 'Jat\LoteController@busqueda');
Route::resource('loteservicio', 'Jat\LoteServicioController');
Route::resource('lotefoto', 'Jat\LoteFotoController');
// habitaciones
Route::resource('habitaciones', 'Jat\HabitacionController');
Route::post('buscarhabitacion', 'Jat\HabitacionController@busqueda');
Route::resource('habitacionservicio', 'Jat\HabitacionController');
Route::resource('habitacionfoto', 'Jat\HabitacionFotoController');
// locales
Route::resource('locales', 'Jat\LocalController');
Route::post('buscarlocal', 'Jat\LocalController@busqueda');
Route::resource('loteservicio', 'Jat\LocalServicioController');
Route::resource('localfoto', 'Jat\LocalFotoController');
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

Route::resource('usuarios','Jat\UsuarioController');
Route::post('buscarusuario', 'Jat\UsuarioController@busqueda');

Route::resource('roles','Jat\RolController');
Route::post('busquedaRoles', 'Jat\RolController@busqueda');
// Route::get('roles','Jat\RolController@index');

Route::resource('servicios', 'Jat\ServiciosController');
Route::post('buscarservicio', 'Jat\ServiciosController@busqueda');

Route::resource('rol', 'Jat\RolController');

Route::resource('personas', 'Jat\PersonaController');
Route::resource('buscarpersona', 'Jat\PersonaController@busqueda');

Route::resource('casa', 'Jat\CasaController');
Route::resource('casa.casaservicio', 'Jat\CasaServicioController');

Route::resource('empresa', 'Jat\EmpresaController');

Route::resource('lotes', 'Jat\LoteController');
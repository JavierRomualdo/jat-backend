<?php

namespace App\Http\Controllers\Jat\Busqueda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\Habitacion;
use App\Models\Local;
use App\Models\Lote;
use App\Models\Apartamento;
use App\Models\Cochera;

class BusquedaController extends Controller
{
    //

    public function busquedaPropiedad(Request $request )
    {
    	# code...
    	switch ($request->input('propiedad')) {
    		case 'Casas':
    			# code...
    			return $this->mostrarCasas($request);
    			break;
    		case 'Habitaciones':
    			return $this->mostrarHabitaciones($request);
    			break;
    		case 'Locales':
    			return $this->mostrarLocales($request);
    			break;
    		case 'Lotes':
    			return $this->mostrarLotes($request);
    			break;
    		case 'Apartamentos':
    			return $this->mostrarApartamentos($request);
    			break;
    		case 'Cocheras':
    			return $this->mostrarCocheras($request);
    			break;
    		default:
    			# code...
    			break;
    	}
    }

    public function mostrarCasas(Request $request)
    {
    	# code...
    	$casas = "vacio";
    	if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	//DISTRITO
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // casas con ese distrito con rango de precio
                        if ($request->input('servicios') != null) {
                        	// incluyendo servicios
                        	$codigo = $request->input('distrito.codigo');
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                       ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                       ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('distrito.codigo');
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio'))
	                        ->get();
                        }
                        
                    } else {
                        // casas con ese distrito sin rango de precio
                        if ($request->input('servicios') != null) {
                        	// incluyendo servicios
                        	$codigo = $request->input('distrito.codigo');
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('distrito.codigo');
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio','casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                } else {
                	// NO DISTRITO OSEA PROVINCIAS
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo')!="" && 
                        $request->input('rangoprecio.preciominimo')!=null && 
                        $request->input('rangoprecio.preciomaximo')!="" && 
                        $request->input('rangoprecio.preciomaximo')!=null) {
                        //provincia con rango de precio
                    	if ($request->input('servicios') != null) {
                    		// incluyendo servicios
                    		$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                    	} else {
                    		// sin servicios
                    		$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    	}
                    } else {
                        // provincia sin rango de precios
                        if ($request->input('servicios') != null) {
                        	// incluyendo servicios
                        	$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
	                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                }
            } else {
            	// NO PROVINCIAS OSEA SOLO DEPARTAMENTOS EN GENERAL
                if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                    // casas con departamento con rango de precio
                    if ($request->input('servicios') != null) {
                    	// con rango de precio con servicios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
                        ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->get();
                    } else {
                        // con rango de precios sin servicios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
	                    $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                    ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                    ->get();
                    }
                    
                } else {
                    // casas del departamento en general sin rango de precios
                    if ($request->input('servicios') != null) {
                        // con servicios sin rango de precios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
	                    $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                    ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->groupBy('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.tiposervicio', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                    ->get();
                    }
                    else {
                        // sin servicios sin rango de precios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
	                    $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('casa.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    }
                }
            }
        }
        return response()->json($casas);
    }

    public function mostrarHabitaciones(Request $request)
    {
    	# code...
    	$habitaciones = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	// CON DISTRITOS
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // habitaciones con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    	->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // habitaciones con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    	->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                } else { 
                	// SIN DISTRITOS OSEA CON PROVINCIAS EN GENERAL
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    	->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general sin rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    	->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                }
            } else {
            	// SIN PROVINCIAS OSEA SOLO CON DEPRTAMENTOS
                if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                    // habitaciones del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
                    	// CON SERVICIOS
                    	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    }
                } else {
                    // habitaciones del departamento en general sin rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                   	if ($request->input('servicios') != null) {
                   		// CON SERVICIOS
                   		$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->groupBy('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->get();
                   	} else {
                   		// SIN SERVICIOS sin rango
                   		$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.tiposervicio','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('habitacion.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                   	}
                }
            }
        }
        return response()->json($habitaciones);
    }

    public function mostrarLocales(Request $request)
    {
    	# code...
    	$locales = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	// CON DISTRITO
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // locales con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // locales con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio','ubigeo.codigo', 'ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio','ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
	                    }
                    }
                } else { 
                	// SIN DISTRITO OSEA SOLO CON PROVINCIAS EN GENERAL
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
							// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
							// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                }
            } else { 
            	// SIN PROVINCIAS OSEA SOLO CON DEPARTAMENTOS
                if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                    // locales del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
						// CON SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                    'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                    'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    }
                } else {
                    // locales del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
						// CON SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                    'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
		                ->groupBy('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                        'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
	                    'local.foto', 'local.estado', 'local.tiposervicio', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('local.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    }
                }
            }
        }
        return response()->json($locales);
    }

    // Solo alquiler
    public function mostrarLotes(Request $request)
    {
    	# code...
    	// AQUI EN LOTES NO HAY SERVICIOS SOLO EL TIPO SE DERVICIO (V O A)
    	$lotes = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	// CON DISTRITOS
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // lotes con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 'lote.tiposervicio', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
	                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    } else {
                        // lotes con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 'lote.tiposervicio', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
                        ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->get();
                    }
                } else { 
                	// SIN DISTRITOS OSEA SOLO ON PROVINCIAS EN GENERAL
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 'lote.tiposervicio', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
                        ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 'lote.tiposervicio', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                        ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->get();
                    }
                }
            } else { 
            	// SIN PROVINCIAS OSEA CON DEPARTAMENTOS EN GENERAL
                if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                    // lotes del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'lote.tiposervicio', 'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
                    ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                    ->get();
                } else {
                    // lotes del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 'lote.tiposervicio', 'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                    ->whereIn('lote.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                    ->get();
                }
            }
        }
        return response()->json($lotes);
    }

    // Solo alquiler
    public function mostrarApartamentos(Request $request)
    {
    	# code...
    	$apartamentos = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	// CON DISTRITOS
                	$codigo = $request->input('distrito.codigo');
                    if ($request->input('servicios') != null) {
                        // CON SERVICIOS
                        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						        'apartamento.foto', 'apartamento.estado')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
	                    ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                    ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			            ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
	                    ->get();
                    } else {
                       	// SIN SERVICIOS
                       	$apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                    ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                    ->get();
                    }
                } else { 
                	// SIN DISTRITOS OSEA SOLO CON PROVINCIAS EN GENERAL
                	$codigo = $request->input('provincia.codigo'); 
                    $subs = substr($codigo, 0, 4); // ejmp: 01
                    if ($request->input('servicios') != null) {
                        // distritos de la provincia con rango de precio
                        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					        'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
					        'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
					        'apartamento.foto', 'apartamento.estado')
                        ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                        ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                        ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			            ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
                        ->get();
                    } else {
                        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					        'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
					        'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
					        'apartamento.foto', 'apartamento.estado')
                        ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                        ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                        ->get();
                    }
                }
            } else {
            	// SIN PROVINCIAS SOLO CON DEPARTAMENTOS EN GENERAL
            	$codigo = $request->input('departamento.codigo'); 
                $subs = substr($codigo, 0, 2); // ejmp: 01
                if ($request->input('servicios') != null) {
                    //con servicios
                    $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
					    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
					    'apartamento.foto', 'apartamento.estado')
                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                    ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                    ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			        ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			        ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
                    ->get();
                } else {
                    // sin servicios
                    $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'tiposervicio',
					    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'tipoubigeo_id',
					    'apartamento.foto', 'apartamento.estado')
                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
                    ->whereIn('apartamento.tiposervicio', $request->input('tiposervicio')) // ['V','A']
                    ->get();
                }
            }
        }
        return response()->json($apartamentos);
    }

    public function mostrarCocheras(Request $request)
    {
    	# code...
    	$cocheras = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                	// CON DISTRITOS
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // cocheras con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
					        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
					        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                        
                    } else {
                        // cocheras con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion', 'cochera.tiposervicio', 'ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion', 'cochera.tiposervicio', 'ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])
	                        ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                } else {
                	// SIN DISTRITOS OSEA CON PROVINCIAS
                    if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                        ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                        ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
	                        ->get();
                        }
                    }
                }
            } else {
            	// SIN PROVINCIAS OSEA CON DEPARTAMENTOS
                if ($request->input('rangoprecio') != null && 
                        $request->input('rangoprecio.preciominimo') != "" && 
                        $request->input('rangoprecio.preciominimo') != null && 
                        $request->input('rangoprecio.preciomaximo') != "" && 
                        $request->input('rangoprecio.preciomaximo') != null) {
                    // cocheras del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
                    	// CON SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			            ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                    ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
	                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
	                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])
	                    ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // 
	                    ->get();
                    }
                } else {
                    // cocheras del departamento en general sin rango de fechas
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
                    	// CON SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
						->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // ['V','A']
			            ->groupBy('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'cochera.tiposervicio', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','precio',
					        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.tiposervicio', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id')
						->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])
	                    ->whereIn('cochera.tiposervicio', $request->input('tiposervicio')) // 
	                    ->get();
                    }
                }
            }
        }
        return response()->json($cocheras);
    }
}

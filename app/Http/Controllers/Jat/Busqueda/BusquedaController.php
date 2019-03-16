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
    		case 'Casa':
    			# code...
    			return $this->mostrarCasas($request);
    			break;
    		case 'Habitación':
    			return $this->mostrarHabitaciones($request);
    			break;
    		case 'Local':
    			return $this->mostrarLocales($request);
    			break;
    		case 'Lote':
    			return $this->mostrarLotes($request);
    			break;
    		case 'Apartamento':
    			return $this->mostrarApartamentos($request);
    			break;
    		case 'Cochera':
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
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
	                        ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                       ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                       ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 
							'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('distrito.codigo');
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['casa.estado', '=', true],['casa.estadocontrato','=','L']])
	                        ->whereIn('casa.contrato', $request->input('contrato'))
	                        ->get();
                        }
                        
                    } else {
                        // casas con ese distrito sin rango de precio
                        if ($request->input('servicios') != null) {
                        	// incluyendo servicios
                        	$codigo = $request->input('distrito.codigo');
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 'tcochera',
							'largo','ancho','casa.direccion', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto', 'persona.nombres', 
							'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('distrito.codigo');
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato','casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
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
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 
							'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                    	} else {
                    		// sin servicios
                    		$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['casa.estado', '=', true], ['casa.estadocontrato','=','L']])->get();
                    	}
                    } else {
                        // provincia sin rango de precios
                        if ($request->input('servicios') != null) {
                        	// incluyendo servicios
                        	$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin',
							'tcochera','largo','ancho','casa.direccion', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto', 'persona.nombres', 
							'casa.contrato', 'casa.persona_id', 'casa.estado', 'ubigeo.tipoubigeo_id','referencia')
	                        ->get();
                        } else {
                        	// sin servicios
                        	$codigo = $request->input('provincia.codigo'); 
	                        $subs = substr($codigo, 0, 4); // ejmp: 01
							$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
							'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
							'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
							'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 
							'referencia')
	                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
							->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
							->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
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
						$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 
						'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto',
						'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado', 'ubigeo.tipoubigeo_id', 
						'referencia')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
						->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
						->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
                        ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
                        ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
						['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
						['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
                        ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
                        ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
						->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 'tcochera',
						'largo','ancho','casa.direccion', 'habilitacionurbanas.siglas', 'casa.nombrehabilitacionurbana',
						'ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto', 'persona.nombres', 
						'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 'referencia')
                        ->get();
                    } else {
                        // con rango de precios sin servicios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
						$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
						'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
						'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id',
						'referencia')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
						->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
						->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                    ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                    ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
						['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
						['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                    ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
						->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 'tcochera',
						'largo','ancho','casa.direccion', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto', 'persona.nombres', 
						'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    }
                    
                } else {
                    // casas del departamento en general sin rango de precios
                    if ($request->input('servicios') != null) {
                        // con servicios sin rango de precios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
						$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
						'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
						'persona.nombres', 'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id',
						'referencia')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
						->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
						->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
	                    ->join('casaservicio', 'casaservicio.casa_id', '=', 'casa.id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                    ->whereIn('casaservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
						->groupBy('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios','tjardin', 'tcochera',
						'largo','ancho','casa.direccion', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 'path', 'casa.foto', 'persona.nombres',
						'casa.contrato', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id','referencia')
	                    ->get();
                    }
                    else {
                        // sin servicios sin rango de precios
                    	$codigo = $request->input('departamento.codigo'); 
	                    $subs = substr($codigo, 0, 2); // ejmp: 01
						$casas = Casa::select('casa.id','nombres','preciocontrato','npisos','ncuartos', 'nbanios',
						'tjardin', 'tcochera','largo','ancho', 'habilitacionurbana.siglas', 'casa.nombrehabilitacionurbana',
						'casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'descripcion', 'path', 'casa.foto',
						'persona.nombres', 'casa.persona_id', 'casa.estado', 'ubigeo.tipoubigeo_id', 'casa.contrato', 
						'referencia')
	                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
						->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
						->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'casa.habilitacionurbana_id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['casa.estado', '=', true], ['casa.estadocontrato','=','L']])
	                    ->whereIn('casa.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    }
                }
            }
        }
        return response()->json($casas);
    }

	// Solo alquiler
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
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 'largo', 
							'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 
							'habitacion.foto', 'habitacion.estado', 'habitacion.contrato', 'ubigeo.ubigeo', 
							'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
								['habitacion.estado', '=', true]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 'ubigeo.ubigeo', 
							'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 
							'habitacion.estado', 'habitacion.contrato','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
							'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 
							'habitacion.estado','habitacion.contrato','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
	                            ['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
								['habitacion.estado', '=', true]])
	                        ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // habitaciones con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
							'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 
							'habitacion.foto', 'habitacion.estado','habitacion.contrato','ubigeo.ubigeo', 
							'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
							['habitacion.estado', '=', true]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 'ubigeo.ubigeo', 
							'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 
							'habitacion.estado', 'habitacion.contrato','ubigeo.ubigeo', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
							'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
							'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato', 
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
							['habitacion.estado', '=', true]])
	                        ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
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
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
							'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path', 'habitacion.foto', 
							'habitacion.estado','habitacion.contrato','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
	                            ['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
								['habitacion.estado', '=', true]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
							'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 
							'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.contrato',
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
							'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
							'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato', 
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
	                            ['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
								['habitacion.estado', '=', true]])
	                        ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general sin rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
							'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
							'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato', 
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
							['habitacion.estado', '=', true]])
	                        ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    	->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
							->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
							'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 
							'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.contrato', 
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
							$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
							'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
							'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato',
							'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
							['habitacion.estado', '=', true]])
	                        ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
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
						$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
						'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
						'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato',
						'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                    ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
	                    ['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
						['habitacion.estado', '=', true]])
	                    ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
						->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 
						'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.contrato',
						'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
						$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
						'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
						'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato',
						'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                    ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
	                    ['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')],
						['habitacion.estado', '=', true]])
	                    ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    }
                } else {
                    // habitaciones del departamento en general sin rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                   	if ($request->input('servicios') != null) {
                   		// CON SERVICIOS
						$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
						   'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
						   'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato',
						   'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->join('habitacionservicio', 'habitacionservicio.habitacion_id', '=', 'habitacion.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
						['habitacion.estado', '=', true]])
	                    ->whereIn('habitacionservicio.servicio_id', $request->input('servicios')) //[4,1,2]
	                    ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
						->groupBy('habitacion.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 
						'path', 'habitacion.foto', 'habitacion.estado', 'habitacion.contrato',
						'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                   	} else {
                   		// SIN SERVICIOS sin rango
						$habitaciones = Habitacion::select('habitacion.id', 'nombres', 'preciocontrato', 
						   'largo', 'ancho', 'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 
						   'descripcion', 'path', 'habitacion.foto', 'habitacion.estado','habitacion.contrato',
						   'ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
						['habitacion.estado', '=', true]])
	                    ->whereIn('habitacion.contrato', $request->input('contrato')) // ['V','A']
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
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // locales con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato','ubigeo.codigo', 
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato','ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
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
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
							// CON SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                        ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                    ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'local.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                        ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
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
                    	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
						'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
						'ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                    ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
						['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
						['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                    ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
						'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
						'ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                    ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
						['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
						['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    }
                } else {
                    // locales del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
						// CON SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
						'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
						'ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
	                    ->join('localservicio', 'localservicio.local_id', '=', 'local.id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                    ->whereIn('localservicio.servicio_id', $request->input('servicios')) //[4,1,2]
		                ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
		                ->groupBy('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
							'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
							'ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$locales = Local::select('local.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
	                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
						'local.foto', 'local.estado', 'local.contrato', 'ubigeo.codigo',
						'ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'local.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['local.estadocontrato','=','L'], ['local.estado', '=', true]])
	                    ->whereIn('local.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    }
                }
            }
        }
        return response()->json($locales);
    }

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
						$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 
						'lote.contrato', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                        ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
							['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
							['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
	                    ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    } else {
                        // lotes con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
						$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 
						'lote.contrato', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
						['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
                        ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
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
						$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 
						'lote.contrato', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
							['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
							['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
                        ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
                        ->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
						$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
						'ubigeo.ubigeo', 'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado', 
						'lote.contrato', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
                        ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
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
					$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
					'ubigeo.ubigeo', 'lote.direccion', 'lote.contrato', 'descripcion', 'path', 
					'lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
                        ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
						['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
						['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
                    ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
                    ->get();
                } else {
                    // lotes del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
					$lotes = Lote::select('lote.id', 'nombres', 'preciocontrato', 'largo', 'ancho', 
					'ubigeo.ubigeo', 'lote.direccion', 'lote.contrato', 'descripcion', 'path', 
					'lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo', 'referencia')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
					->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
					['lote.estadocontrato','=','L'], ['lote.estado', '=', true]])
                    ->whereIn('lote.contrato', $request->input('contrato')) // ['V','A']
                    ->get();
                }
            }
        }
        return response()->json($lotes);
    }

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
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'preciocontrato',
						        'apartamento.foto', 'apartamento.estado')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
	                    ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], ['apartamento.estadocontrato','=','L']])
	                    ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
			            ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
	                    ->get();
                    } else {
                       	// SIN SERVICIOS
                       	$apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'preciocontrato',
						    'apartamento.foto', 'apartamento.estado')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], ['apartamento.estadocontrato','=','L']])
	                    ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
	                    ->get();
                    }
                } else { 
                	// SIN DISTRITOS OSEA SOLO CON PROVINCIAS EN GENERAL
                	$codigo = $request->input('provincia.codigo'); 
                    $subs = substr($codigo, 0, 4); // ejmp: 01
                    if ($request->input('servicios') != null) {
                        // distritos de la provincia con rango de precio
                        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					        'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
					        'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'preciocontrato',
					        'apartamento.foto', 'apartamento.estado')
                        ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                        ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], ['apartamento.estadocontrato','=','L']])
                        ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
			            ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
                        ->get();
                    } else {
                        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					        'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
					        'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'preciocontrato',
					        'apartamento.foto', 'apartamento.estado')
                        ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], ['apartamento.estadocontrato','=','L']])
                        ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
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
					    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
					    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'preciocontrato',
					    'apartamento.foto', 'apartamento.estado')
                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                    ->join('apartamentoservicio', 'apartamentoservicio.apartamento_id', '=', 'apartamento.id')
                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], ['apartamento.estadocontrato','=','L']])
                    ->whereIn('apartamentoservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			        ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
			        ->groupBy('apartamento.id', 'npisos', 'tcochera',
						    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato',
						    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path',
						    'apartamento.foto', 'apartamento.estado')
                    ->get();
                } else {
                    // sin servicios
                    $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
					    'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'contrato', 'preciocontrato',
					    'ubigeo.tipoubigeo_id', 'apartamento.descripcion', 'path', 'tipoubigeo_id',
					    'apartamento.foto', 'apartamento.estado')
                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], ['apartamento.estadocontrato','=','L']])
                    ->whereIn('apartamento.contrato', $request->input('contrato')) // ['V','A']
                    ->get();
                }
            }
        }
        return response()->json($apartamentos);
    }

	// Solo Alquiler
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
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'cochera.contrato', 'path', 'cochera.foto', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
					        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'cochera.contrato', 'path', 'cochera.foto', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
					        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                        
                    } else {
                        // cocheras con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion', 'cochera.contrato', 'ubigeo.ubigeo', 
							'ubigeo.codigo', 'cochera.descripcion', 'path', 'cochera.foto', 'cochera.persona_id', 
							'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion', 'cochera.contrato', 'ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','=',$codigo], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
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
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','precio',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                            ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
								['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
								['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
	                        ->get();
                        }
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01
                        if ($request->input('servicios') != null) {
                        	// CON SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                        ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			                ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			                ->groupBy('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                        ->get();
                        } else {
                        	// SIN SERVICIOS
                        	$cocheras = Cochera::select('cochera.id','persona.nombres','prepreciocontratocio',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
							->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                        ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
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
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                        ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
							['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                    ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			            ->groupBy('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
	                    ->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'],
	                        ['preciocontrato','>=',$request->input('rangoprecio.preciominimo')],
							['preciocontrato','<=',$request->input('rangoprecio.preciomaximo')], 
							['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                    ->whereIn('cochera.contrato', $request->input('contrato')) // 
	                    ->get();
                    }
                } else {
                    // cocheras del departamento en general sin rango de fechas
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01
                    if ($request->input('servicios') != null) {
                    	// CON SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
						->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
	                    ->join('cocheraservicio', 'cocheraservicio.cochera_id', '=', 'cochera.id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                    ->whereIn('cocheraservicio.servicio_id', $request->input('servicios')) //[4,1,2]
			            ->whereIn('cochera.contrato', $request->input('contrato')) // ['V','A']
			            ->groupBy('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 'cochera.descripcion', 
							'cochera.contrato', 'path', 'cochera.foto', 'cochera.persona_id', 'cochera.estado',
							'ubigeo.tipoubigeo_id', 'referencia')
	                    ->get();
                    } else {
                    	// SIN SERVICIOS
                    	$cocheras = Cochera::select('cochera.id','persona.nombres','preciocontrato',
							'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'ubigeo.codigo', 
							'cochera.descripcion', 'path', 'cochera.foto', 'cochera.contrato', 
							'cochera.persona_id', 'cochera.estado','ubigeo.tipoubigeo_id', 'referencia')
						->join('persona', 'persona.id', '=', 'cochera.persona_id')
	                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
						->where([['tipoubigeo_id','=',3],['ubigeo.codigo','like',$subs.'%'], 
						['cochera.estadocontrato','=','L'], ['cochera.estado', '=', true]])
	                    ->whereIn('cochera.contrato', $request->input('contrato')) // 
	                    ->get();
                    }
                }
            }
        }
        return response()->json($cocheras);
    }
}

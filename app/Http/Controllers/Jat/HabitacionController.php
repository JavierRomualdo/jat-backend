<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\HabitacionFoto;
use App\Models\HabitacionMensaje;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Servicios;
use App\Models\HabitacionServicio;
use App\Models\Ubigeo;
use App\Models\HabilitacionUrbana;
use App\Dto\HabitacionDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

use App\EntityWeb\Utils\RespuestaWebTO;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $habitaciones = Habitacion::orderBy('codigo')->get();
        return response()->json($habitaciones);
    }

    public function mostrarCondicionUbigeo($tipoubigeo, $codigo)
    {
        # code...
        if ($tipoubigeo==1) {
            // ubigeos con departamentos
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $condicion = ['codigo','like',$subs.'%'];
        }  elseif ($tipoubigeo==2) {
            $subs = substr($codigo, 0, 4); // ejmp: 0101
            $condicion = ['codigo','like',$subs.'%'];
        } else if ($tipoubigeo==3) {
            // ubigeos con provincias
            $subs = substr($codigo, 0, 4); // ejmp: 010101
            $condicion = ['codigo','=',$codigo];
        } else {
            $condicion = 'error';
        }
        return $condicion;
    }

    // listo todas las habitaciones disponibles para cualquier tipo contrato (venta o alquiler)
    public function listarHabitacionesParaTipoContrato(Request $request)
    {
        # code...
        /* Parametros ($request) : 
            { codigo: string, contrato: string, ubigeo: Ubigeo }
        */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            // para la condicion del ubigeo
            $tipoubigeo = $request->input('ubigeo') ? $request->input('ubigeo.tipoubigeo_id') : null;
            $codigo = $request->input('ubigeo') ? $request->input('ubigeo.codigo'): null;
            $condicion = $request->input('ubigeo') ? $this->mostrarCondicionUbigeo($tipoubigeo,$codigo) : null;
            if ($condicion!== 'error') { // HabitacionTO
                $habitaciones = Habitacion::select('habitacion.id', 'habitacion.foto', 'persona.nombres as propietario', 
                    'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas',
                    'habitacion.direccion', 'largo', 'ancho', 'habitacion.codigo', 
                    'precioadquisicion', 'preciocontrato', 'ganancia', 'ncamas', 'tbanio', 'habitacion.contrato', 
                    'habitacion.estadocontrato', 'habitacion.estado', 'habitacion.nmensajes')
                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                    ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                    ->where([['habitacion.estado','=',true], ['habitacion.estadocontrato','=','L'],
                        ['habitacion.codigo','like','%'.($request->codigo).'%'], ['habitacion.contrato','=',$request->contrato], 
                        ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($habitaciones!==null && !$habitaciones->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($habitaciones);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron habitaciones');
                }
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error con ubigeos');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }
    //

    public function listarHabitaciones(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            } // HabitacionTO
            $habitaciones = Habitacion::select('habitacion.id', 'habitacion.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas',
            'habitacion.direccion', 'largo', 'ancho', 'habitacion.codigo', 
            'precioadquisicion', 'preciocontrato', 'ganancia', 'ncamas', 'tbanio', 'habitacion.contrato', 
            'habitacion.estadocontrato', 'habitacion.estado', 'habitacion.nmensajes')
            ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->whereIn('habitacion.estado', $estados)->get();

            if ($habitaciones!==null && !$habitaciones->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($habitaciones);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron habitaciones');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function listarHabitacionesPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // HabitacionTO
            $habitaciones = Habitacion::select('habitacion.id', 'habitacion.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas',
            'habitacion.direccion', 'largo', 'ancho', 'habitacion.codigo', 
            'precioadquisicion', 'preciocontrato', 'ganancia', 'ncamas', 'tbanio', 'habitacion.contrato', 
            'habitacion.estadocontrato', 'habitacion.estado', 'habitacion.nmensajes')
            ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->where('habitacion.estadocontrato', $request->input('estadoContrato'))->get();

            if ($habitaciones!==null && !$habitaciones->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($habitaciones);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron habitaciones');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function cambiarEstadoHabitacion(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado la habitacion en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $habitacion = Habitacion::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($habitacion!==null && $habitacion!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('La habitacion: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($habitacion);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al modificar estado');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function generarCodigoHabitacion() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoHabitacion();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo de la habitacion');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function nuevoCodigoHabitacion()
    {
        # code...
        $codigo = 'HA';
        $n = Habitacion::count();
        $n++;
        if ($n < 10) {
            $codigo = $codigo.'0000'.$n;
        } else if ($n < 100) {
            $codigo = $codigo.'000'.$n;
        } else if ($n < 1000) {
            $codigo = $codigo.'00'.$n;
        } else if ($n < 10000) {
            $codigo = $codigo.'0'.$n;
        } else if ($n < 100000) {
            $codigo = $codigo.$n;
        } else {
            $codigo = null;
        }
        
        return $codigo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $habitacion = Habitacion::create([
                'persona_id' => $request->input('persona_id.id'),
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'precioadquisicion' => $request->precioadquisicion,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'direccion' => $request->direccion,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'ncamas' => $request->ncamas,
                'tbanio' => $request->tbanio,
                'referencia' => $request->referencia,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'foto' => $request->foto,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ]);
    
            // aqui obtengo un arreglo de id proveniente de la lista de servicios
            $servicios = $request->input('serviciosList.*.id');
            for ($i = 0; $i < count($servicios); $i++) {
                $habitacionservicio = HabitacionServicio::create([
                    'habitacion_id' => $habitacion->id,
                    'servicio_id' => $servicios[$i],
                    'estado' => true
                ]);
            }
    
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $habitacionfoto = HabitacionFoto::create([
                    'habitacion_id' => $habitacion->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La habitacion: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($habitacion);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $habitaciondto = new HabitacionDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();

            $habitacion = Habitacion::select('habitacion.id', 'nombres', 'habitacion.codigo', 'precioadquisicion', 
                'preciocontrato', 'ganancia', 'largo', 'ancho', 'habilitacionurbana.nombre',
                'habilitacionurbana.siglas',
                'ubigeo.ubigeo as nombrehabilitacionurbana', 'habitacion.direccion', 'referencia',
                'habitacion.latitud', 'habitacion.longitud', 'ncamas', 'tbanio', 'descripcion', 'path','habitacion.foto', 
                'habitacion.estado', 'habitacion.persona_id as idpersona', 'habitacion.ubigeo_id as idubigeo', 
                'habitacion.habilitacionurbana_id as idhabilitacionurbana', 'contrato', 'estadocontrato')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where('habitacion.id','=',$id)->first();
            if ($habitacion !== null && $habitacion !== '') {
                $habitaciondto->setHabitacion($habitacion);
                $persona = Persona::FindOrFail($habitacion->idpersona);
                $habitaciondto->setPersona($persona);

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($habitacion->idubigeo); // siempre es el ubigeo distrito
                $ubigeodto->setUbigeo($ubigeo);
                $codigo = $ubigeo->codigo;
                $subsdepartamento = substr($codigo, 0, 2)."00000000";
                $subsprovincia = substr($codigo, 0, 4)."000000";
                $subsdistrito = substr($codigo, 0, 6)."0000";

                $ubigeos = Ubigeo::whereIn('codigo', [$subsdepartamento, $subsprovincia, $subsdistrito])->get();

                $departamento = $ubigeos[0];
                $provincia = $ubigeos[1];
                $distrito = $ubigeos[2];
                $ubigeodetalledto->setDepartamento($departamento);
                $ubigeodetalledto->setProvincia($provincia);
                $ubigeodetalledto->setDistrito($distrito);
                $ubigeodetalledto->setUbigeo($ubigeodto);
                $habitaciondto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                // habilitacionurbana
                $habilitacionurbana = HabilitacionUrbana::FindOrFail($habitacion->idhabilitacionurbana);
                $habitaciondto->setHabilitacionUrbana($habilitacionurbana);
                //end habilitacionurbana
                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('habitacionfoto', 'habitacionfoto.foto_id', '=', 'foto.id')
                        ->where('habitacionfoto.habitacion_id', $id)->get();
                $habitaciondto->setFotos($fotos);
                $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
                    ->join('habitacionservicio', 'habitacionservicio.servicio_id', '=', 'servicios.id')
                    ->where('habitacionservicio.habitacion_id', $id)->get();
                $habitaciondto->setServicios($servicios);
                $habitacionservicios = HabitacionServicio::select('habitacionservicio.id','habitacionservicio.habitacion_id',
                                'habitacionservicio.servicio_id','habitacionservicio.estado')
                                ->where('habitacionservicio.habitacion_id',$id)->get();
                $habitaciondto->setHabitacionServicio($habitacionservicios);

                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($habitaciondto);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron habitaciones');
            } 
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $habitacion = Habitacion::FindOrFail($id);
            $input = [
                'persona_id' => $request->input('persona_id.id'),
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'precioadquisicion' => $request->precioadquisicion,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'direccion' => $request->direccion,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'ncamas' => $request->ncamas,
                'tbanio' => $request->tbanio,
                'referencia' => $request->referencia,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'foto' => $request->foto,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ];
            $habitacion->fill($input)->save();

            $serviciosList = $request->input('serviciosList.*');
            $serviciosLista = [];
            /*Todos los serviciosList no muestran sus atributos asi que le convertimos
            mediante su respectivo modelo "Servicios*/
            foreach($serviciosList as $servicio) {
                $serviciomodel = new Servicios();
                $serviciomodel->fill($servicio);
                $serviciosLista[] = $serviciomodel;
            }
            // Eliminacion de habitacionservicio (6 8 16)
            $habitacionservicioList = $request->input('habitacionservicioList.*');// id = 6 y id = 16
            $habitacionserviciosLista = [];
            foreach($habitacionservicioList as $habitacionservicio) {
                $habitacionservicioModel = new HabitacionServicio();
                $habitacionservicioModel->fill($habitacionservicio);
                $habitacionserviciosLista[] = $habitacionservicioModel;
            }
            $habitacionservicios = HabitacionServicio::where('habitacion_id',$habitacion->id)->get();
            $esNuevo = false;
            $habitacionserviciosEliminados = [];
            foreach($habitacionservicios as $_habitacionservicio) {
                $esNuevo = false;
                foreach($habitacionserviciosLista as $habitacionservicioLista) {
                    if($_habitacionservicio->id == $habitacionservicioLista->id) {
                        $esNuevo = true;
                    }
                }
                if(!$esNuevo) {
                    $habitacionserviciosEliminados[] = $_habitacionservicio;
                    $_habitacionservicio->delete();
                    //$habitacionservice = HabitacionServicio::FindOrFail($_habitacionservicio->id);
                    //$habitacionservice->delete();
                }
            }
            // comparamos
            // $habitacionservicioeliminadosId = array_diff($habitacionserviciosId, $);
            // end eliminacion habitacionservicio

            // agregar nuevo habitacionservicio
            $esNuevo = true;
            $serviciosNuevo = [];
            foreach($serviciosLista as $servicioLista) {
                $esNuevo = true;
                foreach($habitacionservicios as $habitacionservicio) {
                    if($servicioLista->id == $habitacionservicio->servicio_id) {
                        $esNuevo = false;
                    }
                }
                if($esNuevo) {
                    $serviciosNuevo[] = $servicioLista;
                    $habitacionservicionuevo = HabitacionServicio::create([
                        'habitacion_id' => $habitacion->id,
                        'servicio_id'=> $servicioLista->id,
                        'estado' => true
                    ]);
                }
            }
            // fotos
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $habitacionfoto = HabitacionFoto::create([
                    'habitacion_id' => $habitacion->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La habitacion: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($habitacion);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            // eliminar los mensajes
            $habitacionmensaje = HabitacionMensaje::where('habitacion_id', $id)->delete();
            // luego eliminar los servicios
            $habitacionservicio = HabitacionServicio::where('habitacion_id', $id)->delete();
            // despues eliminar las fotos
            $habitacionfoto = HabitacionFoto::where('habitacion_id', $id)->delete();
            $fotos = Foto::join('habitacionfoto', 'habitacionfoto.foto_id', '=', 'foto.id')
                    ->where('habitacionfoto.habitacion_id', $id)->delete();
            // finalmente la habitacion
            $habitacion = Habitacion::FindOrFail($id);
            $habitacion->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La habitacion: código: '.$habitacion->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($habitacion);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }
}

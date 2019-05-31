<?php

namespace App\Http\Controllers\Jat\Cochera;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cochera;
use App\Models\CocheraMensaje;
use App\Models\CocheraFoto;
use App\Models\CocheraArchivo;
use App\Models\CocheraServicio;
use App\Models\Persona;
use App\Models\Foto;
//
use App\Models\Servicios;
use App\Models\Ubigeo;
use App\Models\HabilitacionUrbana;
use App\Dto\CocheraDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

use App\EntityWeb\Utils\RespuestaWebTO;

class CocheraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cocheras = Cochera::orderBy('codigo')->get();
        return response()->json($cocheras, 200);
    }

    public function mostrarCondicionUbigeo($tipoubigeo, $codigo)
    {
        # code...
        if ($tipoubigeo==1) {
            // ubigeos con departamentos
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $condicion = ['codigo','like',$subs.'%'];
        }  elseif ($tipoubigeo==2) {
            $subs = substr($codigo, 0, 4); // ejmp: 01
            $condicion = ['codigo','like',$subs.'%'];
        } else if ($tipoubigeo==3) {
            // ubigeos con provincias
            $subs = substr($codigo, 0, 4); // ejmp: 01
            $condicion = ['codigo','=',$codigo];
        } else {
            $condicion = 'error';
        }
        return $condicion;
    }

    // listo todas las cocheras disponibles para cualquier tipo contrato (venta o alquiler)
    public function listarCocherasParaTipoContrato(Request $request)
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
            if ($condicion!== 'error') { // CocheraTO
                $cocheras = Cochera::select('cochera.id', 'cochera.foto', 'persona.nombres as propietario', 
                'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 'cochera.direccion',
                'precioadquisicion', 'preciocontrato', 'ganancia', 'largo', 'ancho', 'cochera.contrato',
                'cochera.estadocontrato', 'cochera.codigo', 'cochera.estado', 'cochera.nmensajes')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['cochera.estado','=',true], ['cochera.estadocontrato','=','L'],
                    ['cochera.codigo','like','%'.($request->codigo).'%'], ['cochera.contrato','=',$request->contrato], 
                    ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($cocheras!==null && !$cocheras->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($cocheras);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron cocheras');
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

    public function listarCocheras(Request $request)
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
            } // CocheraTO
            $cocheras = Cochera::select('cochera.id', 'cochera.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 
            'cochera.direccion', 'precioadquisicion', 'preciocontrato', 
            'ganancia', 'largo', 'ancho', 'cochera.contrato', 'cochera.estadocontrato', 'cochera.codigo',
            'cochera.estado', 'cochera.nmensajes')
            ->join('persona', 'persona.id', '=', 'cochera.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->whereIn('cochera.estado', $estados)->get();

            if ($cocheras!==null && !$cocheras->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($cocheras);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron cocheras');
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

    public function listarCocherasPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // CocheraTO
            $cocheras = Cochera::select('cochera.id', 'cochera.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 'cochera.direccion',
            'precioadquisicion', 'preciocontrato', 'ganancia', 'largo', 'ancho', 'cochera.contrato',
            'cochera.estadocontrato', 'cochera.codigo', 'cochera.estado', 'cochera.nmensajes')
            ->join('persona', 'persona.id', '=', 'cochera.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->where('cochera.estadocontrato', $request->input('estadoContrato'))->get();

            if ($cocheras!==null && !$cocheras->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($cocheras);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron cocheras');
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

    public function cambiarEstadoCochera(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado la cochera en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $cochera = Cochera::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($cochera!==null && $cochera!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('La cochera: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($cochera);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generarCodigoCochera() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoCochera();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo de la cochera');
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

    public function nuevoCodigoCochera()
    {
        # code...
        $codigo = 'CO';
        $n = Cochera::count();
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

    public function store(Request $request)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $cochera = Cochera::create([
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
                'referencia' => $request->referencia,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'pathArchivos' => $request->pathArchivos,
                'foto' => $request->foto,
                'nmensajes' => $request->nmensajes,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ]);
    
            // aqui obtengo un arreglo de id proveniente de la lista de servicios
            $servicios = $request->input('serviciosList.*.id');
            for ($i = 0; $i < count($servicios); $i++) {
                $cocheraservicio = CocheraServicio::create([
                    'cochera_id' => $cochera->id,
                    'servicio_id' => $servicios[$i],
                    'estado' => true
                ]);
            }
    
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $cocherafoto = CocheraFoto::create([
                    'cochera_id' => $cochera->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }

            foreach ($request->archivosList as $archivo) {
                CocheraArchivo::create([
                    'cochera_id' => $cochera->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }
    
            // $cochera = Cochera::create($request->all());
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La cochera: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($cochera);
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
            $cocheradto = new CocheraDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();

            $cochera = Cochera::select('cochera.id','cochera.codigo','precioadquisicion', 'preciocontrato',
                'largo','ancho', 'habilitacionurbana.nombre', 'habilitacionurbana.siglas','cochera.nombrehabilitacionurbana',
                'cochera.direccion', 'cochera.latitud', 'cochera.longitud', 'descripcion', 'path', 'pathArchivos',
                'cochera.foto','persona.nombres', 'ubigeo.ubigeo', 'cochera.nmensajes', 'cochera.ubigeo_id as idubigeo',
                'cochera.habilitacionurbana_id as idhabilitacionurbana', 'cochera.persona_id as idpersona',
                'contrato', 'estadocontrato', 'cochera.estado', 'referencia')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'cochera.habilitacionurbana_id')
                ->where('cochera.id','=',$id)->first();
            if ($cochera !== null && $cochera !== '') {
                $cocheradto->setCochera($cochera); // ingreso de la cochera
                $persona = Persona::FindOrFail($cochera->idpersona);
                $cocheradto->setPersona($persona); // ingreso del dueño del la cochera

                // habilitacionurbana
                $habilitacionurbana = HabilitacionUrbana::FindOrFail($cochera->idhabilitacionurbana);
                $cocheradto->setHabilitacionUrbana($habilitacionurbana);
                //end habilitacionurbana

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($cochera->idubigeo); // siempre es el ubigeo distrito
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
                $cocheradto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('cocherafoto', 'cocherafoto.foto_id', '=', 'foto.id')
                        ->where('cocherafoto.cochera_id', $id)->get();
                $cocheradto->setFotos($fotos); // ingreso de las fotos de la cochera
                // archivos
                $archivos = CocheraArchivo::select('cocheraarchivo.id', 'cocheraarchivo.cochera_id', 'cocheraarchivo.nombre',
                    'cocheraarchivo.archivo', 'cocheraarchivo.tipoarchivo', 'cocheraarchivo.estado')
                    ->join('cochera', 'cochera.id', '=', 'cocheraarchivo.cochera_id')
                    ->where('cocheraarchivo.cochera_id', $id)->get();
                $cocheradto->setArchivos($archivos); // ingreso de los archivos de la cochera
                // servicios
                $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
                    ->join('cocheraservicio', 'cocheraservicio.servicio_id', '=', 'servicios.id')
                    ->where('cocheraservicio.cochera_id', $id)->get();
                $cocheradto->setServicios($servicios);
                $cocheraservicios = CocheraServicio::select('cocheraservicio.id','cocheraservicio.cochera_id',
                                'cocheraservicio.servicio_id','cocheraservicio.estado')
                                ->where('cocheraservicio.cochera_id',$id)->get();
                $cocheradto->setCocheraServicio($cocheraservicios);
                
                /*$nmensajes = CocheraMensaje::where([['cochera_id','=',$cochera->id],['estado','=',true]])->count();
                $cocheradto->setnMensajes($nmensajes);*/

                //$persona = Persona::FindOrFail($id);
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($cocheradto);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron cocheras');
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
            $cochera = Cochera::FindOrFail($id);
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
                'referencia' => $request->referencia,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'pathArchivos' => $request->pathArchivos,
                'foto' => $request->foto,
                'nmensajes' => $request->nmensajes,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ];
            $cochera->fill($input)->save();
            $serviciosList = $request->input('serviciosList.*');
            $serviciosLista = [];
            /*Todos los serviciosList no muestran sus atributos asi que le convertimos
            mediante su respectivo modelo "Servicios*/
            foreach($serviciosList as $servicio) {
                $serviciomodel = new Servicios();
                $serviciomodel->fill($servicio);
                $serviciosLista[] = $serviciomodel;
            }
            // Eliminacion de cocheraservicio (6 8 16)
            $cocheraservicioList = $request->input('cocheraservicioList.*');// id = 6 y id = 16
            $ocheraserviciosLista = [];
            foreach($cocheraservicioList as $cocheraservicio) {
                $cocheraservicioModel = new CocheraServicio();
                $cocheraservicioModel->fill($cocheraservicio);
                $cocheraserviciosLista[] = $cocheraservicioModel;
            }
            $cocheraservicios = CocheraServicio::where('cochera_id',$cochera->id)->get();
            $esNuevo = false;
            $cocheraserviciosEliminados = [];
            foreach($cocheraservicios as $_cocheraservicio) {
                $esNuevo = false;
                foreach($cocheraserviciosLista as $cocheraservicioLista) {
                    if($_cocheraservicio->id == $cocheraservicioLista->id) {
                        $esNuevo = true;
                    }
                }
                if(!$esNuevo) {
                    $cocheraserviciosEliminados[] = $_cocheraservicio;
                    $_cocheraservicio->delete();
                    //$cocheraservice = CocheraServicio::FindOrFail($_cocheraservicio->id);
                    //$cocheraservice->delete();
                }
            }
            // comparamos
            // $cocheraservicioeliminadosId = array_diff($cocheraserviciosId, $);
            // end eliminacion cocheraservicio

            // agregar nuevo cocheraservicio
            $esNuevo = true;
            $serviciosNuevo = [];
            foreach($serviciosLista as $servicioLista) {
                $esNuevo = true;
                foreach($cocheraservicios as $cocheraservicio) {
                    if($servicioLista->id == $cocheraservicio->servicio_id) {
                        $esNuevo = false;
                    }
                }
                if($esNuevo) {
                    $serviciosNuevo[] = $servicioLista;
                    $cocheraservicionuevo = CocheraServicio::create([
                        'cochera_id' => $cochera->id,
                        'servicio_id'=> $servicioLista->id,
                        'estado' => true
                    ]);
                }
            }
            
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $cocherafoto = CocheraFoto::create([
                    'cochera_id' => $cochera->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }

            // archivos
            foreach ($request->archivosList as $archivo) {
                CocheraArchivo::create([
                    'cochera_id' => $cochera->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }

            // $cochera = Cochera::FindOrFail($id);
            // $input = $request->all();
            // $cochera->fill($input)->save();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La cochera: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($cochera);
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
            $cocheramensaje = CocheraMensaje::where('cochera_id', $id)->delete();
            // luego eliminar los servicios
            $cocheraservicio = CocheraServicio::where('cochera_id', $id)->delete();
            // despues eliminar las fotos
            $cocherafoto = CocheraFoto::where('cochera_id', $id)->delete();
            $fotos = Foto::join('cocherafoto', 'cocherafoto.foto_id', '=', 'foto.id')
                    ->where('cocherafoto.cochera_id', $id)->delete();
            // seguidamente los archivos
            $cocheraarchivo = CocheraArchivo::where('cochera_id', $id)->delete();
            // finalmente la cochera
            $cochera = Cochera::FindOrFail($id);
            $cochera->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La cochera: código: '.$cochera->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($cochera);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);

        // $cochera = Cochera::FindOrFail($id);
        // Cochera::where('id', $id)->update(['estado'=>!$cochera->estado]);
    }
}

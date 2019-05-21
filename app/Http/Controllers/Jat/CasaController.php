<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\CasaFoto;
use App\Models\CasaArchivo;
use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\Venta;
// use App\Models\CasaMensaje;
use App\Models\Servicios;
use App\Models\CasaServicio;
use App\Models\CasaMensaje;
use App\Models\Ubigeo;
use App\Models\HabilitacionUrbana;
use App\Dto\CasaDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

use App\Exceptions\Handler;
use Illuminate\Database\QueryException;
use App\EntityWeb\Entidades\Casas\CasaTO;
use App\EntityWeb\Utils\RespuestaWebTO;

class CasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $casas = Casa::orderBy('codigo')->get();
        return response()->json($casas, 200);
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

    // listo todas las casas disponibles para cualquier tipo contrato (venta o alquiler)
    public function listarCasasParaTipoContrato(Request $request)
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
            if ($condicion!== 'error') { // CasaTO
                $casas = Casa::select('casa.id', 'casa.foto', 'persona.nombres as propietario', 
                'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 
                'casa.direccion', 'largo', 'ancho', 'casa.codigo', 'precioadquisicion',
                'preciocontrato', 'ganancia', 'npisos', 'ncuartos', 'nbanios', 'tjardin',
                'tcochera', 'casa.contrato', 'casa.nmensajes', 'casa.estadocontrato', 'casa.estado',
                'ubigeo.rutaubigeo as ubicacion')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['casa.estado','=',true], ['casa.estadocontrato','=','L'],
                ['casa.codigo','like','%'.($request->codigo).'%'], ['casa.contrato','=',$request->contrato], 
                ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($casas!==null && !$casas->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($casas);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron casas');
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

    public function listarCasas(Request $request)
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
            } // CasaTO
            $casas = Casa::select('casa.id', 'casa.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 'casa.direccion',
            'largo', 'ancho', 'casa.codigo', 'precioadquisicion', 'preciocontrato', 'ganancia',
            'npisos', 'ncuartos', 'nbanios', 'tjardin', 'tcochera', 'casa.contrato', 'casa.nmensajes',
            'casa.estadocontrato', 'casa.estado', 'ubigeo.rutaubigeo as ubicacion')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->whereIn('casa.estado', $estados)->get();

            if ($casas!==null && !$casas->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($casas);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron casas');
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

    public function listarCasasPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // CasaTO
            $casas = Casa::select('casa.id', 'casa.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 'casa.direccion',
            'largo', 'ancho', 'casa.codigo', 'precioadquisicion', 'preciocontrato', 'ganancia',
            'npisos', 'ncuartos', 'nbanios', 'tjardin', 'tcochera', 'casa.contrato', 'casa.nmensajes', 
            'casa.estadocontrato', 'casa.estado', 'ubigeo.rutaubigeo as ubicacion')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->where('casa.estadocontrato', $request->input('estadoContrato'))->get();

            if ($casas!==null && !$casas->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($casas);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron casas');
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

    public function cambiarEstadoCasa(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado la casa en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $casa = Casa::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($casa!==null && $casa!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('La casa: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($casa);
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
    public function generarCodigoCasa() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoCasa();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo de la casa');
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

    public function nuevoCodigoCasa()
    {
        # code...
        $codigo = 'CA';
        $n = Casa::count();
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
            $casa = Casa::create([
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
                'npisos' => $request->npisos,
                'ncuartos' => $request->ncuartos,
                'nbanios' => $request->nbanios,
                'tjardin' => $request->tjardin,
                'tcochera' => $request->tcochera,
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
                $casaservicio = CasaServicio::create([
                    'casa_id' => $casa->id,
                    'servicio_id' => $servicios[$i],
                    'estado' => true
                ]);
            }

            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $casafoto = CasaFoto::create([
                    'casa_id' => $casa->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }

            foreach ($request->archivosList as $archivo) {
                CasaArchivo::create([
                    'casa_id' => $casa->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }

            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La casa: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($casa);
            // $casa = Casa::create($request->all());
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
            $casadto = new CasaDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();

            $casa = Casa::select('casa.id','nombres', 'casa.codigo', 'precioadquisicion', 'preciocontrato',
            'npisos', 'ganancia', 'ncuartos', 'nbanios', 'tjardin', 'tcochera','largo', 'ancho',
            'habilitacionurbana.nombre', 'habilitacionurbana.siglas', 'casa.direccion', 'casa.latitud',
            'casa.longitud', 'casa.referencia', 'descripcion', 'path', 'pathArchivos', 'casa.foto', 'persona.nombres',
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'casa.ubigeo_id as idubigeo', 
            'ubigeo.habilitacionurbana_id as idhabilitacionurbana', 'casa.persona_id as idpersona', 
            'contrato', 'estadocontrato', 'casa.estado')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->where('casa.id','=',$id)->first();
            if ($casa !== null && $casa !== '') {
                $casadto->setCasa($casa); // ingreso de la casa
                $persona = Persona::FindOrFail($casa->idpersona);
                $casadto->setPersona($persona); // ingreso del dueño del la casa

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($casa->idubigeo); // siempre es el ubigeo distrito
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
                $casadto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                // habilitacionurbana
                $habilitacionurbana = HabilitacionUrbana::FindOrFail($casa->idhabilitacionurbana);
                $casadto->setHabilitacionUrbana($habilitacionurbana);
                //end habilitacionurbana
                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('casafoto', 'casafoto.foto_id', '=', 'foto.id')
                        ->where('casafoto.casa_id', $id)->get();
                $casadto->setFotos($fotos); // ingreso de las fotos de la casa
                // archivos
                $archivos = CasaArchivo::select('casaarchivo.id', 'casaarchivo.casa_id', 'casaarchivo.nombre',
                    'casaarchivo.archivo', 'casaarchivo.tipoarchivo', 'casaarchivo.estado')
                    ->join('casa', 'casa.id', '=', 'casaarchivo.casa_id')
                    ->where('casaarchivo.casa_id', $id)->get();
                $casadto->setArchivos($archivos); // ingreso de los archivos de la casa
                // servicios
                $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
                    ->join('casaservicio', 'casaservicio.servicio_id', '=', 'servicios.id')
                    ->where('casaservicio.casa_id', $id)->get();
                $casadto->setServicios($servicios);
                $casaservicios = CasaServicio::select('casaservicio.id','casaservicio.casa_id',
                                'casaservicio.servicio_id','casaservicio.estado')
                                ->where('casaservicio.casa_id',$id)->get();
                $casadto->setCasaServicio($casaservicios);

                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($casadto);
                
                /*$nmensajes = CasaMensaje::where([['casa_id','=',$casa->id],['estado','=',true]])->count();
                $casadto->setnMensajes($nmensajes);*/

                //$persona = Persona::FindOrFail($id);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron casas');
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
            $casa = Casa::FindOrFail($id);
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
                'npisos' => $request->npisos,
                'ncuartos' => $request->ncuartos,
                'nbanios' => $request->nbanios,
                'tjardin' => $request->tjardin,
                'tcochera' => $request->tcochera,
                'referencia' => $request->referencia,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'pathArchivos' => $request->pathArchivos,
                'foto' => $request->foto,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ];
            $casa->fill($input)->save();
            $serviciosList = $request->input('serviciosList.*');
            $serviciosLista = [];
            /*Todos los serviciosList no muestran sus atributos asi que le convertimos
            mediante su respectivo modelo "Servicios*/
            foreach($serviciosList as $servicio) {
                $serviciomodel = new Servicios();
                $serviciomodel->fill($servicio);
                $serviciosLista[] = $serviciomodel;
            }
            // Eliminacion de casaservicio (6 8 16)
            $casaservicioList = $request->input('casaservicioList.*');// id = 6 y id = 16
            $casaserviciosLista = [];
            foreach($casaservicioList as $casaservicio) {
                $casaservicioModel = new CasaServicio();
                $casaservicioModel->fill($casaservicio);
                $casaserviciosLista[] = $casaservicioModel;
            }
            $casaservicios = CasaServicio::where('casa_id',$casa->id)->get();
            $esNuevo = false;
            $casaserviciosEliminados = [];
            foreach($casaservicios as $_casaservicio) {
                $esNuevo = false;
                foreach($casaserviciosLista as $casaservicioLista) {
                    if($_casaservicio->id == $casaservicioLista->id) {
                        $esNuevo = true;
                    }
                }
                if(!$esNuevo) {
                    $casaserviciosEliminados[] = $_casaservicio;
                    $_casaservicio->delete();
                    //$casaservice = CasaServicio::FindOrFail($_casaservicio->id);
                    //$casaservice->delete();
                }
            }
            // comparamos
            // $casaservicioeliminadosId = array_diff($casaserviciosId, $);
            // end eliminacion casaservicio

            // agregar nuevo casaservicio
            $esNuevo = true;
            $serviciosNuevo = [];
            foreach($serviciosLista as $servicioLista) {
                $esNuevo = true;
                foreach($casaservicios as $casaservicio) {
                    if($servicioLista->id == $casaservicio->servicio_id) {
                        $esNuevo = false;
                    }
                }
                if($esNuevo) {
                    $serviciosNuevo[] = $servicioLista;
                    $casaservicionuevo = CasaServicio::create([
                        'casa_id' => $casa->id,
                        'servicio_id'=> $servicioLista->id,
                        'estado' => true
                    ]);
                }
            }

            // fotos
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $casafoto = CasaFoto::create([
                    'casa_id' => $casa->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            
            // archivos
            foreach ($request->archivosList as $archivo) {
                CasaArchivo::create([
                    'casa_id' => $casa->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }
            
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La casa: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($casa);
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
            $casamensaje = CasaMensaje::where('casa_id', $id)->delete();
            // luego eliminar los servicios
            $casaservicio = CasaServicio::where('casa_id', $id)->delete();
            // despues eliminar las fotos
            $casafoto = CasaFoto::where('casa_id', $id)->delete();
            $fotos = Foto::join('casafoto', 'casafoto.foto_id', '=', 'foto.id')
                    ->where('casafoto.casa_id', $id)->delete();
            // seguidamente los archivos
            $casaarchivo = CasaArchivo::where('casa_id', $id)->delete();
            // finalmente la casa
            $casa = Casa::FindOrFail($id);
            $casa->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La casa: código: '.$casa->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($casa);
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

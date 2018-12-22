<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Local;
use App\Models\LocalFoto;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Servicios;
use App\Models\LocalServicio;
use App\Models\LocalMensaje;
use App\Models\Ubigeo;
use App\Dto\LocalDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

use App\EntityWeb\Utils\RespuestaWebTO;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $locales = Local::select('local.id', 'persona.nombres', 'precio', 'largo', 'ancho', 
        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
        'local.foto', 'local.nmensajes', 'local.estado')
        // DB::raw('(CASE WHEN (localmensaje.estado=1) then (count(*)) else 0 end) as nmensajes')
        // DB::raw('count(*) as totalmensajes'))
        ->join('persona', 'persona.id', '=', 'local.persona_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')->get();
        
        return response()->json($locales);
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
        // return response()->json($condicion, 200);
        return $condicion;
    }

    // listo todos los locales disponibles para cualquier tipo contrato (venta o alquiler)
    public function listarLocalesParaTipoContrato(Request $request)
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
            if ($condicion!== 'error') { // LocalTO
                $locales = Local::select('local.id', 'local.foto', 'persona.nombres as propietario', 
                    'largo', 'ancho', 'local.codigo', 'preciocompra', 'preciocontrato', 'ganancia', 
                    'ubigeo.ubigeo as ubicacion', 'local.direccion', 'tbanio', 'local.contrato', 
                    'local.estadocontrato', 'local.estado')
                    ->join('persona', 'persona.id', '=', 'local.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id') 
                    ->where([['local.estado','=',true], ['local.estadocontrato','=','L'],
                        ['local.codigo','like','%'.($request->codigo).'%'], ['local.contrato','=',$request->contrato], 
                        ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($locales!==null && !$locales->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($locales);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron locales');
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

    public function listarLocales(Request $request)
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
            } // LocalTO
            $locales = Local::select('local.id', 'local.foto', 'persona.nombres as propietario', 
            'largo', 'ancho', 'local.codigo', 'preciocompra', 'preciocontrato', 'ganancia', 
            'ubigeo.ubigeo as ubicacion', 'local.direccion', 'tbanio', 'local.contrato', 
            'local.estadocontrato', 'local.estado')
            ->join('persona', 'persona.id', '=', 'local.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id') 
            ->whereIn('local.estado', $estados)->get();

            if ($locales!==null && !$locales->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($locales);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron locales');
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

    public function listarLocalesPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // LocalTO
            $locales = Local::select('local.id', 'local.foto', 'persona.nombres as propietario', 
            'largo', 'ancho', 'local.codigo', 'preciocompra', 'preciocontrato', 'ganancia', 
            'ubigeo.ubigeo as ubicacion', 'local.direccion', 'tbanio', 'local.contrato', 
            'local.estadocontrato', 'local.estado')
            ->join('persona', 'persona.id', '=', 'local.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id') 
            ->where('local.estadocontrato', $request->input('estadoContrato'))->get();

            if ($locales!==null && !$locales->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($locales);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron locales');
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

    public function cambiarEstadoLocal(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado el local en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $local = Local::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($local!==null && $local!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El local: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($local);
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
    public function generarCodigoLocal() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoLocal();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo del local');
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
    
    public function nuevoCodigoLocal()
    {
        # code...
        $codigo = 'LC';
        $n = Local::count();
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
            $local = Local::create([
                'persona_id' => $request->input('persona_id.id'),
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'preciocompra' => $request->preciocompra,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'direccion' => $request->direccion,
                'tbanio' => $request->tbanio,
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
                $localservicio = LocalServicio::create([
                    'local_id' => $local->id,
                    'servicio_id' => $servicios[$i],
                    'estado' => true
                ]);
            }
    
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $localfoto = LocalFoto::create([
                    'local_id' => $local->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El local: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($local);
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
    public function busqueda(Request $request)
    {
        # code...
        if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['local.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
                $request->input('ubigeo_id.ubigeo') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['local.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['local.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && 
        $request->input('ubigeo_id.ubigeo') != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('local.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->input('ubigeo_id.ubigeo') != null && 
                $request->input('ubigeo_id.ubigeo') != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($locales);
    }

    public function mostrarlocales(Request $request)
    {
        # code...
        $locales = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                    if ($request->input('rangoprecio') != null) {
                        // locales con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // locales con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])->get();
                    }
                } else { // distrito = null
                    if ($request->input('rangoprecio') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                    }
                }
            } else { // != provincia
                if ($request->input('rangoprecio') != null) {
                    // locales del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                    'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'local.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                } else {
                    // locales del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                    'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'local.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                }
            }
        }
        return response()->json($locales);
    }

    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $localdto = new LocalDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();
            
            $local = Local::select('local.id', 'nombres', 'local.codigo', 'preciocompra', 'preciocontrato', 
                'largo', 'ancho', 'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 
                'path','local.foto', 'contrato', 'estadocontrato', 'local.estado', 'local.persona_id as idpersona', 
                'local.ubigeo_id as idubigeo')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('local.id','=',$id)->first();
            if ($local !== null && $local !== '') {
                $localdto->setLocal($local);
                $persona = Persona::FindOrFail($local->idpersona);
                $localdto->setPersona($persona);

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($local->idubigeo); // siempre es el ubigeo distrito
                $ubigeodto->setUbigeo($ubigeo);
                $codigo = $ubigeo->codigo;
                $subsdepartamento = substr($codigo, 0, 2)."00000000";
                $subsprovincia = substr($codigo, 0, 4)."000000";

                $ubigeos = Ubigeo::whereIn('codigo', [$subsdepartamento, $subsprovincia])->get();

                $departamento = $ubigeos[0];
                $provincia = $ubigeos[1];
                $ubigeodetalledto->setDepartamento($departamento);
                $ubigeodetalledto->setProvincia($provincia);
                $ubigeodetalledto->setUbigeo($ubigeodto);
                $localdto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('localfoto', 'localfoto.foto_id', '=', 'foto.id')
                        ->where('localfoto.local_id', $id)->get();
                $localdto->setFotos($fotos);
                $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
                    ->join('localservicio', 'localservicio.servicio_id', '=', 'servicios.id')
                    ->where('localservicio.local_id', $id)->get();
                $localdto->setServicios($servicios);
                $localservicios = LocalServicio::select('localservicio.id','localservicio.local_id',
                                'localservicio.servicio_id','localservicio.estado')
                                ->where('localservicio.local_id',$id)->get();
                $localdto->setLocalServicio($localservicios);

                $nmensajes = LocalMensaje::where([['local_id','=',$local->id],['estado','=',true]])->count();
                $localdto->setnMensajes($nmensajes);
                //$persona = Persona::FindOrFail($id);
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($localdto);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron locales');
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
            $local = Local::FindOrFail($id);
            $input = [
                'persona_id' => $request->input('persona_id.id'),
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'preciocompra' => $request->preciocompra,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'direccion' => $request->direccion,
                'tbanio' => $request->tbanio,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'foto' => $request->foto,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ];
            $local->fill($input)->save();
            
            $serviciosList = $request->input('serviciosList.*');
            $serviciosLista = [];
            /*Todos los serviciosList no muestran sus atributos asi que le convertimos
            mediante su respectivo modelo "Servicios*/
            foreach($serviciosList as $servicio) {
                $serviciomodel = new Servicios();
                $serviciomodel->fill($servicio);
                $serviciosLista[] = $serviciomodel;
            }
            // Eliminacion de localservicio (6 8 16)
            $localservicioList = $request->input('localservicioList.*');// id = 6 y id = 16
            $localserviciosLista = [];
            foreach($localservicioList as $localservicio) {
                $localservicioModel = new LocalServicio();
                $localservicioModel->fill($localservicio);
                $localserviciosLista[] = $localservicioModel;
            }
            $localservicios = LocalServicio::where('local_id',$local->id)->get();
            $esNuevo = false;
            $localserviciosEliminados = [];
            foreach($localservicios as $_localservicio) {
                $esNuevo = false;
                foreach($localserviciosLista as $localservicioLista) {
                    if($_localservicio->id == $localservicioLista->id) {
                        $esNuevo = true;
                    }
                }
                if(!$esNuevo) {
                    $localserviciosEliminados[] = $_localservicio;
                    $_localservicio->delete();
                    //$localservice = LocalServicio::FindOrFail($_localservicio->id);
                    //$localservice->delete();
                }
            }
            // comparamos
            // $localservicioeliminadosId = array_diff($localserviciosId, $);
            // end eliminacion localservicio

            // agregar nuevo localservicio
            $esNuevo = true;
            $serviciosNuevo = [];
            foreach($serviciosLista as $servicioLista) {
                $esNuevo = true;
                foreach($localservicios as $localservicio) {
                    if($servicioLista->id == $localservicio->servicio_id) {
                        $esNuevo = false;
                    }
                }
                if($esNuevo) {
                    $serviciosNuevo[] = $servicioLista;
                    $localservicionuevo = LocalServicio::create([
                        'local_id' => $local->id,
                        'servicio_id'=> $servicioLista->id,
                        'estado' => true
                    ]);
                }
            }
            
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $localfoto = LocalFoto::create([
                    'local_id' => $local->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El local: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($local);
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
            $localmensaje = LocalMensaje::where('local_id', $id)->delete();
            // luego eliminar los servicios
            $localservicio = LocalServicio::where('local_id', $id)->delete();
            // despues eliminar las fotos
            $localfoto = LocalFoto::where('local_id', $id)->delete();
            $fotos = Foto::join('localfoto', 'localfoto.foto_id', '=', 'foto.id')
                    ->where('localfoto.local_id', $id)->delete();
            // finalmente el local
            $local = Local::FindOrFail($id);
            $local->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El local: código: '.$local->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($local);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);

        // $local = Local::FindOrFail($id);
        // Local::where('id', $id)->update(['estado'=>!$local->estado]);
    }
}

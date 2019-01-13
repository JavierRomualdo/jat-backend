<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\CasaFoto;
use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\Venta;
// use App\Models\CasaMensaje;
use App\Models\Servicios;
use App\Models\CasaServicio;
use App\Models\CasaMensaje;
use App\Models\Ubigeo;
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
        //$persona_id, $casaservicio_id
        $casas = Casa::select('casa.id','persona.nombres','precio','npisos','ncuartos', 'nbanios',
            'tjardin', 'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'descripcion', 
            'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.nmensajes', 'casa.estado')
        // DB::raw('(CASE WHEN (casamensaje.estado=1) then (count(casamensaje.estado)) else (0) end) as nmensajesactivados'),
        // DB::raw("(select count(*)  as nmensajesactivados from casamensaje where 'estado' = 1"),
        // DB::raw('count(*) as totalmensajes')
        ->join('persona', 'persona.id', '=', 'casa.persona_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')->get();

        //$casas = Casa::get();
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
                    'ubigeo.ubigeo as ubicacion', 'casa.direccion', 'largo', 'ancho', 'casa.codigo', 
                    'precioadquisicion', 'preciocontrato', 'ganancia', 'npisos', 'ncuartos', 'nbanios', 
                    'tjardin', 'tcochera', 'casa.contrato', 'casa.nmensajes', 'casa.estadocontrato', 
                    'casa.estado')
                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id') 
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
            'ubigeo.ubigeo as ubicacion', 'casa.direccion', 'largo', 'ancho', 'casa.codigo', 
            'precioadquisicion', 'preciocontrato', 'ganancia', 'npisos', 'ncuartos', 'nbanios', 
            'tjardin', 'tcochera', 'casa.contrato', 'casa.nmensajes', 'casa.estadocontrato', 
            'casa.estado')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id') 
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
            'ubigeo.ubigeo as ubicacion', 'casa.direccion', 'largo', 'ancho', 'casa.codigo', 
            'precioadquisicion', 'preciocontrato', 'ganancia', 'npisos', 'ncuartos', 'nbanios', 
            'tjardin', 'tcochera', 'casa.contrato', 'casa.nmensajes', 'casa.estadocontrato', 
            'casa.estado')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id') 
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
                'descripcion' => $request->descripcion,
                'path' => $request->path,
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
    public function busqueda(Request $request)
    {
        # code...
        if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'ubigeo.ubigeo','casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['casa.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion', 'descripcion', 'path',
                'casa.foto','persona.nombres','ubigeo.ubigeo','casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['casa.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['casa.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'ubigeo.ubigeo','casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where('casa.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '')) {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($casas);
    }

    public function mostrarpropiedades(Request $request)
    {
        # code...
        $casas = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                    if ($request->input('rangoprecio') != null) {
                        // casas con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                        'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                        'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // casas con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                        'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                        'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])->get();
                    }
                } else { // distrito = null
                    if ($request->input('rangoprecio') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                        'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                        'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                        'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                        'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'casa.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                    }
                }
            } else { // != provincia
                if ($request->input('rangoprecio') != null) {
                    // casas del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                    'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                    'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                } else {
                    // casas del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                    'tcochera','largo','ancho','casa.direccion','ubigeo.ubigeo', 'ubigeo.codigo','descripcion', 
                    'path', 'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'casa.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                }
            }
        }
        return response()->json($casas);
    }
    
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
                'casa.direccion', 'casa.latitud', 'casa.longitud', 'descripcion', 'path', 'casa.foto',
                'persona.nombres', 'ubigeo.ubigeo', 'casa.ubigeo_id as idubigeo', 'casa.persona_id as idpersona', 
                'contrato', 'estadocontrato', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
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

                $ubigeos = Ubigeo::whereIn('codigo', [$subsdepartamento, $subsprovincia])->get();

                $departamento = $ubigeos[0];
                $provincia = $ubigeos[1];
                $ubigeodetalledto->setDepartamento($departamento);
                $ubigeodetalledto->setProvincia($provincia);
                $ubigeodetalledto->setUbigeo($ubigeodto);
                $casadto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('casafoto', 'casafoto.foto_id', '=', 'foto.id')
                        ->where('casafoto.casa_id', $id)->get();
                $casadto->setFotos($fotos); // ingreso de las fotos de la casa
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
                'descripcion' => $request->descripcion,
                'path' => $request->path,
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
            
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $casafoto = CasaFoto::create([
                    'casa_id' => $casa->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }
            // $casa = Casa::FindOrFail($id);
            // $input = $request->all();
            // $casa->fill($input)->save();
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
        // $casa = Rol::FindOrFail($id);
        // $casa->delete();
    }
}

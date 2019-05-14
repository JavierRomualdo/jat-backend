<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\LoteFoto;
use App\Models\LoteArchivo;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Ubigeo;
use App\Models\HabilitacionUrbana;
use App\Models\LoteMensaje;
use App\Dto\LoteDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

use App\EntityWeb\Utils\RespuestaWebTO;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lotes = Lote::orderBy('codigo')->get();
        return response()->json($lotes);
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

    // listo todas los lotes disponibles para cualquier tipo contrato (venta o alquiler)
    public function listarLotesParaTipoContrato(Request $request)
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
            if ($condicion!== 'error') { // LoteTO
                $lotes = Lote::select('lote.id', 'lote.foto', 'persona.nombres as propietario', 
                'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas',
                'lote.direccion', 'precioadquisicion', 'preciocontrato', 'ganancia', 'largo', 'ancho',
                'lote.contrato', 'lote.estadocontrato', 'lote.codigo', 'lote.estado', 'lote.nmensajes')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['lote.estado','=',true], ['lote.estadocontrato','=','L'],
                   ['lote.codigo','like','%'.($request->codigo).'%'], ['lote.contrato','=',$request->contrato], 
                    ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($lotes!==null && !$lotes->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($lotes);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron lotes');
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

    public function listarLotes(Request $request)
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
            } // LoteTO
            $lotes = Lote::select('lote.id', 'lote.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas',
            'lote.direccion', 'precioadquisicion', 'preciocontrato', 'ganancia', 'largo',
            'ancho', 'lote.contrato', 'lote.estadocontrato', 'lote.codigo', 'lote.estado',
            'lote.nmensajes')
            ->join('persona', 'persona.id', '=', 'lote.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->whereIn('lote.estado', $estados)->get();

            if ($lotes!==null && !$lotes->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($lotes);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron lotes');
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

    public function listarLotesPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // LoteTO
            $lotes = Lote::select('lote.id', 'lote.foto', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as nombrehabilitacionurbana', 'habilitacionurbana.siglas', 
            'lote.direccion', 'precioadquisicion', 'preciocontrato', 'ganancia', 'largo',
            'ancho', 'lote.contrato', 'lote.estadocontrato', 'lote.codigo', 'lote.estado',
            'lote.nmensajes')
            ->join('persona', 'persona.id', '=', 'lote.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
            ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
            ->where('lote.estadocontrato', $request->input('estadoContrato'))->get();

            if ($lotes!==null && !$lotes->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($lotes);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron lotes');
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

    public function cambiarEstadoLote(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado el lote en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $lote = Lote::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($lote!==null && $lote!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El lote: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($lote);
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
    public function generarCodigoLote() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoLote();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo del lote');
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

    public function nuevoCodigoLote()
    {
        # code...
        $codigo = 'LT';
        $n = Lote::count();
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
            $lote = Lote::create([
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
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ]);
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $lotefoto = LoteFoto::create([
                    'lote_id' => $lote->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }

            foreach ($request->archivosList as $archivo) {
                LoteArchivo::create([
                    'lote_id' => $lote->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }

            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El lote: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($lote);
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
            $lotedto = new LoteDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();

            $lote = Lote::select('lote.id', 'nombres', 'lote.codigo','precioadquisicion', 'preciocontrato',
                    'largo', 'ancho', 'habilitacionurbana.nombre', 'habilitacionurbana.siglas',
                    'lote.nombrehabilitacionurbana', 'ubigeo.ubigeo', 'lote.direccion', 'lote.latitud', 
                    'lote.longitud', 'descripcion', 'path', 'pathArchivos', 'lote.foto', 'lote.estado', 'contrato', 
                    'estadocontrato', 'referencia', 'lote.persona_id as idpersona', 'lote.ubigeo_id as idubigeo',
                    'lote.habilitacionurbana_id as idhabilitacionurbana')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'lote.habilitacionurbana_id')
                    ->where('lote.id','=',$id)->first();
            if ($lote !== null && $lote !== '') {
                $lotedto->setLote($lote);
                $persona = Persona::FindOrFail($lote->idpersona);
                $lotedto->setPersona($persona);

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($lote->idubigeo); // siempre es el ubigeo distrito
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
                $lotedto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo

                // habilitacionurbana
                $habilitacionurbana = HabilitacionUrbana::FindOrFail($lote->idhabilitacionurbana);
                $lotedto->setHabilitacionUrbana($habilitacionurbana);
                //end habilitacionurbana
                $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                        ->join('lotefoto', 'lotefoto.foto_id', '=', 'foto.id')
                        ->where('lotefoto.lote_id', $id)->get();
                $lotedto->setFotos($fotos);
                // archivos
                $archivos = LoteArchivo::select('lotearchivo.id', 'lotearchivo.lote_id', 'lotearchivo.nombre',
                    'lotearchivo.archivo', 'lotearchivo.tipoarchivo', 'lotearchivo.estado')
                    ->join('lote', 'lote.id', '=', 'lotearchivo.lote_id')
                    ->where('lotearchivo.lote_id', $id)->get();
                $lotedto->setArchivos($archivos); // ingreso de los archivos del lote
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($lotedto);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron lotes');
            }
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
            $lote = Lote::FindOrFail($id);
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
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
                'estado' => $request->estado
            ];
            $lote->fill($input)->save();
            foreach ($request->fotosList as $foto) {
                $foto = Foto::create($foto);
                $lotefoto = LoteFoto::create([
                    'lote_id' => $lote->id,
                    'foto_id'=> $foto->id,
                    'estado' => true
                ]);
            }

            // archivos
            foreach ($request->archivosList as $archivo) {
                LoteArchivo::create([
                    'lote_id' => $lote->id,
                    'nombre' => $archivo["nombre"],
                    'archivo' => $archivo["archivo"],
                    'tipoarchivo' => $archivo["tipoarchivo"],
                    'estado' => $archivo["estado"]
                ]);
            }

            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El lote: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($lote);
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
            $lotemensaje = LoteMensaje::where('lote_id', $id)->delete();
            // despues eliminar las fotos
            $lotefoto = LoteFoto::where('lote_id', $id)->delete();
            $fotos = Foto::join('lotefoto', 'lotefoto.foto_id', '=', 'foto.id')
                    ->where('lotefoto.lote_id', $id)->delete();
            // seguidamente los archivos
            $lotearchivo = LoteArchivo::where('lote_id', $id)->delete();
            // finalmente el lote
            $lote = Lote::FindOrFail($id);
            $lote->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El lote: código: '.$lote->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($lote);
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

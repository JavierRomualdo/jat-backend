<?php

namespace App\Http\Controllers\Jat\Mensaje;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Alquiler;
// use App\Models\Apartamento;
use App\Models\CasaMensaje;
use App\Models\Casa;
use App\Models\CocheraMensaje;
use App\Models\Cochera;
use App\Models\HabitacionMensaje;
use App\Models\Habitacion;
use App\Models\LocalMensaje;
use App\Models\Local;
use App\Models\LoteMensaje;
use App\Models\Lote;
use App\EntityWeb\Utils\RespuestaWebTO;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listarMensajes(Request $request)
    {
        # code...
        /**
         * $request {propiedad: string, activos: boolean, propiedad_id: number }
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            // para los estados
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            }
            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = $this->listarMensajesCasa($request->input('propiedad_id'), $estados);
                    break;
                
                case 'Cochera':
                    # code...
                    $mensajes = $this->listarMensajesCochera($request->input('propiedad_id'), $estados);
                    break;
                
                case 'Habitación':
                    # code...
                    $mensajes = $this->listarMensajesHabitacion($request->input('propiedad_id'), $estados);
                    break;
                
                case 'Local':
                    # code...
                    $mensajes = $this->listarMensajesLocal($request->input('propiedad_id'), $estados);
                    break;

                case 'Lote':
                    # code...
                    $mensajes = $this->listarMensajesLote($request->input('propiedad_id'), $estados);
                    break;

                default:
                    # code...
                    break;
            }
            if ($mensajes!==null && !$mensajes->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($mensajes);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron mensajes ');
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

    public function listarMensajesCasa($casa_id, $estados)
    {
        # code...
        //MensajeTO
        $mensajes = CasaMensaje::select('id', 'nombres', 'telefono', 'email',
            'titulo', 'mensaje', 'estado')->where('casa_id', $casa_id)
            ->whereIn('estado', $estados)    
            ->orderBy('created_at','asc')->get();
        return $mensajes;
    }

    public function listarMensajesCochera($cochera_id, $estados)
    {
        # code...
        //MensajeTO
        $mensajes = CocheraMensaje::select('id', 'nombres', 'telefono', 'email',
            'titulo', 'mensaje', 'estado')->where('cochera_id', $cochera_id)
            ->whereIn('estado', $estados)    
            ->orderBy('created_at','asc')->get();
        return $mensajes;
    }

    public function listarMensajesHabitacion($habitacion_id, $estados)
    {
        # code...
        //MensajeTO
        $mensajes = HabitacionMensaje::select('id', 'nombres', 'telefono', 'email',
            'titulo', 'mensaje', 'estado')->where('habitacion_id', $habitacion_id)
            ->whereIn('estado', $estados)    
            ->orderBy('created_at','asc')->get();
        return $mensajes;
    }

    public function listarMensajesLocal($local_id, $estados)
    {
        # code...
        //MensajeTO
        $mensajes = LocalMensaje::select('id', 'nombres', 'telefono', 'email',
            'titulo', 'mensaje', 'estado')->where('local_id', $local_id)
            ->whereIn('estado', $estados)    
            ->orderBy('created_at','asc')->get();
        return $mensajes;
    }

    public function listarMensajesLote($lote_id, $estados)
    {
        # code...
        //MensajeTO
        $mensajes = LoteMensaje::select('id', 'nombres', 'telefono', 'email',
            'titulo', 'mensaje', 'estado')->where('lote_id', $lote_id)
            ->whereIn('estado', $estados)    
            ->orderBy('created_at','asc')->get();
        return $mensajes;
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
    public function store(Request $request)
    {
        //
        /**
         * $request {propiedad: string, mensaje: object }
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = $this->guardarMensajeCasa($request->input('mensaje'));
                    break;

                case 'Cochera':
                    # code...
                    $mensajes = $this->guardarMensajeCochera($request->input('mensaje'));
                    break;

                case 'Habitación':
                    # code...
                    $mensajes = $this->guardarMensajeHabitacion($request->input('mensaje'));
                    break;
                
                case 'Local':
                    # code...
                    $mensajes = $this->guardarMensajeLocal($request->input('mensaje'));
                    break;

                case 'Lote':
                    # code...
                    $mensajes = $this->guardarMensajeLote($request->input('mensaje'));
                    break;

                default:
                    # code...
                    break;
            }
            if ($mensajes!==null && !$mensajes->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('Su mensaje ha sido enviada');
                $respuesta->setExtraInfo($mensajes);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al guardar mensaje ');
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

    public function guardarMensajeCasa($mensaje)
    {
        # code...
        $mensaje = CasaMensaje::create($mensaje->all());
        $casa = Casa::where('id', $mensaje->casa_id)->first();
        Casa::where('id', $mensaje->casa_id)->update(['nmensajes'=>($casa->nmensajes + 1)]);
        
        return $mensaje;
    }

    public function guardarMensajeCochera($mensaje)
    {
        # code...
        $mensaje = CocheraMensaje::create($mensaje->all());
        $cochera = Cochera::where('id', $mensaje->cochera_id)->first();
        Cochera::where('id', $mensaje->cochera_id)->update(['nmensajes'=>($cochera->nmensajes + 1)]);
        
        return $mensaje;
    }

    public function guardarMensajeHabitacion($mensaje)
    {
        # code...
        $mensaje = HabitacionMensaje::create($mensaje->all());
        $habitacion = Habitacion::where('id', $mensaje->chabitacion_id)->first();
        Habitacion::where('id', $mensaje->habitacion_id)->update(['nmensajes'=>($habitacion->nmensajes + 1)]);
        
        return $mensaje;
    }

    public function guardarMensajeLocal($mensaje)
    {
        # code...
        $mensaje = LocalMensaje::create($mensaje->all());
        $local = Local::where('id', $mensaje->local_id)->first();
        Local::where('id', $mensaje->local_id)->update(['nmensajes'=>($local->nmensajes + 1)]);
        
        return $mensaje;
    }

    public function guardarMensajeLote($mensaje)
    {
        # code...
        $mensaje = LoteMensaje::create($mensaje->all());
        $lote = Lote::where('id', $mensaje->lote_id)->first();
        Lote::where('id', $mensaje->lote_id)->update(['nmensajes'=>($lote->nmensajes + 1)]);
        
        return $mensaje;
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
    }

    public function cambiarEstadoMensaje(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            $propiedad_id = $request->input('propiedad_id');
            $mensaje = $request->input('mensaje');
            $estado = $request->input('estado');
            $cantMensajesActual = $request->input('cantMensajesActual');
            $nmensajes = $estado ? ($cantMensajesActual + 1) : ($cantMensajesActual - 1);

            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = CasaMensaje::where('id', $mensaje['id'])->update(['estado'=>$estado]);
                    Casa::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Cochera':
                    # code...
                    $mensajes = CocheraMensaje::where('id', $mensaje['id'])->update(['estado'=>$estado]);
                    Cochera::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Habitación':
                    # code...
                    $mensajes = HabitacionMensaje::where('id', $mensaje['id'])->update(['estado'=>$estado]);
                    Habitacion::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;
                
                case 'Local':
                    # code...
                    $mensajes = LocalMensaje::where('id', $mensaje['id'])->update(['estado'=>$estado]);
                    Local::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Lote':
                    # code...
                    $mensajes = LoteMensaje::where('id', $mensaje['id'])->update(['estado'=>$estado]);
                    Lote::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;
                default:
                    # code...
                    break;
            }
            if ($mensajes!==null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El mensaje se ha '.( 
                    $request->input('estado') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($mensaje);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al guardar mensaje ');
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

    public function cambiarEstadoMensajes(Request $request)
    {
        # code...
        /**
         * $request {propiedad: string, propiedad_id: number, 
         *  nmensajes: number, listaMensajes: Array<Mensajes>, estado: boolean }
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            $listaIdsMensajes = $request->input('listaIdsMensajes');
            $propiedad_id = $request->input('propiedad_id');
            $estado = $request->input('estado');
            $cantMensajesActual = $request->input('cantMensajesActual');
            $nmensajes = $estado ? ($cantMensajesActual + count($listaIdsMensajes)) : ($cantMensajesActual - count($listaIdsMensajes));
            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = CasaMensaje::whereIn('id', $listaIdsMensajes)->update(['estado'=>$estado]);
                    Casa::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Cochera':
                    # code...
                    $mensajes = CocheraMensaje::whereIn('id', $listaIdsMensajes)->update(['estado'=>$estado]);
                    Cochera::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Habitación':
                    # code...
                    $mensajes = HabitacionMensaje::whereIn('id', $listaIdsMensajes)->update(['estado'=>$estado]);
                    Habitacion::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;
                
                case 'Local':
                    # code...
                    $mensajes = LocalMensaje::whereIn('id', $listaIdsMensajes)->update(['estado'=>$estado]);
                    Local::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                case 'Lote':
                    # code...
                    $mensajes = LoteMensaje::whereIn('id', $listaIdsMensajes)->update(['estado'=>$estado]);
                    Lote::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    break;

                default:
                    # code...
                    break;
            }
            if ($mensajes!==null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('Los mensajes se han '.( 
                    $request->input('estado') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($request->input('listaMensajes'));
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al '.( 
                    $request->input('estado') ? 'activar' : 'inactivar').' mensajes');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function eliminarMensajes(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            $listaMensajes = $request->input('listaMensajes');
            $listaIdsMensajes = $request->input('listaIdsMensajes');
            $propiedad_id = $request->input('propiedad_id');
            $cantMensajesActual = $request->input('cantMensajesActual');
            $cantMensajesActivados = $request->input('cantMensajesActivados');
            $nmensajes = $cantMensajesActual - $cantMensajesActivados;
            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = CasaMensaje::whereIn('id', $listaIdsMensajes)->delete();
                    if ($cantMensajesActivados != 0) {
                        Casa::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    }
                    break;
                case 'Cochera':
                    # code...
                    $mensajes = CocheraMensaje::whereIn('id', $listaIdsMensajes)->delete();
                    if ($cantMensajesActivados != 0) {
                        Cochera::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    }
                    break;
                case 'Habitación':
                    # code...
                    $mensajes = HabitacionMensaje::whereIn('id', $listaIdsMensajes)->delete();
                    if ($cantMensajesActivados != 0) {
                        Habitacion::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    }
                    break;
                case 'Local':
                    # code...
                    $mensajes = LocalMensaje::whereIn('id', $listaIdsMensajes)->delete();
                    if ($cantMensajesActivados != 0) {
                        Local::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    }
                    break;
                case 'Lote':
                    # code...
                    $mensajes = LoteMensaje::whereIn('id', $listaIdsMensajes)->delete();
                    if ($cantMensajesActivados != 0) {
                        Lote::where('id', $propiedad_id)->update(['nmensajes'=>$nmensajes]);
                    }
                    break;
                default:
                    # code...
                    break;
            }
            if ($mensajes!==null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('Los mensajes se han eliminado correctamente.');
                $respuesta->setExtraInfo($request->input('listaMensajes'));
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al eliminar mensajes ');
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

    public function eliminarMensaje(Request $request)
    {
        # code...
        /**
         * $request {propiedad: string, id: number }
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $mensajes = null;
            $mensaje = $request->input('mensaje');
            $propiedad_id = $request->input('propiedad_id');
            $cantMensajesActual = $request->input('cantMensajesActual');
            switch ($request->input('propiedad')) {
                case 'Casa':
                    # code...
                    $mensajes = CasaMensaje::where('id', $mensaje['id'])->delete();
                    if ($mensaje['estado']) {
                        Casa::where('id', $propiedad_id)->update(['nmensajes'=>($cantMensajesActual - 1)]);
                    }
                    break;
                case 'Cochera':
                    # code...
                    $mensajes = CocheraMensaje::where('id', $mensaje['id'])->delete();
                    if ($mensaje['estado']) {
                        Cochera::where('id', $propiedad_id)->update(['nmensajes'=>($cantMensajesActual - 1)]);
                    }
                    break;
                case 'Habitación':
                    # code...
                    $mensajes = HabitacionMensaje::where('id', $mensaje['id'])->delete();
                    if ($mensaje['estado']) {
                        Habitacion::where('id', $propiedad_id)->update(['nmensajes'=>($cantMensajesActual - 1)]);
                    }
                    break;
                case 'Local':
                    # code...
                    $mensajes = LocalMensaje::where('id', $mensaje['id'])->delete();
                    if ($mensaje['estado']) {
                        Local::where('id', $propiedad_id)->update(['nmensajes'=>($cantMensajesActual - 1)]);
                    }
                    break;
                case 'Lote':
                    # code...
                    $mensajes = LoteMensaje::where('id', $mensaje['id'])->delete();
                    if ($mensaje['estado']) {
                        Lote::where('id', $propiedad_id)->update(['nmensajes'=>($cantMensajesActual - 1)]);
                    }
                    break;
                default:
                    # code...
                    break;
            }
            if ($mensajes!==null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El mensaje se ha eliminado correctamente.');
                $respuesta->setExtraInfo($mensaje);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al eliminar mensaje ');
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

    public function destroy($id)
    {
        //
    }
}

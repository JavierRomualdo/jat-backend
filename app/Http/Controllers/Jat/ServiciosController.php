<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicios;
use App\EntityWeb\Utils\RespuestaWebTO;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $servicios = Servicios::all();
        return response()->json($servicios);
    }

    public function listarServicios(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            } // Servicios
            $servicios = Servicios::whereIn('servicios.estado', $estados)->get();
            if ($servicios!==null && !$servicios->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($servicios);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron servicios');
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

    public function cambiarEstadoServicio(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado el servicio en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $servicio = Servicios::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($servicio!==null && $servicio!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El servicio: '.$request->servicio.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($servicio);
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
    public function store(Request $request)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $servicio = Servicios::create($request->all());
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El servicio: '.$request->servicio.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($servicio);
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
    public function busqueda(Request $request) {
        if (($request->servicio != null && $request->servicio != '') && 
            $request->detalle != null && $request->detalle != '') {
            $servicios = Servicios::where('servicio','like','%'.($request->servicio).'%', 'and',
            'detalle','like','%'.($request->detalle).'%')->get();
        } else {
            if($request->servicio != null && $request->servicio != '') {
                $servicios = Servicios::where('servicio','like','%'.($request->servicio).'%')->get();
            } else {
                $servicios = Servicios::where('detalle','like','%'.($request->detalle).'%')->get();
            }
        }
        return response()->json($servicios);
    }

    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $servicio = Servicios::FindOrFail($id);
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($servicio);
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
            $servicio = Servicios::FindOrFail($id);
            $input = $request->all();
            $servicio->fill($input)->save();

            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El servicio: '.$request->servicio.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($servicio);
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
            $servicio = Servicios::FindOrFail($id);
            $servicio->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El servicio: '.$servicio->servicio.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($servicio);
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

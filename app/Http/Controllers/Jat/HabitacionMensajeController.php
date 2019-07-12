<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Http\Controllers\Controller;
use App\Models\HabitacionMensaje;

use App\Exceptions\Handler;
use Illuminate\Database\QueryException;
use App\EntityWeb\Utils\RespuestaWebTO;

class HabitacionMensajeController extends Controller
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
            $habitacionmensaje = HabitacionMensaje::create($request->all());
            $habitacion = Habitacion::where('id', $habitacionmensaje->habitacion_id)->first();
            Habitacion::where('id', $habitacionmensaje->habitacion_id)->update(['nmensajes'=>($habitacion->nmensajes + 1)]);
            
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('Mensaje, se ha guardado correctamente.');
            $respuesta->setExtraInfo($habitacionmensaje);
            // return response()->json($habitacionmensaje, 200); // 201
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrarhabitacionmensajes($habitacion_id, $estado)
    {
        # code...
        $habitacionmensaje = HabitacionMensaje::where([['habitacion_id','=',$habitacion_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($habitacionmensaje, 200);
    }

    public function cambiarestado($habitacion_id, $nmensajes, $estado) {
        if ($estado == 1) {
            Habitacion::where('id', $habitacion_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            Habitacion::where('id', $habitacion_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        HabitacionMensaje::where('id', $id)->update(['estado'=>$estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $habitacionmensaje = HabitacionMensaje::FindOrFail($id);
        $habitacion = Habitacion::where('id', $habitacionmensaje->habitacion_id)->first();
        Habitacion::where([['id', $habitacionmensaje->habitacion_id],['estado', true]])
            ->update(['nmensajes'=>($habitacion->nmensajes - 1)]);
        HabitacionMensaje::where('id', $id)->update(['estado'=>!$habitacionmensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

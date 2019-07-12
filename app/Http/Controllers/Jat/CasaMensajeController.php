<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\CasaMensaje;

use App\Exceptions\Handler;
use Illuminate\Database\QueryException;
use App\EntityWeb\Utils\RespuestaWebTO;

class CasaMensajeController extends Controller
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
            $casamensaje = CasaMensaje::create($request->all());
            $casa = Casa::where('id', $casamensaje->casa_id)->first();
            Casa::where('id', $casamensaje->casa_id)->update(['nmensajes'=>($casa->nmensajes + 1)]);
            
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('Mensaje, se ha guardado correctamente.');
            $respuesta->setExtraInfo($casamensaje);
            // return response()->json($casamensaje, 200); // 201
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
    public function mostrarcasamensajes($casa_id, $estado)
    {
        # code...
        $casamensaje = CasaMensaje::where([['casa_id','=',$casa_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($casamensaje, 200);
    }

    public function cambiarestado($casa_id, $nmensajes, $estado) {
        if ($estado == 1) {
            Casa::where('id', $casa_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            Casa::where('id', $casa_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        CasaMensaje::where('id', $id)->update(['estado'=>$estado]);
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
        $casamensaje = CasaMensaje::FindOrFail($id);
        $casa = Casa::where('id', $casamensaje->casa_id)->first();
        Casa::where([['id', $casamensaje->casa_id],['estado', true]])
            ->update(['nmensajes'=>($casa->nmensajes - 1)]);
        CasaMensaje::where('id', $id)->update(['estado'=>!$casamensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

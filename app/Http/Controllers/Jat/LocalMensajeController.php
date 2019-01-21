<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Local;
use App\Models\LocalMensaje;

class LocalMensajeController extends Controller
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
        $localmensaje = LocalMensaje::create($request->all());
        $local = Local::where('id', $localmensaje->local_id)->first();
        Local::where('id', $localmensaje->local_id)->update(['nmensajes'=>($local->nmensajes + 1)]);
        return response()->json($localmensaje, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrarlocalmensajes($local_id, $estado)
    {
        # code...
        $localmensaje = LocalMensaje::where([['local_id','=',$local_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($localmensaje, 200);
    }

    public function cambiarestado($local_id, $nmensajes, $estado) {
        if ($estado == 1) {
            Local::where('id', $local_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            Local::where('id', $local_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        LocalMensaje::where('id', $id)->update(['estado'=>$estado]);
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
        $localmensaje = LocalMensaje::FindOrFail($id);
        $local = Local::where('id', $localmensaje->local_id)->first();
        Local::where([['id', $localmensaje->local_id],['estado', true]])
            ->update(['nmensajes'=>($local->nmensajes - 1)]);
        LocalMensaje::where('id', $id)->update(['estado'=>!$localmensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

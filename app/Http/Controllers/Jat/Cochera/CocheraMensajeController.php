<?php

namespace App\Http\Controllers\Jat\Cochera;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cochera;
use App\Models\CocheraMensaje;

class CocheraMensajeController extends Controller
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
        $cocheramensaje = CocheraMensaje::create($request->all());
        $cochera = Cochera::where('id', $cocheramensaje->cochera_id)->first();
        Cochera::where('id', $cocheramensaje->cochera_id)->update(['nmensajes'=>($cochera->nmensajes + 1)]);
        return response()->json($cocheramensaje, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrarcocheramensajes($cochera_id, $estado)
    {
        # code...
        $cocheramensaje = CocheraMensaje::where([['cochera_id','=',$cochera_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($cocheramensaje, 200);
    }

    public function cambiarestado($cochera_id, $nmensajes, $estado) {
        if ($estado == 1) {
            Cochera::where('id', $cochera_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            Cochera::where('id', $cochera_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        CocheraMensaje::where('id', $id)->update(['estado'=>$estado]);
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
        $cocheramensaje = CocheraMensaje::FindOrFail($id);
        $cochera = Cochera::where('id', $cocheramensaje->cochera_id)->first();
        Cochera::where('id', $cocheramensaje->cochera_id)->update(['nmensajes'=>($cochera->nmensajes - 1)]);
        CocheraMensaje::where('id', $id)->update(['estado'=>!$cocheramensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

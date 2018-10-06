<?php

namespace App\Http\Controllers\Jat\ApartamentoCuarto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApartamentoCuarto;
use App\Models\ApartamentoCuartoMensaje;

class ApartamentoCuartoMensajeController extends Controller
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
        $apartamentocuartomensaje = ApartamentoCuartoMensaje::create($request->all());
        $apartamentocuarto = ApartamentoCuartoMensaje::where('id', $apartamentocuartomensaje->apartamentocuarto_id)->first();
        ApartamentoCuartoMensaje::where('id', $apartamentocuartomensaje->apartamentocuarto_id)->update(['nmensajes'=>($apartamentocuarto->nmensajes + 1)]);
        return response()->json($apartamentocuartomensaje, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrarapartamentocuartomensajes($apartamentocuarto_id, $estado)
    {
        # code...
        $apartamentocuartomensaje = ApartamentoCuartoMensaje::
            where([['apartamentocuarto_id','=',$apartamentocuarto_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($apartamentocuartomensaje, 200);
    }

    public function cambiarestado($apartamentocuarto_id, $nmensajes, $estado) {
        if ($estado == 1) {
            ApartamentoCuarto::where('id', $apartamentocuarto_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            ApartamentoCuarto::where('id', $apartamentocuarto_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        ApartamentoCuartoMensaje::where('id', $id)->update(['estado'=>$estado]);
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
        $apartamentocuartomensaje = ApartamentoCuartoMensaje::FindOrFail($id);
        $apartamentocuarto = ApartamentoCuarto::where('id', $apartamentocuartomensaje->apartamentocuarto_id)->first();
        ApartamentoCuarto::where('id', $apartamentocuartomensaje->apartamentocuarto_id)->update(['nmensajes'=>($apartamentocuarto->nmensajes - 1)]);
        ApartamentoCuartoMensaje::where('id', $id)->update(['estado'=>!$apartamentocuartomensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

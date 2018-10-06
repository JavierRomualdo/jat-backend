<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\LoteMensaje;

class LoteMensajeController extends Controller
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
        $lotemensaje = LoteMensaje::create($request->all());
        $lote = Lote::where('id', $lotemensaje->lote_id)->first();
        Lote::where('id', $lotemensaje->lote_id)->update(['nmensajes'=>($lote->nmensajes + 1)]);
        return response()->json($lotemensaje, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrarlotemensajes($lote_id, $estado)
    {
        # code...
        $lotemensaje = LoteMensaje::where([['lote_id','=',$lote_id],['estado','=',$estado]])
            ->orderBy('created_at','asc')->get();
        return response()->json($lotemensaje, 200);
    }

    public function cambiarestado($lote_id, $nmensajes, $estado) {
        if($estado == 1) {
            Lote::where('id', $lote_id)->update(['nmensajes'=>($nmensajes + 1)]);
        } else {
            Lote::where('id', $lote_id)->update(['nmensajes'=>($nmensajes - 1)]);
        }
        LoteMensaje::where('id', $id)->update(['estado'=>$estado]);
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
        $lotemensaje = LoteMensaje::FindOrFail($id);
        $lote = Lote::where('id', $lotemensaje->lote_id)->first();
        Lote::where('id', $lotemensaje->lote_id)->update(['nmensajes'=>($lote->nmensajes - 1)]);
        LoteMensaje::where('id', $id)->update(['estado'=>!$lotemensaje->estado]);
        return response()->json(['exito'=>'Mensaje leido con id: '.$id], 200);
    }
}

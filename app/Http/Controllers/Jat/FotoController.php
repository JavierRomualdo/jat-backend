<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Foto;
use App\Models\Casa;
use App\Models\Lote;
use App\Models\Local;
use App\Dto\FotoDto;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fotodto = new FotoDto();
        $fotocasa = Foto::select('foto.id','foto.nombre', 'foto.detalle', 'foto.estado')
            ->join('casafoto', 'foto_id', '=', 'foto.id')->first();
        $fotolote = Foto::select('foto.id','foto.nombre', 'foto.detalle', 'foto.estado')
            ->join('lotefoto', 'foto_id', '=', 'foto.id')->first();
        $fotohabitacion = Foto::select('foto.id','foto.nombre', 'foto.detalle', 'foto.estado')
            ->join('habitacionfoto', 'foto_id', '=', 'foto.id')->first();
        $fotolocal = Foto::select('foto.id','foto.nombre', 'foto.detalle', 'foto.estado')
            ->join('localfoto', 'foto_id', '=', 'foto.id')->first();
        $fotodto->setFotoCasa($fotocasa);
        $fotodto->setFotoLote($fotolote);
        $fotodto->setFotoHabitacion($fotohabitacion);
        $fotodto->setFotoLocal($fotolocal);

        return response()->json($fotodto);
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
        $foto = Foto::FindOrFail($id);
        $input = $request->all();
        $foto->fill($input)->save();
        return response()->json($foto, 200);
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
        $foto = Foto::FindOrFail($id);
        $foto->delete();
        return response()->json(['exito'=>'Foto eliminado con id: '.$id], 200);
    }
}

<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicios;

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
        $servicios = Servicios::get();
        return response()->json($servicios, 200);
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
        $servicio = Servicios::create($request->all());
        return response()->json($servicio, 201);
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
        $servicio = Servicios::FindOrFail($id);
        return response()->json($servicio, 200);
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
        $servicio = Servicios::FindOrFail($id);
        $input = $request->all();
        $servicio->fill($input)->save();
        return response()->json($servicio, 200);
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
        $servicio = Servicios::FindOrFail($id);
        $servicio->delete();
        return response()->json(['exito'=>'Servicio eliminado con id: '.$id], 200);
    }
}

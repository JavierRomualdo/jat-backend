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
        $servicios = Servicios::all();
        return response()->json($servicios);
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
        Rol::where('id', $id)->update(['estado'=>!$servicio->estado]);
        //$servicio->delete();
        return response()->json(['exito'=>'Servicio eliminado con id: '.$id], 200);
    }
}

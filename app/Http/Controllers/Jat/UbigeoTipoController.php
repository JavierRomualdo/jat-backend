<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UbigeoTipo;

class UbigeoTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tipoubigeos = UbigeoTipo::all();
        return response()->json($tipoubigeos);
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
        $tipoubigeo = UbigeoTipo::create($request->all());
        return response()->json($tipoubigeo, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function buscartipoubigeo(Request $request)
    {
        # code...
        $tipoubigeo = $request->input('tipoubigeo');
        $tiposubigeo = UbigeoTipo::where('tipoubigeo','like','%'.$tipoubigeo.'%')->get();
        return response()->json($tiposubigeo, 200);
    }
    public function show($id)
    {
        //
        $tipoubigeo = UbigeoTipo::FindOrFail($id);
        return response()->json($tipoubigeo, 200);
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
        $tipoubigeo= UbigeoTipo::FindOrFail($id);
        $input = $request->all();
        $tipoubigeo->fill($input)->save();
        return response()->json($tipoubigeo, 200);
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
        $tipoubigeo = UbigeoTipo::FindOrFail($id);
        UbigeoTipo::where('id', $id)->update(['estado'=>!$tipoubigeo->estado]);
        // $rol = Rol::FindOrFail($id);
        // $rol->delete();
        return response()->json(['exito'=>'Rol eliminado con id: '.$id], 200);
    }
}

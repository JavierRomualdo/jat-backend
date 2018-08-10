<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles = Rol::all();
        return response()->json($roles);
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
        $rol = Rol::create($request->all());
        return response()->json($rol, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busqueda(Request $request) {
        if (($request->rol != null && $request->rol != '') && 
            ($request->permiso != null && $request->permiso != '')) {
            $roles = Rol::where('rol','like','%'.($request->rol).'%','and',
            'permiso','like','%'.($request->permiso).'%')->get();
        } else {
            if($request->rol != null && $request->rol != '') {
                $roles = Rol::where('rol','like','%'.($request->rol).'%')->get();
            } else {
                $roles = Rol::where('permiso','like','%'.($request->permiso).'%')->get();
            }
        }
        return response()->json($roles);
    }
    
    public function show($id)
    {
        //
        $rol = Rol::FindOrFail($id);
        return response()->json($rol, 200);
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
        $rol= Rol::FindOrFail($id);
        $input = $request->all();
        $rol->fill($input)->save();
        return response()->json($rol, 200);
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
        $rol = Rol::FindOrFail($id);
        Rol::where('id', $id)->update(['estado'=>!$rol->estado]);
        // $rol = Rol::FindOrFail($id);
        // $rol->delete();
        return response()->json(['exito'=>'Rol eliminado con id: '.$id], 200);
    }
}

<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\Persona;

class CasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$persona_id, $casaservicio_id
        $casas = Casa::select('casa.id','npisos','ncuartos', 'nbaños','tjardin','tcochera','largo',
        'ancho','casa.direccion','casa.ubicacion','foto','persona.nombres')
        ->join('persona','persona.id','=','casa.persona_id')
        ->get();

        //$casas = Casa::get();
        return response()->json($casas, 200);
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
        $casa = Casa::create($request->all());
        return response()->json($casa, 201);
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
        $casa = Casa::select('npisos', 'ncuartos', 'nbaños', 'tjardin', 'tcochera',
        'largo', 'ancho', 'casa.direccion', 'casa.ubicacion', 'foto', 'descripcion', 'nombres')
        ->join('persona','persona.id','=','casa.persona_id')
        ->where('casa.id','=',$id)->get();

        return response()->json($casa, 200);
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
        $casa = Casa::FindOrFail($id);
        $input = $request->all();
        $casa->fill($input)->save();
        return response()->json($casa, 200);
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
        $casa = Rol::FindOrFail($id);
        $casa->delete();
        return response()->json(['exito'=>'Casa eliminado con id: '.$id], 200);
    }
}

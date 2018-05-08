<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Persona;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$personas = Persona::get();
        $personas = Persona::select('persona.id','nombres','telefono','correo','direccion','ubicacion',
        'rol')->join('rol','rol.id','=','persona.rol_id')->get();
        return response()->json($personas, 200);
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
        $persona = Persona::create($request->all());
        return response()->json($persona, 201);
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
        $persona = Persona::select('persona.id','nombres','telefono','correo','direccion','ubicacion',
        'rol')->join('rol','rol.id','=','persona.rol_id')->where('persona.id','=',$id)->first();
        //$persona = Persona::FindOrFail($id);
        return response()->json($persona, 200);
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
        $persona = Persona::FindOrFail($id);
        $input = $request->all();
        $persona->fill($input)->save();
        return response()->json($persona, 200);
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
        $persona = Persona::FindOrFail($id);
        $persona->delete();
        /**"npisos": 1,
	"ncuartos": 4,
	"nbaños": 2,
	"tjardin": false,
	"tcochera": false,
	"largo": 30,
	"ancho": 10,
	"direccion": "Los Almendros 1",
	"ubicacion": "Castilla",
	"foto": "casa.jpg",
	"descripcion": "casa intermedio" */
    }
}

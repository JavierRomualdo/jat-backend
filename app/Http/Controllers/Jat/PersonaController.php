<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use App\Dto\PersonaDto;

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
        $personas = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
        'correo','direccion','ubicacion', 'persona.estado', 'rol')
        ->join('rol','rol.id','=','persona.rol_id')->get();
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
        $persona = Persona::create([
            'rol_id' => $request->input('rol_id.id'),
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'correo' => $request->correo,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'estado' => $request->estado
        ]);
        return response()->json($persona, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busqueda(Request $request) {
        if (($request->nombres != null && $request->nombres != '') && 
        ($request->dni != null && $request->dni != '') && 
        ($request->input('rol_id.id')!= null)) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('nombres','like','%'.($request->nombres).'%', 'and',
                    'dni','like','%'.($request->dni).'%','and',
                    'rol.id','=',$request->input('rol_id.id'))->get();
        } else if (($request->nombres != null && $request->nombres != '') && 
        ($request->dni != null && $request->dni != '')) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubicacion', 'persona.estado', 'rol')
            ->join('rol','rol.id','=','persona.rol_id')
            ->where('nombres','like','%'.($request->nombres).'%', 'and',
            'dni','like','%'.($request->dni).'%')->get();
        } else if (($request->nombres != null && $request->nombres != '') && 
        ($request->input('rol_id.id')!= null)) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('nombres','like','%'.($request->nombres).'%', 'and',
                    'rol.id','=',$request->input('rol_id.id'))->get();
        } else if (($request->dni != null && $request->dni != '') && 
        ($request->input('rol_id.id')!= null)) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('dni','like','%'.($request->dni).'%','and',
                    'rol.id','=',$request->input('rol_id.id'))->get();
        } else {
            if ($request->nombres != null && $request->nombres != '') {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('nombres','like','%'.($request->nombres).'%')->get();
            } else if($request->dni != null && $request->dni != '')  {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('dni','like','%'.($request->dni).'%')->get();
            } else {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubicacion', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->where('rol.id','=',$request->input('rol_id.id'))->get();
            }
        }
        return response()->json($persona);
    }

    public function show($id)
    {
        //
        $personadto = new PersonaDto();
        $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
        'correo','direccion','ubicacion', 'persona.estado', 'rol.id as idrol', 'rol')
        ->join('rol','rol.id','=','persona.rol_id')->where('persona.id','=',$id)->first();
        $personadto->setPersona($persona);
        $rol = Rol::FindOrFail($persona->idrol);
        $personadto->setRol($rol);
        //$persona = Persona::FindOrFail($id);
        return response()->json($personadto, 200);
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
        // $input = $request->all();
        $input = [
            'rol_id' => $request->input('rol_id.id'),
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'correo' => $request->correo,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'estado' => $request->estado
        ];

        // $persona = Persona::FindOrFail($id);
        // $input = $request->all();
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
        // $persona = Persona::FindOrFail($id);
        // $persona->delete();
        $persona = Persona::FindOrFail($id);
        Persona::where('id', $id)->update(['estado'=>!$persona->estado]);
        // $rol = Rol::FindOrFail($id);
        // $rol->delete();
        return response()->json(['exito'=>'Persona eliminado con id: '.$id], 200);
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

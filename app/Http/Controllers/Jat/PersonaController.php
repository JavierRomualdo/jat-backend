<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Ubigeo;
use App\Dto\PersonaDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

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
        'correo','direccion', 'ubigeo.ubigeo', 'persona.estado', 'rol')
        ->join('rol','rol.id','=','persona.rol_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')->get();
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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'correo' => $request->correo,
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
            'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where([['nombres','like','%'.($request->nombres).'%'],
                    ['dni','like','%'.($request->dni).'%'],
                    ['rol.id','=',$request->input('rol_id.id')]])->get();
        } else if (($request->nombres != null && $request->nombres != '') && 
        ($request->dni != null && $request->dni != '')) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
            ->join('rol','rol.id','=','persona.rol_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
            ->where([['nombres','like','%'.($request->nombres).'%'],
            ['dni','like','%'.($request->dni).'%']])->get();
        } else if (($request->nombres != null && $request->nombres != '') && 
        ($request->input('rol_id.id')!= null)) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where([['nombres','like','%'.($request->nombres).'%'],
                    ['rol.id','=',$request->input('rol_id.id')]])->get();
        } else if (($request->dni != null && $request->dni != '') && 
        ($request->input('rol_id.id')!= null)) {
            $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where([['dni','like','%'.($request->dni).'%'],
                    ['rol.id','=',$request->input('rol_id.id')]])->get();
        } else {
            if ($request->nombres != null && $request->nombres != '') {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where('nombres','like','%'.($request->nombres).'%')->get();
            } else if($request->dni != null && $request->dni != '')  {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where('dni','like','%'.($request->dni).'%')->get();
            } else {
                $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
                'correo','direccion','ubigeo.ubigeo', 'persona.estado', 'rol')
                ->join('rol','rol.id','=','persona.rol_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
                ->where('rol.id','=',$request->input('rol_id.id'))->get();
            }
        }
        return response()->json($persona);
    }

    public function show($id)
    {
        //
        $personadto = new PersonaDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $persona = Persona::select('persona.id', 'dni', 'nombres', 'telefono','correo','direccion',
            'ubigeo.ubigeo', 'persona.estado','rol.id as idrol', 'rol', 'persona.ubigeo_id as idubigeo')
            ->join('rol','rol.id','=','persona.rol_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
            ->where('persona.id','=',$id)->first();
        $personadto->setPersona($persona);
        $rol = Rol::FindOrFail($persona->idrol);
        $personadto->setRol($rol);

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($persona->idubigeo); // siempre es el ubigeo distrito
        $ubigeodto->setUbigeo($ubigeo);
        $codigo = $ubigeo->codigo;
        $subsdepartamento = substr($codigo, 0, 2)."00000000";
        $subsprovincia = substr($codigo, 0, 4)."000000";

        $ubigeos = Ubigeo::whereIn('codigo', [$subsdepartamento, $subsprovincia])->get();

        $departamento = $ubigeos[0];
        $provincia = $ubigeos[1];
        $ubigeodetalledto->setDepartamento($departamento);
        $ubigeodetalledto->setProvincia($provincia);
        $ubigeodetalledto->setUbigeo($ubigeodto);
        $personadto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo
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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'correo' => $request->correo,
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

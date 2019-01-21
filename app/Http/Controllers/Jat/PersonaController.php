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
use App\EntityWeb\Utils\RespuestaWebTO;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //$personas = Persona::get();

        $personas = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
        'correo','direccion', 'ubigeo.ubigeo', 'persona.estado', 'rol')
        ->join('rol','rol.id','=','persona.rol_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')->get();
        return response()->json($personas, 200);
    }

    public function listarPersonas(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            } // PersonaTO
            $personas = Persona::select('persona.id', 'dni', 'nombres', 'telefono',
            'correo','direccion', 'ubigeo.ubigeo as ubicacion', 'persona.estado', 'rol')
            ->join('rol','rol.id','=','persona.rol_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'persona.ubigeo_id')
            ->whereIn('persona.estado', $estados)->get();
            if ($personas!==null && !$personas->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($personas);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron personas');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function cambiarEstadoPersona(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado la persona en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $persona = Persona::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($persona!==null && $persona!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('La persona: '.$request->nombres.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($persona);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al modificar estado');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
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
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La persona: '.$request->nombres.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($persona);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200); // 201
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
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
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($personadto);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
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
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La persona: '.$request->nombres.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($persona);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $persona = Persona::FindOrFail($id);
            $persona->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La persona: '.$persona->nombres.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($persona);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
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

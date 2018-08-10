<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\HabitacionFoto;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Servicios;
use App\Models\HabitacionServicio;
use App\Dto\HabitacionDto;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
        'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
        'habitacion.foto', 'habitacion.estado')
        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')->get();
        
        return response()->json($habitaciones);
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
        $habitacion = Habitacion::create([
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'ncamas' => $request->ncamas,
            'tbanio' => $request->tbanio,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ]);

        // aqui obtengo un arreglo de id proveniente de la lista de servicios
        $servicios = $request->input('serviciosList.*.id');
        for ($i = 0; $i < count($servicios); $i++) {
            $habitacionservicio = HabitacionServicio::create([
                'habitacion_id' => $habitacion->id,
                'servicio_id' => $servicios[$i],
                'estado' => true
            ]);
        }

        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $habitacionfoto = HabitacionFoto::create([
                'habitacion_id' => $habitacion->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        return response()->json($habitacion, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busqueda(Request $request)
    {
        # code...
        if (($request->direccion != null && $request->direccion != '') && 
            ($request->ubicacion != null && $request->ubicacion != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'habitacion.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->ubicacion != null && $request->ubicacion != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'habitacion.direccion','like','%'.($request->direccion).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->ubicacion != null && $request->ubicacion != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->ubicacion != null && $request->ubicacion != '')) {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.ubicacion','like','%'.($request->ubicacion).'%')->get();
            } else {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($habitaciones);
    }

    public function show($id)
    {
        //
        $habitaciondto = new HabitacionDto();
        $habitacion = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
        'habitacion.ubicacion', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
        'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->where('habitacion.id','=',$id)->first();
        $habitaciondto->setHabitacion($habitacion);
        $persona = Persona::FindOrFail($habitacion->idpersona);
        $habitaciondto->setPersona($persona);
        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('habitacionfoto', 'habitacionfoto.foto_id', '=', 'foto.id')
                ->where('habitacionfoto.habitacion_id', $id)->get();
        $habitaciondto->setFotos($fotos);
        $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
            ->join('habitacionservicio', 'habitacionservicio.servicio_id', '=', 'servicios.id')
            ->where('habitacionservicio.habitacion_id', $id)->get();
        $habitaciondto->setServicios($servicios);
        $habitacionservicios = HabitacionServicio::select('habitacionservicio.id','habitacionservicio.habitacion_id',
                        'habitacionservicio.servicio_id','habitacionservicio.estado')
                        ->where('habitacionservicio.habitacion_id',$id)->get();
        $habitaciondto->setHabitacionServicio($habitacionservicios);
        //$persona = Persona::FindOrFail($id);
        return response()->json($habitaciondto, 200);
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
        $habitacion = Habitacion::FindOrFail($id);
        $input = [
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'ncamas' => $request->ncamas,
            'tbanio' => $request->tbanio,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ];
        $habitacion->fill($input)->save();

        $serviciosList = $request->input('serviciosList.*');
        $serviciosLista = [];
        /*Todos los serviciosList no muestran sus atributos asi que le convertimos
        mediante su respectivo modelo "Servicios*/
        foreach($serviciosList as $servicio) {
            $serviciomodel = new Servicios();
            $serviciomodel->fill($servicio);
            $serviciosLista[] = $serviciomodel;
        }
        // Eliminacion de habitacionservicio (6 8 16)
        $habitacionservicioList = $request->input('habitacionservicioList.*');// id = 6 y id = 16
        $habitacionserviciosLista = [];
        foreach($habitacionservicioList as $habitacionservicio) {
            $habitacionservicioModel = new HabitacionServicio();
            $habitacionservicioModel->fill($habitacionservicio);
            $habitacionserviciosLista[] = $habitacionservicioModel;
        }
        $habitacionservicios = HabitacionServicio::where('habitacion_id',$habitacion->id)->get();
        $esNuevo = false;
        $habitacionserviciosEliminados = [];
        foreach($habitacionservicios as $_habitacionservicio) {
            $esNuevo = false;
            foreach($habitacionserviciosLista as $habitacionservicioLista) {
                if($_habitacionservicio->id == $habitacionservicioLista->id) {
                    $esNuevo = true;
                }
            }
            if(!$esNuevo) {
                $habitacionserviciosEliminados[] = $_habitacionservicio;
                $_habitacionservicio->delete();
                //$habitacionservice = HabitacionServicio::FindOrFail($_habitacionservicio->id);
                //$habitacionservice->delete();
            }
        }
        // comparamos
        // $habitacionservicioeliminadosId = array_diff($habitacionserviciosId, $);
        // end eliminacion habitacionservicio

        // agregar nuevo habitacionservicio
        $esNuevo = true;
        $serviciosNuevo = [];
        foreach($serviciosLista as $servicioLista) {
            $esNuevo = true;
            foreach($habitacionservicios as $habitacionservicio) {
                if($servicioLista->id == $habitacionservicio->servicio_id) {
                    $esNuevo = false;
                }
            }
            if($esNuevo) {
                $serviciosNuevo[] = $servicioLista;
                $habitacionservicionuevo = HabitacionServicio::create([
                    'habitacion_id' => $habitacion->id,
                    'servicio_id'=> $servicioLista->id,
                    'estado' => true
                ]);
            }
        }
        // fotos
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $habitacionfoto = HabitacionFoto::create([
                'habitacion_id' => $habitacion->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        return response()->json($habitacion, 200);
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
        $habitacion = Habitacion::FindOrFail($id);
        Habitacion::where('id', $id)->update(['estado'=>!$habitacion->estado]);
        return response()->json(['exito'=>'Habitacion eliminado con id: '.$id], 200);
    }
}

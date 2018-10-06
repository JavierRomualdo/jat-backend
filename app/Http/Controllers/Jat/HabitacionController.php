<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\HabitacionFoto;
// use App\Models\HabitacionMensaje;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Servicios;
use App\Models\HabitacionServicio;
use App\Models\Ubigeo;
use App\Dto\HabitacionDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

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
        $habitaciones = Habitacion::select('habitacion.id', 'persona.nombres', 'precio', 'largo', 'ancho', 
        'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
        'habitacion.foto', 'habitacion.nmensajes', 'habitacion.nmensajes', 'habitacion.estado')
        // DB::raw('(CASE WHEN (habitacionmensaje.estado=1) then (count(*)) else 0 end) as nmensajes'),
        // DB::raw('count(*) as totalmensajes'))
        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')->get();
        
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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
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
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['habitacion.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['habitacion.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where([['habitacion.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && 
        $request->input('ubigeo_id.ubigeo') != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where('habitacion.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '')) {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                'habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($habitaciones);
    }

    public function mostrarhabitaciones(Request $request)
    {
        # code...
        $habitaciones = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                    if ($request->input('rangoprecio') != null) {
                        // habitaciones con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                        'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // habitaciones con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                        'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])->get();
                    }
                } else { // distrito = null
                    if ($request->input('rangoprecio') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                        'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                        'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                    }
                }
            } else { // != provincia
                if ($request->input('rangoprecio') != null) {
                    // habitaciones del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                    'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                } else {
                    // habitaciones del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $habitaciones = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 'path',
                    'habitacion.foto', 'habitacion.estado','ubigeo.ubigeo', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                }
            }
        }
        return response()->json($habitaciones);
    }

    public function show($id)
    {
        //
        $habitaciondto = new HabitacionDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $habitacion = Habitacion::select('habitacion.id', 'nombres', 'precio', 'largo', 'ancho', 
            'ubigeo.ubigeo', 'habitacion.direccion', 'ncamas', 'tbanio', 'descripcion', 
            'path','habitacion.foto', 'habitacion.estado', 'habitacion.persona_id as idpersona', 
            'habitacion.ubigeo_id as idubigeo')
            ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
            ->where('habitacion.id','=',$id)->first();
        $habitaciondto->setHabitacion($habitacion);
        $persona = Persona::FindOrFail($habitacion->idpersona);
        $habitaciondto->setPersona($persona);

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($habitacion->idubigeo); // siempre es el ubigeo distrito
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
        $habitaciondto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo

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

        /*$nmensajes = HabitacionMensaje::where([['habitacion_id','=',$habitacion->id],['estado','=',true]])->count();
        $habitaciondto->setnMensajes($nmensajes);*/

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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
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

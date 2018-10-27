<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Local;
use App\Models\LocalFoto;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Servicios;
use App\Models\LocalServicio;
use App\Models\LocalMensaje;
use App\Models\Ubigeo;
use App\Dto\LocalDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $locales = Local::select('local.id', 'persona.nombres', 'precio', 'largo', 'ancho', 
        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
        'local.foto', 'local.nmensajes', 'local.estado')
        // DB::raw('(CASE WHEN (localmensaje.estado=1) then (count(*)) else 0 end) as nmensajes')
        // DB::raw('count(*) as totalmensajes'))
        ->join('persona', 'persona.id', '=', 'local.persona_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')->get();
        
        return response()->json($locales);
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
        $local = Local::create([
            'persona_id' => $request->input('persona_id.id'),
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'tbanio' => $request->tbanio,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'tiposervicio' => $request->tiposervicio,
            'estado' => $request->estado
        ]);

        // aqui obtengo un arreglo de id proveniente de la lista de servicios
        $servicios = $request->input('serviciosList.*.id');
        for ($i = 0; $i < count($servicios); $i++) {
            $localservicio = LocalServicio::create([
                'local_id' => $local->id,
                'servicio_id' => $servicios[$i],
                'estado' => true
            ]);
        }

        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $localfoto = LocalFoto::create([
                'local_id' => $local->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        return response()->json($local, 200); // 201
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
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['local.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
                $request->input('ubigeo_id.ubigeo') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['local.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['local.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && 
        $request->input('ubigeo_id.ubigeo') != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('local.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->input('ubigeo_id.ubigeo') != null && 
                $request->input('ubigeo_id.ubigeo') != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($locales);
    }

    public function mostrarlocales(Request $request)
    {
        # code...
        $locales = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                    if ($request->input('rangoprecio') != null) {
                        // locales con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // locales con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])->get();
                    }
                } else { // distrito = null
                    if ($request->input('rangoprecio') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                        'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                        'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                        ->join('persona', 'persona.id', '=', 'local.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                    }
                }
            } else { // != provincia
                if ($request->input('rangoprecio') != null) {
                    // locales del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                    'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'local.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                    ['precio','>=',$request->input('rangoprecio.preciominimo')],
                    ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                } else {
                    // locales del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                    'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path',
                    'local.foto', 'local.estado', 'ubigeo.codigo','ubigeo.tipoubigeo_id')
                    ->join('persona', 'persona.id', '=', 'local.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                }
            }
        }
        return response()->json($locales);
    }

    public function show($id)
    {
        //
        $localdto = new LocalDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();
        
        $local = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 'tiposervicio',
            'ubigeo.ubigeo', 'local.direccion', 'tbanio', 'descripcion', 'path','local.foto', 
            'local.estado', 'local.persona_id as idpersona', 'local.ubigeo_id as idubigeo')
            ->join('persona', 'persona.id', '=', 'local.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
            ->where('local.id','=',$id)->first();
        $localdto->setLocal($local);
        $persona = Persona::FindOrFail($local->idpersona);
        $localdto->setPersona($persona);

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($local->idubigeo); // siempre es el ubigeo distrito
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
        $localdto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo

        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('localfoto', 'localfoto.foto_id', '=', 'foto.id')
                ->where('localfoto.local_id', $id)->get();
        $localdto->setFotos($fotos);
        $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
            ->join('localservicio', 'localservicio.servicio_id', '=', 'servicios.id')
            ->where('localservicio.local_id', $id)->get();
        $localdto->setServicios($servicios);
        $localservicios = LocalServicio::select('localservicio.id','localservicio.local_id',
                        'localservicio.servicio_id','localservicio.estado')
                        ->where('localservicio.local_id',$id)->get();
        $localdto->setLocalServicio($localservicios);

        $nmensajes = LocalMensaje::where([['local_id','=',$local->id],['estado','=',true]])->count();
        $localdto->setnMensajes($nmensajes);
        //$persona = Persona::FindOrFail($id);
        return response()->json($localdto, 200);
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
        $local = Local::FindOrFail($id);
        $input = [
            'persona_id' => $request->input('persona_id.id'),
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'tbanio' => $request->tbanio,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'tiposervicio' => $request->tiposervicio,
            'estado' => $request->estado
        ];
        $local->fill($input)->save();
        
        $serviciosList = $request->input('serviciosList.*');
        $serviciosLista = [];
        /*Todos los serviciosList no muestran sus atributos asi que le convertimos
        mediante su respectivo modelo "Servicios*/
        foreach($serviciosList as $servicio) {
            $serviciomodel = new Servicios();
            $serviciomodel->fill($servicio);
            $serviciosLista[] = $serviciomodel;
        }
        // Eliminacion de localservicio (6 8 16)
        $localservicioList = $request->input('localservicioList.*');// id = 6 y id = 16
        $localserviciosLista = [];
        foreach($localservicioList as $localservicio) {
            $localservicioModel = new LocalServicio();
            $localservicioModel->fill($localservicio);
            $localserviciosLista[] = $localservicioModel;
        }
        $localservicios = LocalServicio::where('local_id',$local->id)->get();
        $esNuevo = false;
        $localserviciosEliminados = [];
        foreach($localservicios as $_localservicio) {
            $esNuevo = false;
            foreach($localserviciosLista as $localservicioLista) {
                if($_localservicio->id == $localservicioLista->id) {
                    $esNuevo = true;
                }
            }
            if(!$esNuevo) {
                $localserviciosEliminados[] = $_localservicio;
                $_localservicio->delete();
                //$localservice = LocalServicio::FindOrFail($_localservicio->id);
                //$localservice->delete();
            }
        }
        // comparamos
        // $localservicioeliminadosId = array_diff($localserviciosId, $);
        // end eliminacion localservicio

        // agregar nuevo localservicio
        $esNuevo = true;
        $serviciosNuevo = [];
        foreach($serviciosLista as $servicioLista) {
            $esNuevo = true;
            foreach($localservicios as $localservicio) {
                if($servicioLista->id == $localservicio->servicio_id) {
                    $esNuevo = false;
                }
            }
            if($esNuevo) {
                $serviciosNuevo[] = $servicioLista;
                $localservicionuevo = LocalServicio::create([
                    'local_id' => $local->id,
                    'servicio_id'=> $servicioLista->id,
                    'estado' => true
                ]);
            }
        }
        
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $localfoto = LocalFoto::create([
                'local_id' => $local->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        return response()->json($local, 200);

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
        $local = Local::FindOrFail($id);
        Local::where('id', $id)->update(['estado'=>!$local->estado]);
        return response()->json(['exito'=>'Local eliminado con id: '.$id], 200);
    }
}

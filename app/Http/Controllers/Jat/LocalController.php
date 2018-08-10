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
use App\Dto\LocalDto;

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
        $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
        'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
        'local.foto', 'local.estado')
        ->join('persona', 'persona.id', '=', 'local.persona_id')->get();
        
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
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'tbanio' => $request->tbanio,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
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
            ($request->ubicacion != null && $request->ubicacion != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'local.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->ubicacion != null && $request->ubicacion != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'local.direccion','like','%'.($request->direccion).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('locl.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->ubicacion != null && $request->ubicacion != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->ubicacion != null && $request->ubicacion != '')) {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.ubicacion','like','%'.($request->ubicacion).'%')->get();
            } else {
                $locales = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
                'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
                'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($locales);
    }

    public function show($id)
    {
        //
        $localdto = new LocalDto();
        $local = Local::select('local.id', 'nombres', 'precio', 'largo', 'ancho', 
        'local.ubicacion', 'local.direccion', 'tbanio', 'descripcion', 'path',
        'local.foto', 'local.estado', 'local.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->where('local.id','=',$id)->first();
        $localdto->setLocal($local);
        $persona = Persona::FindOrFail($local->idpersona);
        $localdto->setPersona($persona);
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
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'tbanio' => $request->tbanio,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
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

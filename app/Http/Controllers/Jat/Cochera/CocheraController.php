<?php

namespace App\Http\Controllers\Jat\Cochera;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cochera;
use App\Models\CocheraFoto;
use App\Models\CocheraServicio;
use App\Models\Persona;
use App\Models\Foto;
//
use App\Models\Servicios;
use App\Models\Ubigeo;
use App\Dto\CocheraDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

class CocheraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
        'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
        'cochera.foto', 'cochera.persona_id', 'cochera.nmensajes', 'cochera.estado')
        ->join('persona', 'persona.id', '=', 'cochera.persona_id')
        ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
        ->get();

        return response()->json($cocheras, 200);
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
        $cochera = Cochera::create([
            'persona_id' => $request->input('persona_id.id'),
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'tiposervicio' => $request->tiposervicio,
            'estado' => $request->estado
        ]);

        // aqui obtengo un arreglo de id proveniente de la lista de servicios
        $servicios = $request->input('serviciosList.*.id');
        for ($i = 0; $i < count($servicios); $i++) {
            $cocheraservicio = CocheraServicio::create([
                'cochera_id' => $cochera->id,
                'servicio_id' => $servicios[$i],
                'estado' => true
            ]);
        }

        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $cocherafoto = CocheraFoto::create([
                'cochera_id' => $cochera->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }

        // $cochera = Cochera::create($request->all());
        return response()->json($cochera, 200); // 201
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
        if (($request->input('direccion') != null && $request->input('direccion') != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['cochera.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '')) {
            $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['cochera.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where([['cochera.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
                $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                    'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                    'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                    ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                    ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                    ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if ($request->direccion != null && $request->direccion != '') { 
                $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where('cochera.direccion','like','%'.($request->direccion).'%')
                ->get();
               
            } else if (($request->input('ubigeo_id.ubigeo') != null && $request->input('ubigeo_id.ubigeo') != '')) {
                $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $cocheras = Cochera::select('cochera.id','persona.nombres','precio',
                'largo','ancho','cochera.direccion','ubigeo.ubigeo', 'cochera.descripcion', 'path',
                'cochera.foto', 'cochera.persona_id as idpersona', 'cochera.estado')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($cocheras);
    }

    public function mostrarcocheras(Request $request)
    {
        //
    }
    public function show($id)
    {
        //
        $cocheradto = new CocheraDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $cochera = Cochera::select('cochera.id','precio', 'largo','ancho','cochera.direccion', 
            'descripcion', 'path', 'cochera.foto','persona.nombres', 'ubigeo.ubigeo', 'cochera.nmensajes', 'tiposervicio',
            'cochera.ubigeo_id as idubigeo', 'cochera.persona_id as idpersona', 'cochera.estado')
            ->join('persona', 'persona.id', '=', 'cochera.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
            ->where('cochera.id','=',$id)->first();
        $cocheradto->setCochera($cochera); // ingreso de la cochera
        $persona = Persona::FindOrFail($cochera->idpersona);
        $cocheradto->setPersona($persona); // ingreso del dueño del la cochera

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($cochera->idubigeo); // siempre es el ubigeo distrito
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
        $cocheradto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo

        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('cocherafoto', 'cocherafoto.foto_id', '=', 'foto.id')
                ->where('cocherafoto.cochera_id', $id)->get();
        $cocheradto->setFotos($fotos); // ingreso de las fotos de la cochera
        $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
            ->join('cocheraservicio', 'cocheraservicio.servicio_id', '=', 'servicios.id')
            ->where('cocheraservicio.cochera_id', $id)->get();
        $cocheradto->setServicios($servicios);
        $cocheraservicios = CocheraServicio::select('cocheraservicio.id','cocheraservicio.cochera_id',
                        'cocheraservicio.servicio_id','cocheraservicio.estado')
                        ->where('cocheraservicio.cochera_id',$id)->get();
        $cocheradto->setCocheraServicio($cocheraservicios);
        
        /*$nmensajes = CocheraMensaje::where([['cochera_id','=',$cochera->id],['estado','=',true]])->count();
        $cocheradto->setnMensajes($nmensajes);*/

        //$persona = Persona::FindOrFail($id);
        return response()->json($cocheradto, 200);
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
        $cochera = Cochera::FindOrFail($id);
        $input = [
            'persona_id' => $request->input('persona_id.id'),
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'tiposervicio' => $request->tiposervicio,
            'estado' => $request->estado
        ];
        $cochera->fill($input)->save();
        $serviciosList = $request->input('serviciosList.*');
        $serviciosLista = [];
        /*Todos los serviciosList no muestran sus atributos asi que le convertimos
        mediante su respectivo modelo "Servicios*/
        foreach($serviciosList as $servicio) {
            $serviciomodel = new Servicios();
            $serviciomodel->fill($servicio);
            $serviciosLista[] = $serviciomodel;
        }
        // Eliminacion de cocheraservicio (6 8 16)
        $cocheraservicioList = $request->input('cocheraservicioList.*');// id = 6 y id = 16
        $ocheraserviciosLista = [];
        foreach($cocheraservicioList as $cocheraservicio) {
            $cocheraservicioModel = new CocheraServicio();
            $cocheraservicioModel->fill($cocheraservicio);
            $cocheraserviciosLista[] = $cocheraservicioModel;
        }
        $cocheraservicios = CocheraServicio::where('cochera_id',$cochera->id)->get();
        $esNuevo = false;
        $cocheraserviciosEliminados = [];
        foreach($cocheraservicios as $_cocheraservicio) {
            $esNuevo = false;
            foreach($cocheraserviciosLista as $cocheraservicioLista) {
                if($_cocheraservicio->id == $cocheraservicioLista->id) {
                    $esNuevo = true;
                }
            }
            if(!$esNuevo) {
                $cocheraserviciosEliminados[] = $_cocheraservicio;
                $_cocheraservicio->delete();
                //$cocheraservice = CocheraServicio::FindOrFail($_cocheraservicio->id);
                //$cocheraservice->delete();
            }
        }
        // comparamos
        // $cocheraservicioeliminadosId = array_diff($cocheraserviciosId, $);
        // end eliminacion cocheraservicio

        // agregar nuevo cocheraservicio
        $esNuevo = true;
        $serviciosNuevo = [];
        foreach($serviciosLista as $servicioLista) {
            $esNuevo = true;
            foreach($cocheraservicios as $cocheraservicio) {
                if($servicioLista->id == $cocheraservicio->servicio_id) {
                    $esNuevo = false;
                }
            }
            if($esNuevo) {
                $serviciosNuevo[] = $servicioLista;
                $cocheraservicionuevo = CocheraServicio::create([
                    'cochera_id' => $cochera->id,
                    'servicio_id'=> $servicioLista->id,
                    'estado' => true
                ]);
            }
        }
        
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $cocherafoto = CocheraFoto::create([
                'cochera_id' => $cochera->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        // $cochera = Cochera::FindOrFail($id);
        // $input = $request->all();
        // $cochera->fill($input)->save();
        return response()->json($cochera, 200);
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
        $cochera = Cochera::FindOrFail($id);
        Cochera::where('id', $id)->update(['estado'=>!$cochera->estado]);
        return response()->json(['exito'=>'Cochera eliminado con id: '.$id], 200);
    }
}

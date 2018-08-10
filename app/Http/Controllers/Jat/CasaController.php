<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Casa;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\CasaFoto;
use App\Models\Servicios;
use App\Models\CasaServicio;
use App\Dto\CasaDto;

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
        $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
        'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
        'casa.foto','persona.nombres', 'casa.persona_id', 'casa.estado')
        ->join('persona', 'persona.id', '=', 'casa.persona_id')->get();

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
        $casa = Casa::create([
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'npisos' => $request->npisos,
            'ncuartos' => $request->ncuartos,
            'nbanios' => $request->nbanios,
            'tjardin' => $request->tjardin,
            'tcochera' => $request->tcochera,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ]);

        // aqui obtengo un arreglo de id proveniente de la lista de servicios
        $servicios = $request->input('serviciosList.*.id');
        for ($i = 0; $i < count($servicios); $i++) {
            $casaservicio = CasaServicio::create([
                'casa_id' => $casa->id,
                'servicio_id' => $servicios[$i],
                'estado' => true
            ]);
        }

        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $casafoto = CasaFoto::create([
                'casa_id' => $casa->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }

        // $casa = Casa::create($request->all());
        return response()->json($casa, 200); // 201
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
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'casa.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->ubicacion != null && $request->ubicacion != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'casa.direccion','like','%'.($request->direccion).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->ubicacion != null && $request->ubicacion != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->ubicacion != null && $request->ubicacion != '')) {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('casa.ubicacion','like','%'.($request->ubicacion).'%')->get();
            } else {
                $casas = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
                'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
                'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($casas);
    }
    public function show($id)
    {
        //
        $casadto = new CasaDto();
        $casa = Casa::select('casa.id','nombres','precio','npisos','ncuartos', 'nbanios','tjardin',
            'tcochera','largo','ancho','casa.direccion','casa.ubicacion', 'descripcion', 'path',
            'casa.foto','persona.nombres', 'casa.persona_id as idpersona', 'casa.estado')
            ->join('persona', 'persona.id', '=', 'casa.persona_id')
            ->where('casa.id','=',$id)->first();
        $casadto->setCasa($casa); // ingreso de la casa
        $persona = Persona::FindOrFail($casa->idpersona);
        $casadto->setPersona($persona); // ingreso del dueño del la casa
        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('casafoto', 'casafoto.foto_id', '=', 'foto.id')
                ->where('casafoto.casa_id', $id)->get();
        $casadto->setFotos($fotos); // ingreso de las fotos de la casa
        $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
            ->join('casaservicio', 'casaservicio.servicio_id', '=', 'servicios.id')
            ->where('casaservicio.casa_id', $id)->get();
        $casadto->setServicios($servicios);
        $casaservicios = CasaServicio::select('casaservicio.id','casaservicio.casa_id',
                        'casaservicio.servicio_id','casaservicio.estado')
                        ->where('casaservicio.casa_id',$id)->get();
        $casadto->setCasaServicio($casaservicios);
        //$persona = Persona::FindOrFail($id);
        return response()->json($casadto, 200);
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
        $input = [
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'npisos' => $request->npisos,
            'ncuartos' => $request->ncuartos,
            'nbanios' => $request->nbanios,
            'tjardin' => $request->tjardin,
            'tcochera' => $request->tcochera,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ];
        $casa->fill($input)->save();
        $serviciosList = $request->input('serviciosList.*');
        $serviciosLista = [];
        /*Todos los serviciosList no muestran sus atributos asi que le convertimos
        mediante su respectivo modelo "Servicios*/
        foreach($serviciosList as $servicio) {
            $serviciomodel = new Servicios();
            $serviciomodel->fill($servicio);
            $serviciosLista[] = $serviciomodel;
        }
        // Eliminacion de casaservicio (6 8 16)
        $casaservicioList = $request->input('casaservicioList.*');// id = 6 y id = 16
        $casaserviciosLista = [];
        foreach($casaservicioList as $casaservicio) {
            $casaservicioModel = new CasaServicio();
            $casaservicioModel->fill($casaservicio);
            $casaserviciosLista[] = $casaservicioModel;
        }
        $casaservicios = CasaServicio::where('casa_id',$casa->id)->get();
        $esNuevo = false;
        $casaserviciosEliminados = [];
        foreach($casaservicios as $_casaservicio) {
            $esNuevo = false;
            foreach($casaserviciosLista as $casaservicioLista) {
                if($_casaservicio->id == $casaservicioLista->id) {
                    $esNuevo = true;
                }
            }
            if(!$esNuevo) {
                $casaserviciosEliminados[] = $_casaservicio;
                $_casaservicio->delete();
                //$casaservice = CasaServicio::FindOrFail($_casaservicio->id);
                //$casaservice->delete();
            }
        }
        // comparamos
        // $casaservicioeliminadosId = array_diff($casaserviciosId, $);
        // end eliminacion casaservicio

        // agregar nuevo casaservicio
        $esNuevo = true;
        $serviciosNuevo = [];
        foreach($serviciosLista as $servicioLista) {
            $esNuevo = true;
            foreach($casaservicios as $casaservicio) {
                if($servicioLista->id == $casaservicio->servicio_id) {
                    $esNuevo = false;
                }
            }
            if($esNuevo) {
                $serviciosNuevo[] = $servicioLista;
                $casaservicionuevo = CasaServicio::create([
                    'casa_id' => $casa->id,
                    'servicio_id'=> $servicioLista->id,
                    'estado' => true
                ]);
            }
        }
        /*
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $casafoto = CasaFoto::create([
                'casa_id' => $casa->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }*/
        // $casa = Casa::FindOrFail($id);
        // $input = $request->all();
        // $casa->fill($input)->save();
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
        $casa = Casa::FindOrFail($id);
        Casa::where('id', $id)->update(['estado'=>!$casa->estado]);
        return response()->json(['exito'=>'Casa eliminado con id: '.$id], 200);
        // $casa = Rol::FindOrFail($id);
        // $casa->delete();
    }
}

<?php

namespace App\Http\Controllers\Jat\Apartamento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\ApartamentoFoto;
use App\Models\ApartamentoServicio;
use App\Models\Foto;
use App\Models\Ubigeo;
use App\Models\Servicios;
//
use App\Dto\ApartamentoDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

class ApartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera',
        'largo','ancho','apartamento.direccion','ubigeo.ubigeo', 'apartamento.descripcion', 'path',
        'apartamento.foto', 'apartamento.nmensajes', 'apartamento.estado')
        ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')->get();

        //$apartamentos = Apartamento::get();
        return response()->json($apartamentos, 200);
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
        $apartamento = Apartamento::create([
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'npisos' => $request->npisos,
            'direccion' => $request->direccion,
            'tcochera' => $request->tcochera,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'estado' => $request->estado
        ]);

        // aqui obtengo un arreglo de id proveniente de la lista de servicios
        $servicios = $request->input('serviciosList.*.id');
        for ($i = 0; $i < count($servicios); $i++) {
            $apartamentoservicio = ApartamentoServicio::create([
                'apartamento_id' => $apartamento->id,
                'servicio_id' => $servicios[$i],
                'estado' => true
            ]);
        }

        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $apartamentofoto = ApartamentoFoto::create([
                'apartamento_id' => $apartamento->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }

        // $apartamento = Apartamento::create($request->all());
        return response()->json($apartamento, 200); // 201
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
        $apartamentodto = new ApartamentoDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $apartamento = Apartamento::select('apartamento.id','npisos','tcochera','largo','ancho',
            'apartamento.direccion', 'descripcion', 'path', 'apartamento.foto', 'apartamento.nmensajes',
            'ubigeo.ubigeo', 'apartamento.ubigeo_id as idubigeo', 'apartamento.estado')
            ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
            ->where('apartamento.id','=',$id)->first();
        $apartamentodto->setDepartamento($apartamento); // ingreso de la apartamento

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($apartamento->idubigeo); // siempre es el ubigeo distrito
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
        $apartamentodto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo

        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('apartamentofoto', 'apartamentofoto.foto_id', '=', 'foto.id')
                ->where('apartamentofoto.apartamento_id', $id)->get();
        $apartamentodto->setFotos($fotos); // ingreso de las fotos de la apartamento
        $servicios = Servicios::select('servicios.id','servicios.servicio', 'servicios.detalle', 'servicios.estado')
            ->join('apartamentoservicio', 'apartamentoservicio.servicio_id', '=', 'servicios.id')
            ->where('apartamentoservicio.apartamento_id', $id)->get();
        $apartamentodto->setServicios($servicios);
        $apartamentoservicios = ApartamentoServicio::select('apartamentoservicio.id','apartamentoservicio.apartamento_id',
                        'apartamentoservicio.servicio_id','apartamentoservicio.estado')
                        ->where('apartamentoservicio.apartamento_id',$id)->get();
        $apartamentodto->setApartamentoServicio($apartamentoservicios);
        
        /*$nmensajes = ApartamentoMensaje::where([['apartamento_id','=',$apartamento->id],['estado','=',true]])->count();
        $apartamentodto->setnMensajes($nmensajes);*/

        return response()->json($apartamentodto, 200);
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
        $apartamento = Apartamento::FindOrFail($id);
        $input = [
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'npisos' => $request->npisos,
            'direccion' => $request->direccion,
            'tcochera' => $request->tcochera,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'estado' => $request->estado
        ];
        $apartamento->fill($input)->save();
        $serviciosList = $request->input('serviciosList.*');
        $serviciosLista = [];
        /*Todos los serviciosList no muestran sus atributos asi que le convertimos
        mediante su respectivo modelo "Servicios*/
        foreach($serviciosList as $servicio) {
            $serviciomodel = new Servicios();
            $serviciomodel->fill($servicio);
            $serviciosLista[] = $serviciomodel;
        }
        // Eliminacion de apartamentoservicio (6 8 16)
        $apartamentoservicioList = $request->input('apartamentoservicioList.*');// id = 6 y id = 16
        $apartamentoserviciosLista = [];
        foreach($apartamentoservicioList as $apartamentoservicio) {
            $apartamentoservicioModel = new ApartamentoServicio();
            $apartamentoservicioModel->fill($apartamentoservicio);
            $apartamentoserviciosLista[] = $apartamentoservicioModel;
        }
        $apartamentoservicios = ApartamentoServicio::where('apartamento_id',$apartamento->id)->get();
        $esNuevo = false;
        $apartamentoserviciosEliminados = [];
        foreach($apartamentoservicios as $_apartamentoservicio) {
            $esNuevo = false;
            foreach($apartamentoserviciosLista as $apartamentoservicioLista) {
                if($_apartamentoservicio->id == $apartamentoservicioLista->id) {
                    $esNuevo = true;
                }
            }
            if(!$esNuevo) {
                $apartamentoserviciosEliminados[] = $_apartamentoservicio;
                $_apartamentoservicio->delete();
                //$apartamentoservice = ApartamentoServicio::FindOrFail($_apartamentoservicio->id);
                //$apartamentoservice->delete();
            }
        }
        // comparamos
        // $apartamentoservicioeliminadosId = array_diff($apartamentoserviciosId, $);
        // end eliminacion apartamentoservicio

        // agregar nuevo apartamentoservicio
        $esNuevo = true;
        $serviciosNuevo = [];
        foreach($serviciosLista as $servicioLista) {
            $esNuevo = true;
            foreach($apartamentoservicios as $apartamentoservicio) {
                if($servicioLista->id == $apartamentoservicio->servicio_id) {
                    $esNuevo = false;
                }
            }
            if($esNuevo) {
                $serviciosNuevo[] = $servicioLista;
                $apartamentoservicionuevo = ApartamentoServicio::create([
                    'apartamento_id' => $apartamento->id,
                    'servicio_id'=> $servicioLista->id,
                    'estado' => true
                ]);
            }
        }
        
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $apartamentofoto = ApartamentoFoto::create([
                'apartamento_id' => $apartamento->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        // $apartamento = Apartamento::FindOrFail($id);
        // $input = $request->all();
        // $apartamento->fill($input)->save();
        return response()->json($apartamento, 200);
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
        $apartamento = Apartamento::FindOrFail($id);
        Apartamento::where('id', $id)->update(['estado'=>!$apartamento->estado]);
        return response()->json(['exito'=>'Aoartamento eliminado con id: '.$id], 200);
    }
}

<?php

namespace App\Http\Controllers\Jat\Apartamento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\ApartamentoFoto;
use App\Models\ApartamentoServicio;
use App\Models\ApartamentoMensaje;
use App\Models\Foto;
use App\Models\Ubigeo;
use App\Models\Servicios;
//
use App\Dto\ApartamentoDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

use App\EntityWeb\Utils\RespuestaWebTO;

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
    public function mostrarCondicionUbigeo($tipoubigeo, $codigo)
    {
        # code...
        if ($tipoubigeo==1) {
            // ubigeos con departamentos
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $condicion = ['codigo','like',$subs.'%'];
        }  elseif ($tipoubigeo==2) {
            $subs = substr($codigo, 0, 4); // ejmp: 01
            $condicion = ['codigo','like',$subs.'%'];
        } else if ($tipoubigeo==3) {
            // ubigeos con provincias
            $subs = substr($codigo, 0, 4); // ejmp: 01
            $condicion = ['codigo','=',$codigo];
        } else {
            $condicion = 'error';
        }
        // return response()->json($condicion, 200);
        return $condicion;
    }

    public function listarApartamentosParaTipoContrato(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $tipoubigeo = $request->input('ubigeo') ? $request->input('ubigeo.tipoubigeo_id') : null;
            $codigo = $request->input('ubigeo') ? $request->input('ubigeo.codigo'): null;
            $condicion = $request->input('ubigeo') ? $this->mostrarCondicionUbigeo($tipoubigeo,$codigo) : null;
            if ($condicion!== 'error') { // ApartamentoTO
                $apartamentos = Apartamento::select('apartamento.id', 'apartamento.foto', 'ubigeo.ubigeo as ubicacion', 
                    'apartamento.direccion', 'largo','ancho', 'apartamento.codigo', 'precioadquisicion', 
                    'preciocontrato', 'ganancia', 'npisos', 'tcochera', 'apartamento.contrato', 
                    'apartamento.estadocontrato', 'apartamento.estado', 'apartamento.nmensajes')
                    ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id') 
                    ->where([['apartamento.estado','=',true], ['apartamento.estadocontrato','=','L'],
                        ['apartamento.codigo','like','%'.($request->codigo).'%'], ['apartamento.contrato','=',$request->contrato], 
                        ['ubigeo.codigo', $condicion[1], $condicion[2]]])->get(); // con ubigeo
                if ($apartamentos!==null && !$apartamentos->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($apartamentos);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron apartamentos');
                }
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error con ubigeos');
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

    public function listarApartamentos(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            } // ApartamentoTO
            $apartamentos = Apartamento::select('apartamento.id', 'apartamento.foto', 'ubigeo.ubigeo as ubicacion', 
                'apartamento.direccion', 'largo','ancho', 'apartamento.codigo', 'precioadquisicion', 
                'preciocontrato', 'ganancia', 'npisos', 'tcochera', 'apartamento.contrato', 
                'apartamento.estadocontrato', 'apartamento.estado', 'apartamento.nmensajes')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id') 
                ->whereIn('apartamento.estado', $estados)->get();

            if ($apartamentos!==null && !$apartamentos->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($apartamentos);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron apartamentos');
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

    public function listarApartamentosPorEstadoContrato(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO(); // ApartamentoTO
            $apartamentos = Apartamento::select('apartamento.id', 'apartamento.foto', 'ubigeo.ubigeo as ubicacion', 
                'apartamento.direccion', 'largo','ancho', 'apartamento.codigo', 'precioadquisicion', 
                'preciocontrato', 'ganancia', 'npisos', 'tcochera', 'apartamento.contrato', 
                'apartamento.estadocontrato', 'apartamento.estado', 'apartamento.nmensajes')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id') 
                ->where('apartamento.estadocontrato', $request->input('estadoContrato'))->get();

            if ($apartamentos!==null && !$apartamentos->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($apartamentos);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron apartamentos');
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

    public function cambiarEstadoApartamento(Request $request)
    {
        # code...
        try {
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si ha usado el apartamento en venta o alquiler o reserva
            // eso hay que verlo porque falta
            $apartamento = Apartamento::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);

            if ($apartamento!==null && $apartamento!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El apartamento: cod '.$request->codigo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($apartamento);
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

    public function generarCodigoApartamento() {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $codigo = $this->nuevoCodigoApartamento();
            if ($codigo !== null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($codigo);
            } else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setOperacionMensaje('Se ha excedido el codigo del apartamento');
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

    public function nuevoCodigoApartamento()
    {
        # code...
        $codigo = 'AP';
        $n = Apartamento::count();
        $n++;
        if ($n < 10) {
            $codigo = $codigo.'0000'.$n;
        } else if ($n < 100) {
            $codigo = $codigo.'000'.$n;
        } else if ($n < 1000) {
            $codigo = $codigo.'00'.$n;
        } else if ($n < 10000) {
            $codigo = $codigo.'0'.$n;
        } else if ($n < 100000) {
            $codigo = $codigo.$n;
        } else {
            $codigo = null;
        }
        
        return $codigo;
    }

    public function store(Request $request)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $apartamento = Apartamento::create([
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'precioadquisicion' => $request->precioadquisicion,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'npisos' => $request->npisos,
                'direccion' => $request->direccion,
                'tcochera' => $request->tcochera,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'foto' => $request->foto,
                'nmensajes' => $request->nmensajes,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
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
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El apartamento: código: '.$request->codigo.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($apartamento);
            // $apartamento = Apartamento::create($request->all());
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
    public function busqueda(Request $request)
    {
        # code...
        if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '')) {
            $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera', 
                'largo', 'ancho', 'apartamento.direccion','ubigeo.ubigeo', 
                'apartamento.descripcion', 'path', 'apartamento.foto', 'apartamento.estado')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['apartamento.direccion','like','%'.($request->direccion).'%']])->get();
        } else if ($request->direccion != null && $request->direccion != '') {
             $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera', 
                'largo', 'ancho', 'apartamento.direccion','ubigeo.ubigeo', 
                'apartamento.descripcion', 'path', 'apartamento.foto', 'apartamento.estado')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                ->where('apartamento.direccion','like','%'.($request->direccion).'%')->get();
        } else {
            $apartamentos = Apartamento::select('apartamento.id', 'npisos', 'tcochera', 
                'largo', 'ancho', 'apartamento.direccion','ubigeo.ubigeo', 
                'apartamento.descripcion', 'path', 'apartamento.foto', 'apartamento.estado')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
        }
        return response()->json($apartamentos, 200); // 201
    }

    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $apartamentodto = new ApartamentoDto();
            $ubigeodetalledto = new UbigeoDetalleDto();
            $ubigeodto = new UbigeoDto();

            $apartamento = Apartamento::select('apartamento.id', 'apartamento.codigo','precioadquisicion', 
                'preciocontrato', 'npisos','tcochera','largo','ancho', 'apartamento.direccion', 'descripcion', 
                'path', 'apartamento.foto', 'apartamento.nmensajes', 'ubigeo.ubigeo', 'apartamento.ubigeo_id as idubigeo', 
                'contrato', 'estadocontrato', 'apartamento.estado')
                ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
                ->where('apartamento.id','=',$id)->first();
            if ($apartamento !== null && $apartamento !== '') {
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
                
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($apartamentodto);
                /*$nmensajes = ApartamentoMensaje::where([['apartamento_id','=',$apartamento->id],['estado','=',true]])->count();
                $apartamentodto->setnMensajes($nmensajes);*/
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron apartamentos');
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
            $apartamento = Apartamento::FindOrFail($id);
            $input = [
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'codigo' => $request->codigo,
                'precioadquisicion' => $request->precioadquisicion,
                'preciocontrato' => $request->preciocontrato,
                'ganancia' => $request->ganancia,
                'largo' => $request->largo,
                'ancho' => $request->ancho,
                'npisos' => $request->npisos,
                'direccion' => $request->direccion,
                'tcochera' => $request->tcochera,
                'descripcion' => $request->descripcion,
                'path' => $request->path,
                'foto' => $request->foto,
                'nmensajes' => $request->nmensajes,
                'contrato' => $request->contrato,
                'estadocontrato' => $request->estadocontrato,
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
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El apartamento: código: '.$request->codigo.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($apartamento);
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
            // eliminar los mensajes
            $apartamentomensaje = ApartamentoMensaje::where('apartamento_id', $id)->delete();
            // luego eliminar los servicios
            $apartamentoservicio = ApartamentoServicio::where('apartamento_id', $id)->delete();
            // despues eliminar las fotos
            $apartamentofoto = ApartamentoFoto::where('apartamento_id', $id)->delete();
            $fotos = Foto::join('apartamentofoto', 'apartamentofoto.foto_id', '=', 'foto.id')
                    ->where('apartamentofoto.apartamento_id', $id)->delete();
            // finalmente la apartamento
            $apartamento = Apartamento::FindOrFail($id);
            $apartamento->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El apartamento: código: '.$apartamento->codigo.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($apartamento);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
        // $apartamento = Apartamento::FindOrFail($id);
        // Apartamento::where('id', $id)->update(['estado'=>!$apartamento->estado]);
    }
}

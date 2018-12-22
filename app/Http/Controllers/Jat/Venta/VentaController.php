<?php

namespace App\Http\Controllers\Jat\Venta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Casa;
use App\Models\Persona;
use Illuminate\Database\QueryException;
use App\Exceptions\Handler;
use App\EntityWeb\Utils\RespuestaWebTO;

use App\Http\Controllers\Jat\CasaController;
use App\Dto\VentaDto;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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

    public function listarVentas(Request $request) {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ventas = null;
            switch ($request->input('propiedad')) {
                case 'Apartamento':
                    break;
                case 'Casa':
                    $ventas = $this->listarVentasCasas($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    break;
                case 'Cochera':
                    break;
                case 'Local':
                    break;
                case 'Lote':
                    break;
            }
            if ($ventas !== 'error') {
                if ($ventas!==null && !$ventas->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($ventas);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron ventas casas');
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

    public function listarVentasCasas($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // VentaTO
            $ventas = Venta::select('venta.id', 'casa.estadocontrato', 'casa.foto', 'venta.casa_id as propiedad_id',
                'casa.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
                'ubigeo.ubigeo as ubicacion', 'casa.direccion', 'casa.preciocontrato', 'venta.fecha as fechaVenta')
                ->join('casa', 'casa.id', '=', 'venta.casa_id')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $ventas = 'error';
        }
        return $ventas;
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
            $venta = Venta::create([
                'apartamento_id' => $request->apartamento_id,
                'casa_id' => $request->casa_id,
                'cochera_id' => $request->cochera_id,
                'local_id' => $request->local_id,
                'lote_id' => $request->lote_id,
                'persona_id' => $request->persona_id,
                'fecha' => $request->fecha,
                'estado' => $request->estado
            ]);
            // luego cambio de estado a la propiedad
            if ($request->apartamento_id) {
                //
            } else if ($request->casa_id) {
                $casa = Casa::where('id', $request->casa_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
            } else if ($request->cochera_id) {
                //
            } else if ($request->local_id) {
                //
            } else if ($request->lote_id) {
                //
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La venta se ha guardado correctamente.');
            $respuesta->setExtraInfo($venta);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200); // 201
    }

    public function mostrarVenta(Request $request) // show en venta
    {
        # code...
        /**
         * $request = {$id (id de venta)}
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ventaDto = new VentaDto();
            $casaController = new CasaController();
            // adquiririmos los datos de la venta
            $venta = Venta::FindOrFail($request->id);
            $cliente = Persona::FindOrFail($venta->persona_id);
            $ventaDto->setVentaDto($venta->id, $venta->fecha, $venta->estado);
            $ventaDto->setCliente($cliente);
            // luego la propiedad respectiva
            if ($venta->apartamento_id) {
                //
            } else if ($venta->casa_id) {
                $respuestaPropiedad = $casaController->show($venta->casa_id);
                $casa = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setCasa($casa);
            } else if ($venta->cochera_id) {
                //
            } else if ($venta->local_id) {
                //
            } else if ($venta->lote_id) {
                //
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($ventaDto);
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
    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ventaDto = new VentaDto();
            $casaController = new CasaController();
            // adquiririmos los datos de la venta
            $venta = Venta::FindOrFail($id);
            $cliente = Persona::FindOrFail($venta->persona_id);
            $ventaDto->setVentaDto($venta->id, $venta->fecha, $venta->estado);
            $ventaDto->setCliente($cliente);
            // luego la propiedad respectiva
            if ($venta->apartamento_id) {
                //
            } else if ($venta->casa_id) {
                $respuestaPropiedad = $casaController->show($venta->casa_id);
                $casa = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setCasa($casa);
            } else if ($venta->cochera_id) {
                //
            } else if ($venta->local_id) {
                //
            } else if ($venta->lote_id) {
                //
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($ventaDto);
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
    }
}
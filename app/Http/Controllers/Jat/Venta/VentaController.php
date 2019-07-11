<?php

namespace App\Http\Controllers\Jat\Venta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Apartamento;
use App\Models\Casa;
use App\Models\Cochera;
use App\Models\Local;
use App\Models\Lote;
use App\Models\Persona;
use Illuminate\Database\QueryException;
use App\Exceptions\Handler;
use App\EntityWeb\Utils\RespuestaWebTO;

use App\Http\Controllers\Jat\Apartamento\ApartamentoController;
use App\Http\Controllers\Jat\CasaController;
use App\Http\Controllers\Jat\Cochera\CocheraController;
use App\Http\Controllers\Jat\LocalController;
use App\Http\Controllers\Jat\LoteController;
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
            $subs = substr($codigo, 0, 6); // ejmp: 010101
            $condicion = ['codigo','like',$subs.'%'];
        } else if ($tipoubigeo==4) { // ejmp: 01010101
            $condicion = ['codigo','=',$codigo];
        } else {
            $condicion = 'error';
        }
        // return response()->json($condicion, 200);
        return $condicion;
    }

    /**Nota: Solo estas propiedades (casas lotes habitaciones apartamentos) son para 
     * la venta
     */
    public function listarVentas(Request $request) {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ventas = null;
            $propiedad = "";
            switch ($request->input('propiedad')) {
                case 'Apartamento':
                    $ventas = $this->listarVentasApartamentos($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'apartamentos';
                    break;
                case 'Casa':
                    $ventas = $this->listarVentasCasas($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'casas';
                    break;
                /*case 'Cochera':
                    $ventas = $this->listarVentasCocheras($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'cocheras';
                    break;*/
                case 'Local':
                    $ventas = $this->listarVentasLocales($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'locales';
                    break;
                case 'Lote':
                    $ventas = $this->listarVentasLotes($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'lotes';
                    break;
            }
            if ($ventas !== 'error') {
                if ($ventas!==null && !$ventas->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($ventas);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron ventas '.$propiedad);
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

    public function listarVentasApartamentos($tipoubigeo,$codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion !== 'error') { // VentTO
            $ventas = Venta::select('venta.id', 'apartamento.estadocontrato', 'apartamento.foto', 'venta.apartamento_id as propiedad_id',
            'apartamento.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
            'ubigeo.ubigeo as ubicacion', 'apartamento.direccion', 'apartamento.preciocontrato', 'venta.fecha as fechaVenta')
            ->join('apartamento', 'apartamento.id', '=', 'venta.apartamento_id')
            ->join('persona', 'persona.id', '=', 'apartamento.persona_id')
            ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
            ->where([['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
            // ['ubigeo.tipoubigeo_id','=',3],
        } else {
            $ventas = 'error';
        }
        return $ventas;
    }

    public function listarVentasCasas($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // VentaTO
            $ventas = Venta::select('venta.id', 'casa.estadocontrato', 'casa.foto', 'venta.casa_id as propiedad_id',
                'casa.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
                'ubigeo.rutaubigeo as ubicacion', 'habilitacionurbana.siglas', 'casa.direccion', 'casa.preciocontrato',
                'venta.fecha as fechaVenta')
                ->join('casa', 'casa.id', '=', 'venta.casa_id')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
                // ['ubigeo.tipoubigeo_id','=',3],
        } else {
            $ventas = 'error';
        }
        return $ventas;
    }

    public function listarVentasCocheras($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // VentaTO
            $ventas = Venta::select('venta.id', 'cochera.estadocontrato', 'cochera.foto', 'venta.cochera_id as propiedad_id',
                'cochera.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
                'ubigeo.rutaubigeo as ubicacion', 'habilitacionurbana.siglas', 'cochera.direccion', 'cochera.preciocontrato',
                'venta.fecha as fechaVenta')
                ->join('cochera', 'cochera.id', '=', 'venta.cochera_id')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
                // ['ubigeo.tipoubigeo_id','=',3],
        } else {
            $ventas = 'error';
        }
        return $ventas;
    }

    public function listarVentasLocales($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // VentaTO
            $ventas = Venta::select('venta.id', 'local.estadocontrato', 'local.foto', 'venta.local_id as propiedad_id',
                'local.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
                'ubigeo.rutaubigeo as ubicacion', 'habilitacionurbana.siglas', 'local.direccion', 'local.preciocontrato',
                'venta.fecha as fechaVenta')
                ->join('local', 'local.id', '=', 'venta.local_id')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
                // ['ubigeo.tipoubigeo_id','=',3],
        } else {
            $ventas = 'error';
        }
        return $ventas;
    }

    public function listarVentasLotes($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // VentaTO
            $ventas = Venta::select('venta.id', 'lote.estadocontrato', 'lote.foto', 'venta.lote_id as propiedad_id',
                'lote.codigo as propiedad_codigo', 'personaventa.nombres as cliente', 'persona.nombres as propietario', 
                'ubigeo.rutaubigeo as ubicacion', 'habilitacionurbana.siglas', 'lote.direccion', 'lote.preciocontrato',
                'venta.fecha as fechaVenta')
                ->join('lote', 'lote.id', '=', 'venta.lote_id')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('persona as personaventa', 'personaventa.id', '=', 'venta.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
                // ['ubigeo.tipoubigeo_id','=',3],
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
                // 'cochera_id' => $request->cochera_id,
                'local_id' => $request->local_id,
                'lote_id' => $request->lote_id,
                'persona_id' => $request->persona_id,
                'fecha' => $request->fecha,
                'estado' => $request->estado
            ]);
            // luego cambio de estado a la propiedad
            if ($request->apartamento_id) {
                //
                $apartamento = Apartamento::where('id', $request->apartamento_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
            } else if ($request->casa_id) {
                $casa = Casa::where('id', $request->casa_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
            } else if ($request->cochera_id) {
                //
                $cochera = Cochera::where('id', $request->cochera_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
            } else if ($request->local_id) {
                //
                $local = Local::where('id', $request->local_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
            } else if ($request->lote_id) {
                //
                $lote = Lote::where('id', $request->lote_id)->update(['contrato'=>'V', 'estadocontrato'=>'V']);
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

            // adquiririmos los datos de la venta y el cliente
            $venta = Venta::FindOrFail($id);
            $cliente = Persona::FindOrFail($venta->persona_id);
            $ventaDto->setVentaDto($venta->id, $venta->fecha, $venta->estado);
            $ventaDto->setCliente($cliente);

            // luego la propiedad respectiva
            if ($venta->apartamento_id) {
                // APARTAMENTO
                $apartamentoController = new ApartamentoController();
                $respuestaPropiedad = $apartamentoController->show($venta->apartamento_id);
                $apartamento = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setApartamento($apartamento);
            } else if ($venta->casa_id) {
                // CASA
                $casaController = new CasaController();
                $respuestaPropiedad = $casaController->show($venta->casa_id);
                $casa = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setCasa($casa);
            } else if ($venta->cochera_id) {
                // COCHERA
                $cocheraController = new CocheraController();
                $respuestaPropiedad = $cocheraController->show($venta->cochera_id);
                $cochera = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setCochera($cochera);
            } else if ($venta->local_id) {
                // LOCAL
                $localController = new LocalController();
                $respuestaPropiedad = $localController->show($venta->local_id);
                $local = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setLocal($local);
            } else if ($venta->lote_id) {
                // LOTE
                $loteController = new LoteController();
                $respuestaPropiedad = $loteController->show($venta->lote_id);
                $lote = $respuestaPropiedad->original->extraInfo;
                $ventaDto->setLote($lote);
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

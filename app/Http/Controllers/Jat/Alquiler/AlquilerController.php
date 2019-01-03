<?php

namespace App\Http\Controllers\Jat\Alquiler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Alquiler;
use App\Models\Apartamento;
use App\Models\Casa;
use App\Models\Cochera;
use App\Models\Habitacion;
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
use App\Http\Controllers\Jat\HabitacionController;
use App\Http\Controllers\Jat\LoteController;
use App\Dto\AlquilerDto;

class AlquilerController extends Controller
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

    /**Nota: todas las propiedades (casas lotes habitaciones locales cocheras y 
     * apartamentos) son para alquileres */
    public function listarAlquileres(Request $request) {
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $alquileres = null;
            $propiedad = "";
            switch ($request->input('propiedad')) {
                case 'Apartamento':
                    $alquileres = $this->listarAlquileresApartamentos($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'apartamentos';
                    break;
                case 'Casa':
                    $alquileres = $this->listarAlquileresCasas($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'casas';
                    break;
                case 'Cochera':
                    $alquileres = $this->listarAlquileresCocheras($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'cocheras';
                    break;
                case 'Habitación':
                    $alquileres = $this->listarAlquileresHabitaciones($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'habitaciones';
                    break;
                case 'Local':
                    $alquileres = $this->listarAlquileresLocales($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'locales';
                    break;
                case 'Lote':
                    $alquileres = $this->listarAlquileresLotes($request->input('ubigeo.tipoubigeo_id'), 
                                                        $request->input('ubigeo.codigo'));
                    $propiedad = 'lotes';
                    break;
            }
            if ($alquileres !== 'error') {
                if ($alquileres!==null && !$alquileres->isEmpty()) {
                    $respuesta->setEstadoOperacion('EXITO');
                    $respuesta->setExtraInfo($alquileres);
                } else {
                    $respuesta->setEstadoOperacion('ADVERTENCIA');
                    $respuesta->setOperacionMensaje('No se encontraron alquileres '.$propiedad);
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

    public function listarAlquileresApartamentos($tipoubigeo,$codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion !== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'apartamento.estadocontrato', 
            'apartamento.foto', 'alquiler.apartamento_id as propiedad_id',
            'apartamento.codigo as propiedad_codigo', 'personaalquiler.nombres as cliente', 
            'persona.nombres as propietario', 'alquiler.fechahasta',
            'ubigeo.ubigeo as ubicacion', 'apartamento.direccion', 'apartamento.preciocontrato', 
            'alquiler.fechadesde')
            ->join('apartamento', 'apartamento.id', '=', 'alquiler.apartamento_id')
            ->join('persona', 'persona.id', '=', 'apartamento.persona_id')
            ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
            ->join('ubigeo', 'ubigeo.id', '=', 'apartamento.ubigeo_id')
            ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
    }

    public function listarAlquileresCasas($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'casa.estadocontrato', 'casa.foto', 
                'alquiler.casa_id as propiedad_id', 'casa.codigo as propiedad_codigo', 
                'personaalquiler.nombres as cliente', 'persona.nombres as propietario', 
                'alquiler.fechahasta', 'ubigeo.ubigeo as ubicacion', 'casa.direccion', 
                'casa.preciocontrato', 'alquiler.fechadesde')
                ->join('casa', 'casa.id', '=', 'alquiler.casa_id')
                ->join('persona', 'persona.id', '=', 'casa.persona_id')
                ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'casa.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
    }

    public function listarAlquileresCocheras($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'cochera.estadocontrato', 'cochera.foto', 
                'alquiler.cochera_id as propiedad_id', 'cochera.codigo as propiedad_codigo', 
                'personaalquiler.nombres as cliente', 'persona.nombres as propietario', 'alquiler.fechahasta',
                'ubigeo.ubigeo as ubicacion', 'cochera.direccion', 'cochera.preciocontrato', 
                'alquiler.fechadesde')
                ->join('cochera', 'cochera.id', '=', 'alquiler.cochera_id')
                ->join('persona', 'persona.id', '=', 'cochera.persona_id')
                ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'cochera.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
    }

    public function listarAlquileresHabitaciones($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'habitacion.estadocontrato', 'habitacion.foto', 
                'alquiler.habitacion_id as propiedad_id', 'habitacion.codigo as propiedad_codigo', 
                'personaalquiler.nombres as cliente', 'persona.nombres as propietario', 'alquiler.fechahasta',
                'ubigeo.ubigeo as ubicacion', 'habitacion.direccion', 'habitacion.preciocontrato', 'alquiler.fechadesde')
                ->join('habitacion', 'habitacion.id', '=', 'alquiler.habitacion_id')
                ->join('persona', 'persona.id', '=', 'habitacion.persona_id')
                ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'habitacion.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
    }

    public function listarAlquileresLocales($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'local.estadocontrato', 'local.foto', 
                'alquiler.local_id as propiedad_id', 'local.codigo as propiedad_codigo', 
                'personaalquiler.nombres as cliente', 'persona.nombres as propietario', 'alquiler.fechahasta',
                'ubigeo.ubigeo as ubicacion', 'local.direccion', 'local.preciocontrato', 
                'alquiler.fechadesde')
                ->join('local', 'local.id', '=', 'alquiler.local_id')
                ->join('persona', 'persona.id', '=', 'local.persona_id')
                ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'local.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
    }

    public function listarAlquileresLotes($tipoubigeo, $codigo)
    {
        # code...
        // para la condicion del ubigeo
        $condicion = $this->mostrarCondicionUbigeo($tipoubigeo,$codigo);
        if ($condicion!== 'error') { // AlquilerTO
            $alquileres = Alquiler::select('alquiler.id', 'lote.estadocontrato', 'lote.foto', 'alquiler.lote_id as propiedad_id',
                'lote.codigo as propiedad_codigo', 'personaalquiler.nombres as cliente', 'persona.nombres as propietario', 'alquiler.fechahasta',
                'ubigeo.ubigeo as ubicacion', 'lote.direccion', 'lote.preciocontrato', 'alquiler.fechadesde')
                ->join('lote', 'lote.id', '=', 'alquiler.lote_id')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('persona as personaalquiler', 'personaalquiler.id', '=', 'alquiler.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where([['ubigeo.tipoubigeo_id','=',3],['ubigeo.codigo',$condicion[1], $condicion[2]]])->get();
        } else {
            $alquileres = 'error';
        }
        return $alquileres;
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
            $alquiler = Alquiler::create([
                'apartamento_id' => $request->apartamento_id,
                'casa_id' => $request->casa_id,
                'cochera_id' => $request->cochera_id,
                'habitacion_id' => $request->habitacion_id,
                'local_id' => $request->local_id,
                'lote_id' => $request->lote_id,
                'persona_id' => $request->persona_id,
                'fechadesde' => $request->fechadesde,
                'fechahasta' => $request->fechahasta,
                'estado' => $request->estado
            ]);
            // luego cambio de estado a la propiedad
            if ($request->apartamento_id) {
                //
                $apartamento = Apartamento::where('id', $request->apartamento_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            } else if ($request->casa_id) {
                $casa = Casa::where('id', $request->casa_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            } else if ($request->cochera_id) {
                //
                $cochera = Cochera::where('id', $request->cochera_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            } else if ($request->habitacion_id) {
                //
                $habitacion = Habitacion::where('id', $request->habitacion_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            }
            else if ($request->local_id) {
                //
                $local = Local::where('id', $request->local_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            } else if ($request->lote_id) {
                //
                $lote = Lote::where('id', $request->lote_id)->update(['contrato'=>'A', 'estadocontrato'=>'A']);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El alquiler se ha guardado correctamente.');
            $respuesta->setExtraInfo($alquiler);
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
            $alquilerDto = new AlquilerDto();

            // adquiririmos los datos del alquiler y el cliente
            $alquiler = Alquiler::FindOrFail($id);
            $cliente = Persona::FindOrFail($alquiler->persona_id);
            $alquilerDto->setAlquilerDto($alquiler->id, $alquiler->fechadesde, $alquiler->fechahasta, $alquiler->estado);
            $alquilerDto->setCliente($cliente);

            // luego la propiedad respectiva
            if ($alquiler->apartamento_id) {
                // APARTAMENTO
                $apartamentoController = new ApartamentoController();
                $respuestaPropiedad = $apartamentoController->show($alquiler->apartamento_id);
                $apartamento = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setApartamento($apartamento);
            } else if ($alquiler->casa_id) {
                // CASA
                $casaController = new CasaController();
                $respuestaPropiedad = $casaController->show($alquiler->casa_id);
                $casa = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setCasa($casa);
            } else if ($alquiler->cochera_id) {
                // COCHERA
                $cocheraController = new CocheraController();
                $respuestaPropiedad = $cocheraController->show($alquiler->cochera_id);
                $cochera = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setCochera($cochera);
            } else if ($alquiler->habitacion_id) {
                // HABITACION
                $habitacionController = new HabitacionController();
                $respuestaPropiedad = $habitacionController->show($alquiler->habitacion_id);
                $habitacion = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setHabitacion($habitacion);
            } else if ($alquiler->local_id) {
                // LOCAL
                $localController = new LocalController();
                $respuestaPropiedad = $localController->show($alquiler->local_id);
                $local = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setLocal($local);
            } else if ($alquiler->lote_id) {
                // LOTE
                $loteController = new LoteController();
                $respuestaPropiedad = $loteController->show($alquiler->lote_id);
                $lote = $respuestaPropiedad->original->extraInfo;
                $alquilerDto->setLote($lote);
            }
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($alquilerDto);
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

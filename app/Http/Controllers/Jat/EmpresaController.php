<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Ubigeo;
use App\Dto\EmpresaDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use App\EntityWeb\Utils\RespuestaWebTO;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $empresa = Empresa::select('empresa.id', 'nombre', 'ruc', 'direccion', 'empresa.latitud', 'empresa.longitud',
            'telefono','correo', 'nombrefoto','foto', 'ubigeo.ubigeo', 'empresa.ubigeo_id as idubigeo', 'empresa.estado')
            ->join('ubigeo', 'ubigeo.id', '=', 'empresa.ubigeo_id')->first();

            if ($empresa != "") {
                $empresadto = new EmpresaDto();
                $ubigeodetalledto = new UbigeoDetalleDto();
                $ubigeodto = new UbigeoDto();

                $empresadto->setEmpresa($empresa);

                // ubigeo
                $ubigeo = Ubigeo::FindOrFail($empresa->idubigeo); // siempre es el ubigeo distrito
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
                $empresadto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
                // end ubigeo
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($empresadto);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontro empresa');
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
            $empresa = Empresa::create([
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'nombre' => $request->nombre,
                'ruc' => $request->ruc,
                'direccion' => $request->direccion,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'nombrefoto' => $request->nombrefoto,
                'foto' => $request->foto,
                'estado' => $request->estado
            ]);
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La empresa: ruc: '.$request->ruc.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($empresa);
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
            $empresa = Empresa::FindOrFail($id);
            // $input = $request->all();
            $input = [
                'ubigeo_id' => $request->input('ubigeo_id.id'),
                'nombre' => $request->nombre,
                'ruc' => $request->ruc,
                'direccion' => $request->direccion,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'nombrefoto' => $request->nombrefoto,
                'foto' => $request->foto,
                'estado' => $request->estado
            ];

            $empresa->fill($input)->save();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('La empresa: ruc: '.$request->ruc.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($empresa);
            /*$empresa = Empresa::FindOrFail($id);
            $input = $request->all();
            $empresa->fill($input)->save();*/
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
    }
}

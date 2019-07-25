<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ubigeo;
use App\Models\UbigeoTipo;
use App\Models\HabilitacionUrbana;
use App\Dto\UbigeoDto;
use App\Dto\UbigeoDetalleDto;
use DB;
use App\EntityWeb\Utils\RespuestaWebTO;
class UbigeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
        'habilitacionurbana_id', 'estado')
            ->where('tipoubigeo_id',1)->get();
        return response()->json($ubigeos, 200);
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
        /*
        **{
            departamento {}
            provincia {}
            distrito {}
            habilitacionurbana {}
            ubigeo {}
        **}
         */
        $codigo = NULL;
        $idtipoubigeo = $request->input('ubigeo.tipoubigeo_id.id');
        if ($idtipoubigeo == 1) { // departamento
            $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
            $cantidad ++;
            if ($cantidad < 10) {
                $codigo = "0".$cantidad."00000000";
            } else {
                $codigo = $cantidad + "00000000";
            }
        } else if ($idtipoubigeo == 2) { // provincia
            $codigodepartamento = $request->input('departamento.codigo'); // codigo del departamento
            $subs = substr($codigodepartamento, 0, 2); // ejmp: 01
            $cantidad = Ubigeo::where('codigo','like', $subs."%")->count(); 
            // aqui cuenta: 1 (departamento) + ... (provincias)
            if ($cantidad < 10) {
                $codigo = $subs."0".$cantidad."000000";
            } else {
                $codigo = $subs.$cantidad + "000000";
            }
        } else if ($idtipoubigeo == 3) { // distrito
            $codigoprovincia = $request->input('provincia.codigo');
            $subs = substr($codigoprovincia, 0, 4); // ejmp: 0101
            $cantidad = Ubigeo::where('codigo','like', $subs."%")->count();
            if ($cantidad < 10) {
                $codigo = $subs."0".$cantidad."0000";
            } else {
                $codigo = $subs.$cantidad + "0000";
            }
        } else if ($idtipoubigeo == 4) { // habilitacion urbana
            $codigodistrito = $request->input('distrito.codigo');
            $subs = substr($codigodistrito, 0, 6); // ejmp: 010101
            $cantidad = Ubigeo::where('codigo','like', $subs."%")->count(); 
            if ($cantidad < 10) {
                $codigo = $subs."0".$cantidad."00";
            } else {
                $codigo = $subs.$cantidad + "00";
            }
        }
        $ubigeo = Ubigeo::create([
            'tipoubigeo_id' => $idtipoubigeo,
            'ubigeo' => $request->input('ubigeo.ubigeo'),
            'rutaubigeo' => $request->input('ubigeo.rutaubigeo'),
            'habilitacionurbana_id' => $request->input('ubigeo.habilitacionurbana_id.id'),
            'codigo' => $codigo,
            'estado' => $request->input('ubigeo.estado')
        ]);
        return response()->json($ubigeo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function searchUbigeo($ubigeo)
     {
         # code...
         $ubigeos = Ubigeo::where('rutaubigeo','like','%'.$ubigeo.'%')->get();
         return response()->json($ubigeos, 200);
     }

    public function buscarUbigeosHabilitacionUrbana($ubigeo) {
        $ubigeos = Ubigeo::where([['rutaubigeo','like','%'.$ubigeo.'%'],
        ['tipoubigeo_id','=','4']])->get();
        return response()->json($ubigeos, 200);
    }

    public function buscarUbigeosDistrito($ubigeo)
    {
        # code...
        $ubigeos = Ubigeo::where([['rutaubigeo','like','%'.$ubigeo.'%'],
        ['tipoubigeo_id','=','3']])->get();
        return response()->json($ubigeos, 200);
    }

    public function buscarDistritosYHabilitaciones($ubigeo)
    {
        # code...
        $ubigeos = Ubigeo::where([['rutaubigeo','like','%'.$ubigeo.'%'],
        ['tipoubigeo_id','>','2']])->get();
        return response()->json($ubigeos, 200);
    }

    public function mostrarUbigeoProvincia(Request $request)
    {
        # code...
        /** $request =>
         * parametros {
         *  departamento (string),
         *  provincia (string)
         * }
         * Retorna el {ubigeoProvincia y ubigeoDepartamento}
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $departamento = $request->input('departamento');
            $provincia = $request->input('provincia');
            
            $ubigeoDepartamento = Ubigeo::where([['ubigeo', '=', $departamento],
            ['tipoubigeo_id','=','1']])->first();
            $subs = substr($ubigeoDepartamento->codigo, 0, 2); // ejmp: 01
            $ubigeoProvincia = Ubigeo::where([['ubigeo', '=', $provincia],
            ['tipoubigeo_id','=','2'], ['codigo','like','%'.$subs.'%']])
            ->first();

            if ($ubigeoProvincia!==null) {
                $parametros = [$ubigeoDepartamento, $ubigeoProvincia ];
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($parametros);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontro provincia');
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
    
    public function mostrarUbigeoAnterior(Request $request)
    {
        # code...
        /**
         * $request = Ubigeo (Objetecot)
         * Quiero el ubigeo anterior !! 
         * Ejemplo: Me retorne el UbigeoDepartamento del $request (UbigeoProvincia)
         */
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ubigeo = null;
            $tipoubigeo_id = $request->input('tipoubigeo_id');
            if ($tipoubigeo_id == 1) {
                // departamento retornando departamento mismo
                $ubigeo = $request;
            } else if ($tipoubigeo_id == 2) {
                // provincia retornando departamento perteneciente
                $codigo = $request->input('codigo');
                $subs = substr($codigo, 0, 2); // ejmp: 01
                $ubigeo = Ubigeo::where([['codigo', 'like','%'.$subs.'%'],
                ['tipoubigeo_id','=',1]])->first();
            } else if ($tipoubigeo_id == 3) {
                // provincia retornando departamento perteneciente
                $codigo = $request->input('codigo');
                $subs = substr($codigo, 0, 4); // ejmp: 0101
                $ubigeo = Ubigeo::where([['codigo', 'like','%'.$subs.'%'],
                ['tipoubigeo_id','=',2]])->first();
            } else if ($tipoubigeo_id == 4) {
                // provincia retornando departamento perteneciente
                $codigo = $request->input('codigo');
                $subs = substr($codigo, 0, 6); // ejmp: 010101
                $ubigeo = Ubigeo::where([['codigo', 'like','%'.$subs.'%'],
                ['tipoubigeo_id','=',3]])->first();
            }

            if ($ubigeo!==null) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($ubigeo);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontro ubigeo');
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

    public function mostrarubigeos($tipoubigeo_id, $codigo)
    {
        # code...
        $ubigeos = "";
        if ($tipoubigeo_id == 1) { // departamento
            // aqui seleccionamos todas las provincias perteneciente al departamento
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo', 
                'habilitacionurbana_id', 'estado')
                ->where([['tipoubigeo_id','=',2], ['codigo','like',$subs.'%']])->get();
        } else if ($tipoubigeo_id == 2) { // provincia
            // aqui seleccionamos todas los distritos perteneciente a la provincia
            $subs = substr($codigo, 0, 4); // ejmp: 0101
            $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
             'habilitacionurbana_id', 'estado')
                ->where([['tipoubigeo_id','=',3], ['codigo','like',$subs.'%']])->get();
        } else if ($tipoubigeo_id == 3) { // distrito
            // aqui seleccionamos todas los ubigeos -> habilitaciones urbanas
            $subs = substr($codigo, 0, 6); // ejmp: 010101
            $ubigeos = Ubigeo::select('ubigeo.id', 'codigo', 'tipoubigeo_id', 'habilitacionurbana_id',
                'ubigeo', 'rutaubigeo', 'ubigeo.estado', 'habilitacionurbana.siglas')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['tipoubigeo_id','=',4], ['codigo','like',$subs.'%']])->get();
        }
        return response()->json($ubigeos, 200);
    }

    public function listarubigeos(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            if ($request->input('activos') === true) {
                $estados = [true];
            } else if ($request->input('activos') === false) {
                $estados = [true, false];
            } else {
                $estados = [];
            }
            $ubigeos = null;
            $tipoubigeo_id = $request->input('tipoubigeo_id');
            $codigo = $request->input('codigo');
            if ($tipoubigeo_id == 0) { //
                // listamos todos los departamentos
                $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo',
                'rutaubigeo', 'habilitacionurbana_id', 'estado')
                ->where('tipoubigeo_id',1)
                ->whereIn('ubigeo.estado', $estados)->get();            
            } else if ($tipoubigeo_id == 1) { // departamento
                // listamos todas las provincias perteneciente al departamento
                $subs = substr($codigo, 0, 2); // ejmp: 01
                $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo',
                'rutaubigeo', 'habilitacionurbana_id', 'estado')
                ->where([['tipoubigeo_id','=',2], ['codigo','like',$subs.'%']])
                ->whereIn('ubigeo.estado', $estados)->get();
            } else if ($tipoubigeo_id == 2) { // provincia
                // listamos todas los distritos perteneciente a la provincia
                $subs = substr($codigo, 0, 4); // ejmp: 0101
                $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo',
                'rutaubigeo', 'habilitacionurbana_id', 'estado')
                ->where([['tipoubigeo_id','=',3], ['codigo','like',$subs.'%']])
                ->whereIn('ubigeo.estado', $estados)->get();
            } else if ($tipoubigeo_id == 3) { // distrito
                // listamos todas los ubigeos -> habilitaciones urbanas
                $subs = substr($codigo, 0, 6); // ejmp: 010101
                $ubigeos = Ubigeo::select('ubigeo.id', 'codigo', 'tipoubigeo_id', 'habilitacionurbana_id',
                'ubigeo', 'rutaubigeo', 'ubigeo.estado', 'habilitacionurbana.siglas')
                ->join('habilitacionurbana', 'habilitacionurbana.id', '=', 'ubigeo.habilitacionurbana_id')
                ->where([['tipoubigeo_id','=',4], ['codigo','like',$subs.'%']])
                ->whereIn('ubigeo.estado', $estados)->get();
            }
            // agregamos los ubigeos en el objeto respuesta
            if ($ubigeos!==null && !$ubigeos->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($ubigeos);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron ubigeos');
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

    public function buscarubigeos(Request $request)
    {
        # code...
        $idtipoubigeo = $request->input('ubigeo.tipoubigeo_id.id'); //
        $codigo = $request->input('ubigeo.codigo'); // nuevo
        $nombreubigeo = $request->input('ubigeo.ubigeo'); // nuevo
        $ubigeos = "";
        if ($request->input('departamento') == null && 
            $request->input('provincia') == null) {
            // busqueda de departamentos
            $ubigeos = Ubigeo::where([['tipoubigeo_id',1],
                ['ubigeo','like','%'.$nombreubigeo.'%']])->get();
        } else {
            if ($request->input('provincia') == null) {
                // lista las provincias del departamento
                $codigodepartamento = $request->input('departamento.codigo');
                $subs = substr($codigodepartamento, 0, 2);
                $ubigeos = Ubigeo::where([['tipoubigeo_id',2],
                ['ubigeo','like','%'.$nombreubigeo.'%'],
                ['codigo','like',$subs.'%']])->get();
            } else {
                // lista los distritos
                $codigoprovincia = $request->input('provincia.codigo');
                $subs = substr($codigoprovincia, 0, 3);
                $ubigeos = Ubigeo::where([['tipoubigeo_id',3],
                ['ubigeo','like','%'.$nombreubigeo.'%'],
                ['codigo','like',$subs.'%']])->get();
            }
        }
        // $ubigeos = Ubigeo::where('tipoubigeo_id',$id)->get();
        return response()->json($ubigeos, 200);
    }

    public function show($id)
    {
        //
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();
        // $ubigeo = Ubigeo::select('ubigeo.id','ubigeo', 'rutaubigeo', 'codigo','ubigeo.estado', 
        //         'ubigeo.tipoubigeo_id as idtipoubigeo', 'ubigeo.habilitacionurbana_id')
        //         ->join('ubigeotipo', 'ubigeotipo.id', '=', 'ubigeo.tipoubigeo_id')
        //         ->where('ubigeo.id','=',$id)->first();

        $ubigeo = Ubigeo::FindOrFail($id); // siempre es el ubigeo distrito

        $ubigeodto->setUbigeo($ubigeo);
        $tipoubigeo = UbigeoTipo::FindOrFail($ubigeo->tipoubigeo_id);
        $ubigeodto->setTipoUbigeo($tipoubigeo);
        // habilitacionurbana
        // $habilitacionurbana = HabilitacionUrbana::FindOrFail($ubigeo->habilitacionurbana_id);
        // $ubigeodto->setHabilitacionUrbana($habilitacionurbana);
        if ($ubigeo->habilitacionurbana_id) {
            /**Existe habilitacionurbana_id cuando el ubigeo es de tipo habilitacion urbana
             * (osea que el ubigeo no se departamento, provincia ni distrito)
             * entonces se busca la habiitacionurbana (AAHH, Urbanizacion, etc)
            */
            $habilitacionurbana = HabilitacionUrbana::FindOrFail($ubigeo->habilitacionurbana_id);
            $ubigeodto->setHabilitacionUrbana($habilitacionurbana);
        }

        if ($ubigeo->tipoubigeo_id == 2) { // provincia
            // seleccionamos su departamento de la provincia
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

        } else if ($ubigeo->tipoubigeo_id == 3) { // distrito
            // seleccionamos su provincia del distrito
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

            $subs = substr($codigo, 0, 4)."000000";
            $provincia = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setProvincia($provincia);
        } else if ($ubigeo->tipoubigeo_id == 4) { // habilitacion urbana
            // seleccionamos su distrito de la habilitacion urbana
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

            $subs = substr($codigo, 0, 4)."000000";
            $provincia = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setProvincia($provincia);

            $subs = substr($codigo, 0, 6)."0000";
            $distrito = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo',
            'habilitacionurbana_id', 'estado')->where('codigo',$subs)->first();
            $ubigeodetalledto->setDistrito($distrito);
        }
        $ubigeodetalledto->setUbigeo($ubigeodto);
        return response()->json($ubigeodetalledto, 201);
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
         # code...
         try {
            //code...
            $respuesta = new RespuestaWebTO();
            $ubigeo = Ubigeo::FindOrFail($id);
            $input = [
                'ubigeo' => $request->input('ubigeo.ubigeo'),
                'rutaubigeo' => $request->input('ubigeo.rutaubigeo'),
                'estado' => $request->input('ubigeo.estado')
            ];
            $ubigeo->fill($input)->save();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El ubigeo: '.$request->input('ubigeo.ubigeo').', se ha actualizado correctamente.');
            $respuesta->setExtraInfo($ubigeo);
         } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
     }
    // public function update(Request $request, $id)
    // {
    //     //
    //     $ubigeo = Ubigeo::FindOrFail($id); // actual
    //     $idtipoubigeo = $request->input('ubigeo.tipoubigeo_id.id'); // nuevo
    //     $codigo = $ubigeo->codigo;
    //     // $mensaje = "inicio";
    //     if ($request->input('departamento') != null || $request->input('provincia') != null) {
    //         // $mensaje = "entro ";
    //         if ($ubigeo->tipoubigeo_id == 1) { // departamento
    //             if ($idtipoubigeo == 2) { // de departamento a provincia
    //                 $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
    //                 $subs = substr($codigo, 0, 2);
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad ++;
    //                 // $mensaje += "entro aca";
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."000000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "000000";
    //                 }
    //             } else if ($idtipoubigeo == 3) { // de departamento a distrito
    //                 $codigo = $request->input('provincia.codigo'); // codigo de la misma provincia
    //                 $subs = substr($codigo, 0, 4);
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad ++;
    //                 // $mensaje += "entro aca";
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."0000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "0000";
    //                 }
    //             }
    //         } else if ($ubigeo->tipoubigeo_id == 2) { // provincia
    //             // seleccionamos su departamento de la provincia
    //             // $mensaje += "entro provincia ";
    //             if ($idtipoubigeo == 1) { // he cambiado de provincia a departamento
    //                 $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
    //                 $cantidad ++;
    //                 // $mensaje += "entro aca";
    //                 if ($cantidad < 10) {
    //                     $codigo = "0".$cantidad."00000000";
    //                 } else {
    //                     $codigo = $cantidad + "00000000";
    //                 }
    //             } else if ($idtipoubigeo == 2) { // he cambiado de departamento a otro departamento
    //                 $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
    //                 $subs = substr($codigo, 0, 2);
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad ++;
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."000000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "000000";
    //                 }
    //             }
    //             else if ($idtipoubigeo == 3) { // he cambiado de provincia a distrito
    //                 $codigo = $request->input('provincia.codigo'); // codigo de la misma provincia
    //                 $subs = substr($codigo, 0, 4);
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad ++;
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."0000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "0000";
    //                 }
    //             }
    //         } else if ($ubigeo->tipoubigeo_id == 3) { // distrito
    //             // seleccionamos su provincia del distrito
    //             if ($idtipoubigeo == 1) { // he cambiado de distrito a departamento
    //                 $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
    //                 $cantidad ++;
    //                 if ($cantidad < 10) {
    //                     $codigo = "0".$cantidad."00000000";
    //                 } else {
    //                     $codigo = $cantidad + "00000000";
    //                 }
    //             } else if ($idtipoubigeo == 2) { // he cambiado de distrito a provincia
    //                 $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
    //                 $subs = substr($codigo, 0, 2); // subs de departamento
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad++;
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."000000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "000000";
    //                 }
    //             } else if ($idtipoubigeo == 3) {
    //                 $codigo = $request->input('provincia.codigo'); // he cambiado de provincia a otra provincia
    //                 $subs = substr($codigo, 0, 4); // subs de departamento
    //                 $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
    //                 ['codigo','like',$subs.'%']])->count();
    //                 $cantidad++;
    //                 if ($cantidad < 10) {
    //                     $codigo = $subs."0".$cantidad."0000";
    //                 } else {
    //                     $codigo = $subs.$cantidad + "0000";
    //                 }
    //             }
    //         }
    //     } else { // se va ha cambiar para departamento
    //         $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
    //         $cantidad ++;
    //         if ($cantidad < 10) {
    //             $codigo = "0".$cantidad."00000000";
    //         } else {
    //             $codigo = $cantidad + "00000000";
    //         }
    //     }

    //     $input = [
    //         'tipoubigeo_id' => $idtipoubigeo,
    //         'ubigeo' => $request->input('ubigeo.ubigeo'),
    //         'rutaubigeo' => $request->input('ubigeo.rutaubigeo'),
    //         'codigo' => $codigo,
    //         'estado' => $request->input('ubigeo.estado')
    //     ];
    //     $ubigeo->fill($input)->save();

    //     return response()->json($ubigeo, 201);
    // }

    public function cambiarEstadoUbigeo(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            // antes de cambiar estado hay que verificar si existe ubigeos
            // dentro del ubigeo y ademas que se encuentren ya usados en las propiedades
            // personas. empresa. etc
            // eso hay que verlo porque falta
            $habitacion = Ubigeo::where('id', $request->input('id'))
            ->update(['estado'=>$request->input('activar')]);

            if ($habitacion!==null && $habitacion!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El ubigeo: cod '.$request->ubigeo.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($habitacion);
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

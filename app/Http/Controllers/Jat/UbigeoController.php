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
        $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo', 'estado')
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
    public function mostrarubigeos($tipoubigeo_id, $codigo)
    {
        # code...
        $ubigeos = "";
        if ($tipoubigeo_id == 1) { // departamento
            // aqui seleccionamos todas las provincias perteneciente al departamento
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo', 'estado')
                ->where([['tipoubigeo_id','=',2], ['codigo','like',$subs.'%']])->get();
        } else if ($tipoubigeo_id == 2) { // provincia
            // aqui seleccionamos todas los distritos perteneciente a la provincia
            $subs = substr($codigo, 0, 4); // ejmp: 0101
            $ubigeos = Ubigeo::select('id', 'codigo', 'tipoubigeo_id', 'ubigeo', 'rutaubigeo', 'estado')
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
        $ubigeo = Ubigeo::select('ubigeo.id','ubigeo', 'rutaubigeo', 'codigo','ubigeo.estado', 
                'ubigeo.tipoubigeo_id as idtipoubigeo', 'ubigeo.habilitacionurbana_id as idhabilitacionurbana')
                ->join('ubigeotipo', 'ubigeotipo.id', '=', 'ubigeo.tipoubigeo_id')
                ->where('ubigeo.id','=',$id)->first();
        $ubigeodto->setUbigeo($ubigeo);
        $tipoubigeo = UbigeoTipo::FindOrFail($ubigeo->idtipoubigeo);
        $ubigeodto->setTipoUbigeo($tipoubigeo);
        if ($ubigeo->idhabilitacionurbana) {
            $habilitacionurbana = HabilitacionUrbana::FindOrFail($ubigeo->idhabilitacionurbana);
            $ubigeodto->setHabilitacionUrbana($habilitacionurbana);
        }

        if ($ubigeo->idtipoubigeo == 2) { // provincia
            // seleccionamos su departamento de la provincia
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

        } else if ($ubigeo->idtipoubigeo == 3) { // distrito
            // seleccionamos su provincia del distrito
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

            $subs = substr($codigo, 0, 4)."000000";
            $provincia = Ubigeo::where('codigo',$subs)->first();
            $ubigeodetalledto->setProvincia($provincia);
        } else if ($ubigeo->idtipoubigeo == 4) { // habilitacion urbana
            // seleccionamos su distrito de la habilitacion urbana
            $codigo = $ubigeo->codigo;
            $subs = substr($codigo, 0, 2)."00000000";
            $departamento = Ubigeo::where('codigo',$subs)->first();
            $ubigeodetalledto->setDepartamento($departamento);

            $subs = substr($codigo, 0, 4)."000000";
            $provincia = Ubigeo::where('codigo',$subs)->first();
            $ubigeodetalledto->setProvincia($provincia);

            $subs = substr($codigo, 0, 6)."0000";
            $distrito = Ubigeo::where('codigo',$subs)->first();
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
        //
        $ubigeo = Ubigeo::FindOrFail($id); // actual
        $idtipoubigeo = $request->input('ubigeo.tipoubigeo_id.id'); // nuevo
        $codigo = $ubigeo->codigo;
        // $mensaje = "inicio";
        if ($request->input('departamento') != null || $request->input('provincia') != null) {
            // $mensaje = "entro ";
            if ($ubigeo->tipoubigeo_id == 1) { // departamento
                if ($idtipoubigeo == 2) { // de departamento a provincia
                    $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
                    $subs = substr($codigo, 0, 2);
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad ++;
                    // $mensaje += "entro aca";
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."000000";
                    } else {
                        $codigo = $subs.$cantidad + "000000";
                    }
                } else if ($idtipoubigeo == 3) { // de departamento a distrito
                    $codigo = $request->input('provincia.codigo'); // codigo de la misma provincia
                    $subs = substr($codigo, 0, 4);
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad ++;
                    // $mensaje += "entro aca";
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."0000";
                    } else {
                        $codigo = $subs.$cantidad + "0000";
                    }
                }
            } else if ($ubigeo->tipoubigeo_id == 2) { // provincia
                // seleccionamos su departamento de la provincia
                // $mensaje += "entro provincia ";
                if ($idtipoubigeo == 1) { // he cambiado de provincia a departamento
                    $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
                    $cantidad ++;
                    // $mensaje += "entro aca";
                    if ($cantidad < 10) {
                        $codigo = "0".$cantidad."00000000";
                    } else {
                        $codigo = $cantidad + "00000000";
                    }
                } else if ($idtipoubigeo == 2) { // he cambiado de departamento a otro departamento
                    $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
                    $subs = substr($codigo, 0, 2);
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad ++;
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."000000";
                    } else {
                        $codigo = $subs.$cantidad + "000000";
                    }
                }
                else if ($idtipoubigeo == 3) { // he cambiado de provincia a distrito
                    $codigo = $request->input('provincia.codigo'); // codigo de la misma provincia
                    $subs = substr($codigo, 0, 4);
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad ++;
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."0000";
                    } else {
                        $codigo = $subs.$cantidad + "0000";
                    }
                }
            } else if ($ubigeo->tipoubigeo_id == 3) { // distrito
                // seleccionamos su provincia del distrito
                if ($idtipoubigeo == 1) { // he cambiado de distrito a departamento
                    $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
                    $cantidad ++;
                    if ($cantidad < 10) {
                        $codigo = "0".$cantidad."00000000";
                    } else {
                        $codigo = $cantidad + "00000000";
                    }
                } else if ($idtipoubigeo == 2) { // he cambiado de distrito a provincia
                    $codigo = $request->input('departamento.codigo'); // codigo de la misma provincia
                    $subs = substr($codigo, 0, 2); // subs de departamento
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',2],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad++;
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."000000";
                    } else {
                        $codigo = $subs.$cantidad + "000000";
                    }
                } else if ($idtipoubigeo == 3) {
                    $codigo = $request->input('provincia.codigo'); // he cambiado de provincia a otra provincia
                    $subs = substr($codigo, 0, 4); // subs de departamento
                    $cantidad = Ubigeo::where([['tipoubigeo_id','=',3],
                    ['codigo','like',$subs.'%']])->count();
                    $cantidad++;
                    if ($cantidad < 10) {
                        $codigo = $subs."0".$cantidad."0000";
                    } else {
                        $codigo = $subs.$cantidad + "0000";
                    }
                }
            }
        } else { // se va ha cambiar para departamento
            $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
            $cantidad ++;
            if ($cantidad < 10) {
                $codigo = "0".$cantidad."00000000";
            } else {
                $codigo = $cantidad + "00000000";
            }
        }

        $input = [
            'tipoubigeo_id' => $idtipoubigeo,
            'ubigeo' => $request->input('ubigeo.ubigeo'),
            'rutaubigeo' => $request->input('ubigeo.rutaubigeo'),
            'codigo' => $codigo,
            'estado' => $request->input('ubigeo.estado')
        ];
        $ubigeo->fill($input)->save();

        return response()->json($ubigeo, 201);
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

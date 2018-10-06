<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ubigeo;
use App\Models\UbigeoTipo;
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
        $ubigeos = Ubigeo::where('tipoubigeo_id',1)->get();
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
        } else if ($idtipoubigeo == 3) {
            $codigoprovincia = $request->input('provincia.codigo');
            $subs = substr($codigoprovincia, 0, 4); // ejmp: 0101
            $cantidad = Ubigeo::where('codigo','like', $subs."%")->count();
            if ($cantidad < 10) {
                $codigo = $subs."0".$cantidad."0000";
            } else {
                $codigo = $subs.$cantidad + "0000";
            }
        }
        $ubigeo = Ubigeo::create([
            'tipoubigeo_id' => $idtipoubigeo,
            'ubigeo' => $request->input('ubigeo.ubigeo'),
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
    public function mostrarubigeos($tipoubigeo_id, $codigo)
    {
        # code...
        $ubigeos = "";
        if ($tipoubigeo_id == 1) { // departamento
            // aqui seleccionamos todas las provincias perteneciente al departamento
            $subs = substr($codigo, 0, 2); // ejmp: 01
            $ubigeos = Ubigeo::where([['tipoubigeo_id','=',2],
                ['codigo','like',$subs.'%']])->get();
        } else if ($tipoubigeo_id == 2) { // provincia
            // aqui seleccionamos todas los distritos perteneciente a la provincia
            $subs = substr($codigo, 0, 4); // ejmp: 01
            $ubigeos = Ubigeo::where([['tipoubigeo_id','=',3],
            ['codigo','like',$subs.'%']])->get();
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
        $ubigeo = Ubigeo::select('ubigeo.id','ubigeo', 'codigo','ubigeo.estado', 
                'ubigeo.tipoubigeo_id as idtipoubigeo')
                ->join('ubigeotipo', 'ubigeotipo.id', '=', 'ubigeo.tipoubigeo_id')
                ->where('ubigeo.id','=',$id)->first();
        $ubigeodto->setUbigeo($ubigeo);
        $tipoubigeo = UbigeoTipo::FindOrFail($ubigeo->idtipoubigeo);
        $ubigeodto->setTipoUbigeo($tipoubigeo);

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

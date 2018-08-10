<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ubigeo;
use App\Models\UbigeoTipo;
use App\Dto\UbigeoDto;
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
        $codigo = NULL;
        $idtipoubigeo = $request->input('tipoubigeo_id.id');
        if ($idtipoubigeo == 1) {
            $cantidad = Ubigeo::where('tipoubigeo_id', 1)->count();
            $cantidad ++;
            if ($cantidad < 10) {
                $codigo = "0".$cantidad."00000000";
            } else {
                $codigo = $cantidad + "00000000";
            }
        }
        $ubigeo = Ubigeo::create([
            'tipoubigeo_id' => $request->input('tipoubigeo_id.id'),
            'ubigeo' => $request->ubigeo,
            'codigo' => $codigo,
            'estado' => $request->estado
        ]);
        return response()->json($ubigeo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function buscarubigeo($id)
    {
        # code...
        $ubigeos = Ubigeo::where('tipoubigeo_id',$id)->get();
        return response()->json($ubigeos, 200);
    }

    public function asignarubigeo(Request $request)
    {
        # code...
    }

    public function show($id)
    {
        //
        $ubigeodto = new UbigeoDto();
        $ubigeo = Ubigeo::select('ubigeo.id','ubigeo', 'codigo','ubigeo.estado', 
                'ubigeo.tipoubigeo_id as idtipoubigeo')
                ->join('ubigeotipo', 'ubigeotipo.id', '=', 'ubigeo.tipoubigeo_id')
                ->where('ubigeo.id','=',$id)->first();
        $ubigeodto->setUbigeo($ubigeo);
        $tipoubigeo = UbigeoTipo::FindOrFail($ubigeo->idtipoubigeo);
        $ubigeodto->setTipoUbigeo($tipoubigeo);

        return response()->json($ubigeodto, 201);
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
        $ubigeo = Ubigeo::FindOrFail($id);
        $input = [
            'tipoubigeo_id' => $request->input('tipoubigeo_id.id'),
            'ubigeo' => $request->ubigeo,
            'codigo' => $request->codigo,
            'estado' => $request->estado
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

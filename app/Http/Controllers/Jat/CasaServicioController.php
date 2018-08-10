<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicios;
use App\Models\Casa;
use App\Models\CasaServicio;

class CasaServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($casa_id)
    {
        //
        $casa = Casa::find($casa_id)->casaservicios()->get();//Servicios()->
        return response()->json($casa, 200);
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
    public function store(Request $request)//arreglo de idServicio
    {
        //
        /*for($n=0; $request.length(); $n++){
            $casaservicio = CasaServicio::create([
                'idCasa' => $request[$n]->idCasa,
                'idServicio' => $request[$n]->idServicio
            ]);
        }*/
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
        $casaservicio = CasaServicio::where('servicio_id', $id)->delete();
        // $lotefoto->delete();

        $servicio = Servicios::FindOrFail($id);
        $servicio->delete();
        return response()->json(['exito'=>'Servicio eliminado con id: '.$id], 200);
    }
}

<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Ubigeo;
use App\Dto\EmpresaDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;

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
        $empresadto = "vacio";
        $empresa = Empresa::select('empresa.id', 'nombre', 'ruc', 'direccion','telefono','correo', 
        'nombrefoto','foto', 'ubigeo.ubigeo', 'empresa.ubigeo_id as idubigeo', 'empresa.estado')
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
        } else {
            $empresa = 'vacio';
        }
        
        // echo($empresa);
        return response()->json($empresadto, 200);
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
        $empresa = Empresa::create([
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'nombre' => $request->nombre,
            'ruc' => $request->ruc,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'nombrefoto' => $request->nombrefoto,
            'foto' => $request->foto,
            'estado' => $request->estado
        ]);

        // $empresa = Empresa::create($request->all());
        return response()->json($empresa, 200);
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
        $empresa = Empresa::FindOrFail($id);
        // $input = $request->all();
        $input = [
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'nombre' => $request->nombre,
            'ruc' => $request->ruc,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'nombrefoto' => $request->nombrefoto,
            'foto' => $request->foto,
            'estado' => $request->estado
        ];

        $empresa->fill($input)->save();
         /*$empresa = Empresa::FindOrFail($id);
        $input = $request->all();
        $empresa->fill($input)->save();*/
        return response()->json($empresa, 200);       
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

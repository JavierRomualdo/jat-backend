<?php

namespace App\Http\Controllers\Jat\ApartamentoCuarto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApartamentoCuarto;
use App\Models\ApartamentoCuartoFoto;
use App\Models\Persona;
use App\Models\Foto;

class ApartamentoCuartoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $apartamentocuartos = ApartamentoCuarto::select('apartamentocuarto.id','persona.nombres','precio',
        'largo', 'ancho', 'piso', 'nbanios', 'apartamentocuarto.descripcion', 'path', 'apartamentocuarto.foto', 
        'apartamentocuarto.persona_id', 'apartapartamentocuartoamentopiso.nmensajes', 'apartamentocuarto.estado')
        ->join('persona', 'persona.id', '=', 'apartamentocuarto.persona_id')
        ->join('apartamento', 'apartamento.id', '=', 'apartamentocuarto.apartamento_id')->get();

        return response()->json($apartamentocuartos, 200);
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
        $apartamentocuarto = ApartamentoCuarto::create([
            'apartamento_id' => $request->input('apartamento_id.id'),
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'piso' => $request->piso,
            'nbanios' => $request->nbanios,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'estado' => $request->estado
        ]);

        // asigno las fotos del apartamento piso
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $apartamentofoto = ApartamentoFoto::create([
                'apartamentocuarto_id' => $apartamentocuarto->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }

        // $apartamentocuarto = ApartamentoCuarto::create($request->all());
        return response()->json($apartamentocuarto, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apartamentocuartoApartamento($apartamento_id) {
        $apartamentocuartos = ApartamentoCuarto::select('apartamentocuarto.id','persona.nombres','precio',
        'largo', 'ancho', 'piso', 'nbanios', 'apartamentocuarto.descripcion', 'path', 'apartamentocuarto.foto', 
        'apartamentocuarto.persona_id', 'apartapartamentocuartoamentopiso.nmensajes', 'apartamentocuarto.estado')
        ->join('persona', 'persona.id', '=', 'apartamentocuarto.persona_id')
        ->join('apartamento', 'apartamento.id', '=', 'apartamentocuarto.apartamento_id')
        ->where('apartamento.id', $apartamento_id)->get();
    }

    public function show($id)
    {
        //
        $apartamentocuartodto = new ApartamentoCuartoDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $apartamentocuarto = ApartamentoCuarto::select('apartamentocuarto.id','precio', 'largo','ancho',
            'piso', 'nbanios', 'descripcion', 'path', 'apartamentocuarto.foto', 'persona.nombres', 
            'apartamento.id', 'apartamentocuarto.apartamentopisp_id as idapartamentocuarto', 
            'apartamentocuarto.persona_id as idpersona', 'apartamentocuarto.estado')
            ->join('persona', 'persona.id', '=', 'apartamentocuarto.persona_id')
            ->join('apartamento', 'apartamento.id', '=', 'apartamentocuarto.apartamento_id')
            ->where('apartamentocuarto.id','=',$id)->first();
        $apartamentocuartodto->setApartamentoCuarto($apartamentocuarto); // ingreso de la apartamento cuarto
        $persona = Persona::FindOrFail($apartamentocuarto->idpersona);
        $apartamentocuartodto->setPersona($persona); // ingreso del dueño del apartamento cuarto

        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('apartamentocuartofoto', 'apartamentocuartofoto.foto_id', '=', 'foto.id')
                ->where('apartamentocuartofoto.apartamentocuarto_id', $id)->get();
        $apartamentocuartodto->setFotos($fotos); // ingreso de las fotos de la apartamentocuarto

        return response()->json($apartamentocuartodto, 200);
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
        $apartamentocuarto = ApartamentoCuarto::FindOrFail($id);
        $input = [
            'apartamento_id' => $request->input('apartamento_id.id'),
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'piso' => $request->piso,
            'nbanios' => $request->nbanios,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'nmensajes' => $request->nmensajes,
            'estado' => $request->estado
        ];
        $apartamentocuarto->fill($input)->save();
        
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $apartamentocuartofoto = ApartamentoCuartoFoto::create([
                'partamentocuarto_id' => $partamentocuarto->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        // $partamentocuarto = ApartamentoCuarto::FindOrFail($id);
        // $input = $request->all();
        // $partamentocuarto->fill($input)->save();
        return response()->json($partamentocuarto, 200);
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
        $apartamentocuarto = ApartamentoCuarto::FindOrFail($id);
        ApartamentoCuarto::where('id', $id)->update(['estado'=>!$apartamentocuarto->estado]);
        return response()->json(['exito'=>'ApartamentoCuarto eliminado con id: '.$id], 200);
    }
}

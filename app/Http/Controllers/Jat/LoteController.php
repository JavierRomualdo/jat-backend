<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\LoteFoto;
use App\Models\Foto;
use App\Dto\LoteDto;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 
                'lote.ubicacion', 'lote.direccion', 'descripcion', 'path','lote.estado')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')->get();
        
        return response()->json($lotes);
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
        $lote = Lote::create([
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ]);
        $f = 'no esta';
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $lotefoto = LoteFoto::create([
                'lote_id' => $lote->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
            $f = $foto;
        }
        //for ($i = 0; $i < count($request->fotosList); $i++) {
        //    $f = $request->fotosList[$i];
            /*$foto = Foto::create([
                'foto' => $f->foto,
                'detalle' => $f->detalle,
                'estado' => $f->estado
            ]);*/
            /*$lotefoto = LoteFoto::create([
                'lote_id' => $lote->id,
                'foto_id'=> $foto->id
            ]);*/
        //}
        // foreach ($foto as $request->fotosList) {
            // $fotos = $foto;
            /*$foto = Foto::create([
                'foto' => $foto->foto,
                'detalle' => $foto->detalle,
                'estado' => $foto->estado
            ]);
            $lotefoto = LoteFoto::create([
                'lote_id' => $request->id,
                'foto_id'=> $foto->id
            ]);*/
        // }
        return response()->json($f, 200); // 201
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
        $lotedto = new LoteDto();
        $lote = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 
                'lote.ubicacion', 'lote.direccion', 'descripcion', 'path','lote.estado')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.id','=',$id)->first();
        $lotedto->setLote($lote);
        $persona = Persona::FindOrFail($lote->idpersona);
        $lotedto->setPersona($persona);
        $fotos = Foto::selec('foto.foto', 'foto.detalle', 'foto.estado')
                ->join('lotefoto', 'lotefoto.foto_id', '=', 'foto.id')
                ->where('lotefoto.lote_id', $lote->id);
        $lotedto->setFotos($fotos);
        //$persona = Persona::FindOrFail($id);
        return response()->json($lotedto, 200);
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
        $lote = Lote::FindOrFail($id);
        $input = [
            'persona_id' => $request->input('persona_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'ubicacion' => $request->ubicacion,
            'direccion' => $request->direccion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ];
        $lote->fill($input)->save();
        return response()->json($lote, 200);
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
        $lote = Lote::FindOrFail($id);
        Lote::where('id', $id)->update(['estado'=>!$lote->estado]);
        return response()->json(['exito'=>'Lote eliminado con id: '.$id], 200);
    }
}

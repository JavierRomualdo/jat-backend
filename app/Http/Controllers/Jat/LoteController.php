<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\LoteFoto;
use App\Models\Persona;
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
        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.estado')
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
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'estado' => $request->estado
        ]);
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $lotefoto = LoteFoto::create([
                'lote_id' => $lote->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
        return response()->json($lote, 200); // 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busqueda(Request $request)
    {
        # code...
        if (($request->direccion != null && $request->direccion != '') && 
            ($request->ubicacion != null && $request->ubicacion != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'lote.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->ubicacion != null && $request->ubicacion != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'lote.direccion','like','%'.($request->direccion).'%')->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.direccion','like','%'.($request->direccion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else if (($request->ubicacion != null && $request->ubicacion != '') &&
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.ubicacion','like','%'.($request->ubicacion).'%', 'and',
                'nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->ubicacion != null && $request->ubicacion != '')) {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.ubicacion','like','%'.($request->ubicacion).'%')->get();
            } else {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($lotes);
    }
    
    public function show($id)
    {
        //
        $lotedto = new LoteDto();
        $lote = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'lote.ubicacion', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->where('lote.id','=',$id)->first();
        $lotedto->setLote($lote);
        $persona = Persona::FindOrFail($lote->idpersona);
        $lotedto->setPersona($persona);
        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('lotefoto', 'lotefoto.foto_id', '=', 'foto.id')
                ->where('lotefoto.lote_id', $id)->get();
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
        foreach ($request->fotosList as $foto) {
            $foto = Foto::create($foto);
            $lotefoto = LoteFoto::create([
                'lote_id' => $lote->id,
                'foto_id'=> $foto->id,
                'estado' => true
            ]);
        }
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

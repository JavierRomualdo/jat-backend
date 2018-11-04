<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\LoteFoto;
use App\Models\Persona;
use App\Models\Foto;
use App\Models\Ubigeo;
use App\Models\LoteMensaje;
use App\Dto\LoteDto;
use App\Dto\UbigeoDetalleDto;
use App\Dto\UbigeoDto;
use DB;

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
        $lotes = Lote::select('lote.id', 'persona.nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path','lote.foto', 'lote.nmensajes', 'lote.estado')
                // DB::raw('(CASE WHEN (lotemensaje.estado=1) then (count(*)) else 0 end) as nmensajes')
                // DB::raw('count(*) as totalmensajes'))
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')->get();
        
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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'descripcion' => $request->descripcion,
            'path' => $request->path,
            'foto' => $request->foto,
            'tiposervicio' => $request->tiposervicio,
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
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['lote.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
            ($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['lote.direccion','like','%'.($request->direccion).'%']])->get();
        } else if (($request->direccion != null && $request->direccion != '') && 
        ($request->input('persona_id.nombres') != null && 
        $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where([['lote.direccion','like','%'.($request->direccion).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else if (($request->input('ubigeo_id.ubigeo') != null && 
            $request->input('ubigeo_id.ubigeo') != '') &&
            ($request->input('persona_id.nombres') != null && 
            $request->input('persona_id.nombres') != '')) {
            $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where([['ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%'],
                ['nombres','like','%'.($request->input('persona_id.nombres')).'%']])->get();
        } else {
            if (($request->direccion != null && $request->direccion != '')) {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where('lote.direccion','like','%'.($request->direccion).'%')->get();
            } else if (($request->input('ubigeo_id.ubigeo') != null && 
                $request->input('ubigeo_id.ubigeo') != '')) {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where('ubigeo','like','%'.($request->input('ubigeo_id.ubigeo')).'%')->get();
            } else {
                $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 
                'lote.persona_id as idpersona')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where('nombres','like','%'.($request->input('persona_id.nombres')).'%')->get();
            }
        }
        return response()->json($lotes);
    }

    public function mostrarlotes(Request $request)
    {
        # code...
        $lotes = "vacio";
        if ($request->input('departamento.codigo') != null) {
            if ($request->input('provincia.codigo') != null) {
                if ($request->input('distrito.codigo') != null) {
                    if ($request->input('rangoprecio') != null) {
                        // lotes con ese distrito con rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                        'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // lotes con ese distrito sin rango de precio
                        $codigo = $request->input('distrito.codigo');
                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                        'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','=',$codigo]])->get();
                    }
                } else { // distrito = null
                    if ($request->input('rangoprecio') != null) {
                        // distritos de la provincia con rango de precio
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                        'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                            ['precio','>=',$request->input('rangoprecio.preciominimo')],
                            ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                    } else {
                        // distritos de la provincia en general
                        $codigo = $request->input('provincia.codigo'); 
                        $subs = substr($codigo, 0, 4); // ejmp: 01

                        $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                        'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                        ->join('persona', 'persona.id', '=', 'lote.persona_id')
                        ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                        ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                    }
                }
            } else { // != provincia
                if ($request->input('rangoprecio') != null) {
                    // lotes del departamento con rango de precio
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                    'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%'],
                        ['precio','>=',$request->input('rangoprecio.preciominimo')],
                        ['precio','<=',$request->input('rangoprecio.preciomaximo')]])->get();
                } else {
                    // lotes del departamento en general
                    $codigo = $request->input('departamento.codigo'); 
                    $subs = substr($codigo, 0, 2); // ejmp: 01

                    $lotes = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo', 'lote.direccion', 
                    'descripcion', 'path','lote.foto', 'lote.estado', 'ubigeo.tipoubigeo_id','ubigeo.codigo')
                    ->join('persona', 'persona.id', '=', 'lote.persona_id')
                    ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                    ->where([['tipoubigeo_id','=',3],['codigo','like',$subs.'%']])->get();
                }
            }
        }
        return response()->json($lotes);
    }
    
    public function show($id)
    {
        //
        $lotedto = new LoteDto();
        $ubigeodetalledto = new UbigeoDetalleDto();
        $ubigeodto = new UbigeoDto();

        $lote = Lote::select('lote.id', 'nombres', 'precio', 'largo', 'ancho', 'ubigeo.ubigeo',
                'lote.direccion', 'descripcion', 'path', 'lote.foto','lote.estado', 'tiposervicio',
                'lote.persona_id as idpersona', 'lote.ubigeo_id as idubigeo')
                ->join('persona', 'persona.id', '=', 'lote.persona_id')
                ->join('ubigeo', 'ubigeo.id', '=', 'lote.ubigeo_id')
                ->where('lote.id','=',$id)->first();
        $lotedto->setLote($lote);
        $persona = Persona::FindOrFail($lote->idpersona);
        $lotedto->setPersona($persona);

        // ubigeo
        $ubigeo = Ubigeo::FindOrFail($lote->idubigeo); // siempre es el ubigeo distrito
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
        $lotedto->setUbigeo($ubigeodetalledto);// ingreso del ubigeo
        // end ubigeo

        $fotos = Foto::select('foto.id', 'foto.nombre', 'foto.foto', 'foto.detalle', 'foto.estado')
                ->join('lotefoto', 'lotefoto.foto_id', '=', 'foto.id')
                ->where('lotefoto.lote_id', $id)->get();
        $lotedto->setFotos($fotos);

        /*$nmensajes = LoteMensaje::where([['lote_id','=',$lote->id],['estado','=',true]])->count();
        $lotedto->setnMensajes($nmensajes);*/

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
            'ubigeo_id' => $request->input('ubigeo_id.id'),
            'precio' => $request->precio,
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'direccion' => $request->direccion,
            'path' => $request->path,
            'foto' => $request->foto,
            'tiposervicio' => $request->tiposervicio,
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

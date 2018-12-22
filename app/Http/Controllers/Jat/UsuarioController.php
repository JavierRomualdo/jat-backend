<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuarios = User::all();
        return response()->json($usuarios);
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
        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nombrefoto' => $request->nombrefoto,
            'foto' => $request->foto,
        ]);
        return response()->json($usuario, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busqueda(Request $request) {
        if (($request->name != null && $request->name != '') && 
        $request->email != null && $request->email != '') {
            $usuario = User::where('name','like','%'.($request->name).'%', 'and',
            'email','like','%'.($request->email).'%')->get();
        } else {
            if ($request->name != null && $request->name != '') {
                $usuario = User::where('name','like','%'.($request->name).'%')->get();
            } else {
                $usuario = User::where('email','like','%'.($request->email).'%')->get();
            }
        }
        return response()->json($usuario);
    }

    public function iniciarSesion(Request $request) {
        $usuario = '';
        if ($request->email != null && $request->password != '' && 
            $request->password != null && $request->password != '') {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                # code...
                $usuario = Auth::user();
            }
        }
        return response()->json($usuario);
    }

    public function show($id)
    {
        //
        $usuario = User::findOrFail($id);
        return response()->json($usuario, 200);
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
        $user= User::FindOrFail($id);
        // $input = $request->all();
        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nombrefoto' => $request->nombrefoto,
            'foto' => $request->foto,
        ];
        $user->fill($input)->save();
        return response()->json($user, 200);
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
        $user = User::FindOrFail($id);
        User::where('id', $id)->update(['estado'=>!$user->estado]);
        //$servicio->delete();
        return response()->json(['exito'=>'Servicio eliminado con id: '.$id], 200);
    }
}

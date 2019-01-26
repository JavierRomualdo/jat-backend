<?php

namespace App\Http\Controllers\Jat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\CasaMensaje;
use App\Models\CocheraMensaje;
use App\Models\HabitacionMensaje;
use App\Models\LocalMensaje;
use App\Models\LoteMensaje;
use App\Dto\UsuarioDto;
use App\Dto\NotificacionDto;
use App\EntityWeb\Entidades\Mensajes\NotificacionTO;
use Auth;
use App\User;
use DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport as UsersPdfExport;

use App\EntityWeb\Utils\RespuestaWebTO;

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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuarios = User::all();
            if ($usuarios!==null && !$usuarios->isEmpty()) {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($usuarios);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontraron usuarios');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function cambiarEstadoUsuario(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario = User::where('id', $request->input('id'))->update(['estado'=>$request->input('activar')]);
            if ($usuario!==null && $usuario!=='') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('El usuario: nombre '.$request->name.', se ha '.( 
                $request->input('activar') ? 'activado' : 'inactivado').' correctamente.');
                $respuesta->setExtraInfo($usuario);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('Error al modificar estado');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nombrefoto' => $request->nombrefoto,
                'foto' => $request->foto,
            ]);
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El usuario: nombre: '.$request->name.', se ha guardado correctamente.');
            $respuesta->setExtraInfo($usuario);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function iniciarSesion(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $credentials = $request->only('name', 'password');
            // $credential = [
            //     'email' => $request->email,
            //     'password' => $request->password
            // ];
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                $usuario = Auth::user();
                Auth::login($usuario);
                // Auth::guard('web')->login($usuario);
                // Para las notificaciones de las propiedades
                $nCasas = CasaMensaje::select(DB::raw('count(*) as cantidad'))->where('estado', true)->first();
                $nCocheras = CocheraMensaje::select(DB::raw('count(*) as cantidad'))->where('estado', true)->first();
                $nHabitaciones = HabitacionMensaje::select(DB::raw('count(*) as cantidad'))->where('estado', true)->first();
                $nLocales = LocalMensaje::select(DB::raw('count(*) as cantidad'))->where('estado', true)->first();
                $nLotes = LoteMensaje::select(DB::raw('count(*) as cantidad'))->where('estado', true)->first();
                //
                $usuarioDtO = new UsuarioDto();
                $notificacionDto = new NotificacionDto();
                $usuarioDtO->setUsuario($usuario);
                if ($nCasas->cantidad > 0) {
                    $notificacionTO = new NotificacionTO('Casa', 'Casas', $nCasas->cantidad, '/empresa/propiedades/casas');
                    // 'Tiene '.$nCasas->cantidad.($nCasas->cantidad == 1 ? ' mensaje' : ' mensajes').' en casas.'
                    $notificacionDto->setNotificacion($notificacionTO);
                    // $usuarioDtO->setNotificacion($notificacionTO);
                }
                if ($nCocheras->cantidad > 0) {
                    $notificacionTO = new NotificacionTO('Cochera', 'Cocheras', $nCocheras->cantidad, '/empresa/propiedades/cocheras');
                    // 'Tiene '.$nCocheras->cantidad.($nCocheras->cantidad == 1 ? ' mensaje' : ' mensajes').' en cocheras.'
                    $notificacionDto->setNotificacion($notificacionTO);
                    // $usuarioDtO->setNotificacion($notificacionTO);
                }
                if ($nHabitaciones->cantidad > 0) {
                    $notificacionTO = new NotificacionTO('Habitación', 'Habitaciones', $nHabitaciones->cantidad, '/empresa/propiedades/habitaciones');
                    // 'Tiene '.$nHabitaciones->cantidad.($nHabitaciones->cantidad == 1 ? ' mensaje' : ' mensajes').' en habitaciones.'
                    $notificacionDto->setNotificacion($notificacionTO);
                    // $usuarioDtO->setNotificacion($notificacionTO);
                }
                if ($nLocales->cantidad > 0) {
                    $notificacionTO = new NotificacionTO('Local', 'Locales', $nLocales->cantidad, '/empresa/propiedades/locales');
                    // 'Tiene '.$nLocales->cantidad.($nLocales->cantidad == 1 ? ' mensaje' : ' mensajes').' en locales.'
                    $notificacionDto->setNotificacion($notificacionTO);
                    // $usuarioDtO->setNotificacion($notificacionTO);
                }
                if ($nLotes->cantidad > 0) {
                    $notificacionTO = new NotificacionTO('Lote', 'Lotes', $nLotes->cantidad, '/empresa/propiedades/lotes');
                    // 'Tiene '.$nLotes->cantidad.($nLotes->cantidad == 1 ? ' mensaje' : ' mensajes').' en lotes.'
                    $notificacionDto->setNotificacion($notificacionTO);
                    // $usuarioDtO->setNotificacion($notificacionTO);
                }
                $notificacionDto->setCantidad($nCasas->cantidad + $nCocheras->cantidad + $nHabitaciones->cantidad + $nLocales->cantidad + $nLotes->cantidad);
                $usuarioDtO->setNotificacionDtO($notificacionDto);
                //
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setOperacionMensaje('Sesion iniciada');
                $respuesta->setExtraInfo($usuarioDtO);
                // return redirect()->intended('dashboard');
            } 
            else {
                $respuesta->setEstadoOperacion('ERROR');
                $respuesta->setExtraInfo($request->all());
                $respuesta->setOperacionMensaje('Usuario o clave incorrecta');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function cerrarSession()
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario = Auth::user();
            Auth::logout();
            
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('Sesion Cerrada');
            $respuesta->setExtraInfo($usuario);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function check()
    {
        # code...
        if (Auth::check()) {
            // The user is logged in...
            return response()->json('se ha inciado', 200);
        } else {
            return response()->json('noo se ha inciado', 200);
        }
    }

    public function show($id)
    {
        //
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario = User::findOrFail($id);
            if ($usuario !== null && $usuario !== '') {
                $respuesta->setEstadoOperacion('EXITO');
                $respuesta->setExtraInfo($usuario);
            } else {
                $respuesta->setEstadoOperacion('ADVERTENCIA');
                $respuesta->setOperacionMensaje('No se encontró usuario');
            }
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        
        return response()->json($respuesta, 200);
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario= User::FindOrFail($id);
            // $input = $request->all();
            $input = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nombrefoto' => $request->nombrefoto,
                'foto' => $request->foto,
            ];
            $usuario->fill($input)->save();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El usuario: nombre: '.$request->name.', se ha modificado correctamente.');
            $respuesta->setExtraInfo($usuario);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200); // 201
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
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            $usuario = User::FindOrFail($id);
            $usuario->delete();
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setOperacionMensaje('El usuario: nombre: '.$usuario->name.', se ha eliminado correctamente.');
            $respuesta->setExtraInfo($usuario);
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    //exportar archivos
    // public function exportarExcel(Request $request) // Request $request
    // {
    //     # code...
    //     // return Excel::download(new UsersExport, 'users.xlsx');
    //     // return (new UsersExport)->download('users.xlsx');
    //     // return (new UsersExport)->store('users.xlsx', 'public');
    //     return (new UsersExport)->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);

    //     // Excel::import(new UsersExport,request()->file('file'))
    //     // return 'Listo';
    // }

    public function exportarExcel()
    {
        # code...
        $data = [
            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ];
        // Excel::store(new UsersExport($data), 'invoices.xlsx');
        // return null;
        // return Excel::download(new UsersPdfExport($data), 'casas.pdf');

        $pdf = PDF::loadView('exports.casa', [
            'users' => $data
        ]);
        return $pdf->download('casas.pdf');
    }
}

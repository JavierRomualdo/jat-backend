<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Mail;
use App\EntityWeb\Utils\RespuestaWebTO;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    public function enviarMensajeCliente(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            Mail::send('mail.mensajecliente', $request->all(), function($msj) use ($request) {
                $msj->subject('Mensaje del cliente');
                // $msj->from($request->input('email'), 'Correo Cliente');
                $msj->to($request->input('emailReceptor')); // email receptor
            });
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($request->input('emailReceptor'));
            $respuesta->setOperacionMensaje('Email enviado');
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function enviarMensajeContacto(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();
            Mail::send('mail.mensajecontacto', $request->all(), function($msj) use ($request) {
                $msj->subject('Mensaje del contacto');
                $msj->to($request->input('emailReceptor')); // email receptor
            });
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($request->input('emailReceptor'));
            $respuesta->setOperacionMensaje('Mensaje enviado correctamente.');
        } catch (Exception  $e) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($e->getMessage());
        } catch (QueryException $qe) {
            $respuesta->setEstadoOperacion('ERROR');
            $respuesta->setOperacionMensaje($qe->getMessage());
        }
        return response()->json($respuesta, 200);
    }

    public function reenviarMensajeCliente(Request $request)
    {
        # code...
        try {
            //code...
            $respuesta = new RespuestaWebTO();

            Mail::send('mail.mailcliente', $request->all(), function($msj) use ($request) {
                $msj->subject('Mensaje de JAT');
                // $msj->from('javierromualdo2014@gmail.com', 'Correo JAT');
                $msj->to($request->input('emailReceptor')); // email receptor
            });
            $respuesta->setEstadoOperacion('EXITO');
            $respuesta->setExtraInfo($request->input('emailReceptor'));
            $respuesta->setOperacionMensaje('Correo enviado.');
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
    }
}

<?php

namespace App\Http\Controllers\Jat\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
// EXCEL
use App\Exports\Excel\Propiedades\CasasExport;
use App\Exports\Excel\Propiedades\CocherasExport;
use App\Exports\Excel\Propiedades\HabitacionesExport;
use App\Exports\Excel\Propiedades\LocalesExport;
use App\Exports\Excel\Propiedades\LotesExport;
use App\Exports\Excel\Personas\PersonasExport;
use App\Exports\Excel\Servicios\ServiciosExport;
use App\Exports\Excel\HabilitacionesUrbanas\HabilitacionesUrbanasExport;
use App\Exports\Excel\Alquileres\AlquileresExport;
use App\Exports\Excel\Ventas\VentasExport;
// PDF
use App\Exports\Pdf\Propiedades\CasasExport as CasasPdfExport ;
use App\Exports\Pdf\Propiedades\CocherasExport as CocherasPdfExport;
use App\Exports\Pdf\Propiedades\HabitacionesExport as HabitacionesPdfExport;
use App\Exports\Pdf\Propiedades\LocalesExport as LocalesPdfExport;
use App\Exports\Pdf\Propiedades\LotesExport as LotesPdfExport;
use App\Exports\Pdf\Alquileres\AlquileresExport as AlquileresPdfExport;
use App\Exports\Pdf\Ventas\VentasExport as VentasPdfExport;
use PDF;
use App\Http\Controllers\Jat\CasaController;
use App\Http\Controllers\Jat\Cochera\CocheraController;
use App\Http\Controllers\Jat\HabitacionController;
use App\Http\Controllers\Jat\LocalController;
use App\Http\Controllers\Jat\LoteController;
use App\Http\Controllers\Jat\PersonaController;
use App\Http\Controllers\Jat\ServiciosController;
use App\Http\Controllers\Jat\HabilitacionUrbanaController;
use App\Http\Controllers\Jat\EmpresaController;
use Google\Cloud\Storage\StorageClient;

class ReportesController extends Controller
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

    // EXPORTAR EN EXCEL
    public function exportarExcelCasas(Request $request)
    {
        # code...
        return Excel::download(new CasasExport($request), 'casas.xlsx');
    }

    public function exportarExcelCocheras(Request $request)
    {
        # code...
        return Excel::download(new CocherasExport($request), 'cocheras.xlsx');
    }

    public function exportarExcelHabitaciones(Request $request)
    {
        # code...
        return Excel::download(new HabitacionesExport($request), 'habitaciones.xlsx');
    }

    public function exportarExcelLocales(Request $request)
    {
        # code...
        return Excel::download(new LocalesExport($request), 'locales.xlsx');
    }

    public function exportarExcelLotes(Request $request)
    {
        # code...
        return Excel::download(new LotesExport($request), 'lotes.xlsx');
    }

    public function exportarExcelPersonas(Request $request)
    {
        # code...
        return Excel::download(new PersonasExport($request), 'personas.xlsx');
    }

    public function exportarExcelServicios(Request $request)
    {
        # code...
        return Excel::download(new ServiciosExport($request), 'servicios.xlsx');
    }

    public function exportarExcelHabilitacionesUrbanas(Request $request)
    {
        # code...
        return Excel::download(new HabilitacionesUrbanasExport($request), 'habilitacionesurbanas.xlsx');
    }

    public function exportarExcelAlquileres(Request $request)
    {
        # code...
        return Excel::download(new AlquileresExport($request), 'alquileres.xlsx');
    }

    public function exportarExcelVentas(Request $request)
    {
        # code...
        return Excel::download(new VentasExport($request), 'ventas.xlsx');
    }

    // EXPORTAR EN PDF
    public function exportarPdfCasas(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.casas', [
            'casas' => $request->data,
            'estadocontrato' => $request->estadocontrato,
            'activos' => $request->activos,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('casas.pdf');
        // return (new CasasPdfExport($request))->download('casas.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        // return Excel::download(new CasasPdfExport($request), 'casas.pdf');
    }

    public function exportarPdfCasaDetalle(Request $request)
    {
        # code...
        $casaController = new CasaController();
        $respuestaPropiedad = $casaController->show($request->input('casa.id'));
        $casa = $respuestaPropiedad->original->extraInfo;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('exports.pdf.propiedades.casadetalle', [
            'casa' => $casa,
            'propietario' => $casa->persona_id,
            'ubigeo' => $casa->ubigeo,
            'servicios' => $casa->serviciosList,
            'imagenes' => $casa->fotosList,
            'fechaActual'=> $request->fechaActual
        ]);
        // $contxt = stream_context_create([ 
        //     'ssl' => [ 
        //         'verify_peer' => FALSE, 
        //         'verify_peer_name' => FALSE,
        //         'allow_self_signed'=> TRUE
        //     ] 
        // ]);
        // $pdf->setHttpContext($contxt);
        $pdf->setPaper('a4');

        return $pdf->stream('casadetalle.pdf');

        $storage = new StorageClient();

        // $bucket = $storage->bucket('my-bucket');
        // $object = $bucket->object('my-object');
        // $stream = $object->downloadAsStream();
        // echo $stream->getContents();
    }

    public function exportarPdfCocheras(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.cocheras', [
            'cocheras' => $request->data,
            'estadocontrato' => $request->estadocontrato,
            'activos' => $request->activos,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('cocheras.pdf');
        // return Excel::download(new CocherasPdfExport($request), 'cocheras.pdf');
    }

    public function exportarPdfCocheraDetalle(Request $request)
    {
        # code...
        $cocheraController = new CocheraController();
        $respuestaPropiedad = $cocheraController->show($request->input('cochera.id'));
        $cochera = $respuestaPropiedad->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.propiedades.cocheradetalle', [
            'cochera' => $cochera,
            'propietario' => $cochera->persona_id,
            'ubigeo' => $cochera->ubigeo,
            'servicios' => $cochera->serviciosList,
            'imagenes' => $cochera->fotosList,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4');

        return $pdf->stream('cocheradetalle.pdf');
    }

    public function exportarPdfHabitaciones(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.habitaciones', [
            'habitaciones' => $request->data,
            'estadocontrato' => $request->estadocontrato,
            'activos' => $request->activos,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('habitaciones.pdf');
        // return Excel::download(new HabitacionesPdfExport($request), 'habitaciones.pdf');
    }

    public function exportarPdfHabitacionDetalle(Request $request)
    {
        # code...
        $habitacionController = new HabitacionController();
        $respuestaPropiedad = $habitacionController->show($request->input('habitacion.id'));
        $habitacion = $respuestaPropiedad->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.propiedades.habitaciondetalle', [
            'habitacion' => $habitacion,
            'propietario' => $habitacion->persona_id,
            'ubigeo' => $habitacion->ubigeo,
            'servicios' => $habitacion->serviciosList,
            'imagenes' => $habitacion->fotosList,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4');

        return $pdf->stream('habitaciondetalle.pdf');
    }

    public function exportarPdfLocales(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.locales', [
            'locales' => $request->data,
            'estadocontrato' => $request->estadocontrato,
            'activos' => $request->activos,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('locales.pdf');
        // return Excel::download(new LocalesPdfExport($request), 'locales.pdf');
    }

    public function exportarPdfLocalDetalle(Request $request)
    {
        # code...
        $localController = new LocalController();
        $respuestaPropiedad = $localController->show($request->input('local.id'));
        $local = $respuestaPropiedad->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.propiedades.localdetalle', [
            'local' => $local,
            'propietario' => $local->persona_id,
            'ubigeo' => $local->ubigeo,
            'servicios' => $local->serviciosList,
            'imagenes' => $local->fotosList,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4');

        return $pdf->stream('localdetalle.pdf');
    }

    public function exportarPdfLotes(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.lotes', [
            'lotes' => $request->data,
            'estadocontrato' => $request->estadocontrato,
            'activos' => $request->activos,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('lotes.pdf');
        // return Excel::download(new LotesPdfExport($request), 'lotes.pdf');
    }

    public function exportarPdfLoteDetalle(Request $request)
    {
        # code...
        $loteController = new LoteController();
        $respuestaPropiedad = $loteController->show($request->input('lote.id'));
        $lote = $respuestaPropiedad->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.propiedades.lotedetalle', [
            'lote' => $lote,
            'propietario' => $lote->persona_id,
            'ubigeo' => $lote->ubigeo,
            'imagenes' => $lote->fotosList,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4');

        return $pdf->stream('lotedetalle.pdf');
    }

    public function exportarPdfPersonas(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.personas.personas', [
            'personas' => $request->data,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('personas.pdf');
        // return Excel::download(new LotesPdfExport($request), 'lotes.pdf');
    }

    public function exportarPdfPersonaDetalle(Request $request)
    {
        # code...
        $personaController = new PersonaController();
        $respuestaPersona = $personaController->show($request->input('persona.id'));
        $persona = $respuestaPersona->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.personas.personadetalle', [
            'persona' => $persona,
            'rol' => $persona->rol_id,
            'ubigeo' => $persona->ubigeo,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');

        return $pdf->download('personadetalle.pdf');
    }

    public function exportarPdfServicios(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.servicios.servicios', [
            'servicios' => $request->data,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('servicios.pdf');
        // return Excel::download(new LotesPdfExport($request), 'lotes.pdf');
    }

    public function exportarPdfServicioDetalle(Request $request)
    {
        # code...
        $servicioController = new ServiciosController();
        $respuestaServicio = $servicioController->show($request->input('servicio.id'));
        $servicio = $respuestaServicio->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.servicios.serviciodetalle', [
            'servicio' => $servicio,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4');

        return $pdf->stream('serviciodetalle.pdf');
    }

    public function exportarPdfHabilitacionesUrbanas(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.habilitacionesurbanas.habilitacionesurbanas', [
            'habilitacionesurbanas' => $request->data,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('habilitacionesurbanas.pdf');
        // return Excel::download(new LotesPdfExport($request), 'lotes.pdf');
    }

    public function exportarPdfHabilitacionUrbanaDetalle(Request $request)
    {
        # code...
        $habilitacionurbanaController = new HabilitacionUrbanaController();
        $respuestaHabilitacionurbana = $habilitacionurbanaController->show($request->input('habilitacionurbana.id'));
        $habilitacionurbana = $respuestaHabilitacionurbana->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.habilitacionesurbanas.habilitacionurbanadetalle', [
            'habilitacionurbana' => $habilitacionurbana,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');

        return $pdf->download('habilitacionurbanadetalle.pdf');
    }

    public function exportarPdfEmpresa(Request $request)
    {
        # code...
        $empresaController = new EmpresaController();
        $respuestaEmpresa = $empresaController->index();
        $empresa = $respuestaEmpresa->original->extraInfo;
        $pdf = PDF::loadView('exports.pdf.empresa.empresa', [
            'empresa' => $empresa,
            'ubigeo' => $empresa->ubigeo,
            'fechaActual'=> $request->fechaActual
        ]);
        $pdf->setPaper('a4','landscape');
        return $pdf->download('empresa.pdf');
        // return Excel::download(new LotesPdfExport($request), 'lotes.pdf');
    }

    public function exportarPdfAlquileres(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.alquiler.alquileres', [
            'fechaActual'=> $request->fechaActual,
            'propiedad' => $request->propiedad,
            'alquileres' => $request->data
        ]);
        $pdf->setPaper('a4','landscape');

        return $pdf->stream('alquileres.pdf');
    }

    public function exportarPdfAlquilerDetalle(Request $request)
    {
        # code...
        switch($request->input('propiedad')) {
            case 'Casa':
                $casaController = new CasaController();
                $respuestaPropiedad = $casaController->show($request->input('alquiler.propiedad_id'));
                $casa = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.alquiler.alquilerdetalle', [
                    'alquiler' => $request->alquiler,
                    'tipodoc' => 'casadetalle',
                    'casa' => $casa,
                    'propietario' => $casa->persona_id,
                    'ubigeo' => $casa->ubigeo,
                    'servicios' => $casa->serviciosList,
                    'imagenes' => $casa->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
            case 'Local':
                $localController = new LocalController();
                $respuestaPropiedad = $localController->show($request->input('alquiler.propiedad_id'));
                $local = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.valquilerenta.alquilerdetalle', [
                    'alquiler' => $request->alquiler,
                    'tipodoc' => 'localdetalle',
                    'local' => $local,
                    'propietario' => $local->persona_id,
                    'ubigeo' => $local->ubigeo,
                    'servicios' => $local->serviciosList,
                    'imagenes' => $local->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
            case 'Lote':
                $loteController = new LoteController();
                $respuestaPropiedad = $loteController->show($request->input('alquiler.propiedad_id'));
                $lote = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.alquiler.alquilerdetalle', [
                    'alquiler' => $request->alquiler,
                    'tipodoc' => 'lotedetalle',
                    'lote' => $lote,
                    'propietario' => $lote->persona_id,
                    'ubigeo' => $lote->ubigeo,
                    'imagenes' => $lote->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
        }
        $pdf->setPaper('a4');
        return $pdf->stream('ventadetalle.pdf');
    }

    public function exportarPdfVentas(Request $request)
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.venta.ventas', [
            'fechaActual'=> $request->fechaActual,
            'propiedad' => $request->propiedad,
            'ventas' => $request->data
        ]);
        $pdf->setPaper('a4','landscape');

        return $pdf->stream('ventas.pdf');
    }

    public function exportarPdfVentaDetalle(Request $request)
    {
        # code...
        switch($request->input('propiedad')) {
            case 'Casa':
                $casaController = new CasaController();
                $respuestaPropiedad = $casaController->show($request->input('venta.propiedad_id'));
                $casa = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.venta.ventadetalle', [
                    'venta' => $request->venta,
                    'tipodoc' => 'casadetalle',
                    'casa' => $casa,
                    'propietario' => $casa->persona_id,
                    'ubigeo' => $casa->ubigeo,
                    'servicios' => $casa->serviciosList,
                    'imagenes' => $casa->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
            case 'Local':
                $localController = new LocalController();
                $respuestaPropiedad = $localController->show($request->input('venta.propiedad_id'));
                $local = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.venta.ventadetalle', [
                    'venta' => $request->venta,
                    'tipodoc' => 'localdetalle',
                    'local' => $local,
                    'propietario' => $local->persona_id,
                    'ubigeo' => $local->ubigeo,
                    'servicios' => $local->serviciosList,
                    'imagenes' => $local->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
            case 'Lote':
                $loteController = new LoteController();
                $respuestaPropiedad = $loteController->show($request->input('venta.propiedad_id'));
                $lote = $respuestaPropiedad->original->extraInfo;
                $pdf = PDF::loadView('exports.pdf.venta.ventadetalle', [
                    'venta' => $request->venta,
                    'tipodoc' => 'lotedetalle',
                    'lote' => $lote,
                    'propietario' => $lote->persona_id,
                    'ubigeo' => $lote->ubigeo,
                    'imagenes' => $lote->fotosList,
                    'fechaActual'=> $request->fechaActual
                ]);
                break;
        }
        $pdf->setPaper('a4');
        return $pdf->stream('ventadetalle.pdf');
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

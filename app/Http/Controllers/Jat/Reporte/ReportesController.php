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

    public function exportarPdfCasaDetalle()
    {
        # code...
        $pdf = PDF::loadView('exports.pdf.propiedades.casadetalle');
        $pdf->setPaper('a4','landscape');
        return $pdf->stream('casadetalle.pdf');
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

    public function exportarPdfAlquileres(Request $request)
    {
        # code...
        return Excel::download(new AlquileresPdfExport($request), 'alquileres.pdf');
    }

    public function exportarPdfVentas(Request $request)
    {
        # code...
        return Excel::download(new VentasPdfExport($request), 'ventas.pdf');
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

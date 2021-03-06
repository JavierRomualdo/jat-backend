<?php

namespace App\Exports\Excel\Ventas;

use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VentasExport implements FromView
{
    private $data;
    private $propiedad;
    private $fechaActual;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($request)
    {
        # code...
        $this->data = $request->data;
        $this->propiedad = $request->propiedad;
        $this->fechaActual = $request->fechaActual;
    }

    public function view(): View
    {
        # code...
        return view('exports.excel.venta.ventas', [
            'ventas' => $this->data,
            'propiedad' => $this->propiedad,
            'fechaActual'=> $this->fechaActual
        ]);
    }
}

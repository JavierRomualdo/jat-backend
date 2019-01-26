<?php

namespace App\Exports\Pdf\Propiedades;

use App\Cochera;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CocherasExport implements FromView
{
    private $data;
    private $fechaActual;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($request)
    {
        # code...
        $this->data = $request->data;
        $this->fechaActual = $request->fechaActual;
    }

    public function view(): View
    {
        # code...
        return view('exports.pdf.propiedades.cocheras', [
            'cocheras' => $this->data,
            'fechaActual'=> $this->fechaActual
        ]);
    }
}

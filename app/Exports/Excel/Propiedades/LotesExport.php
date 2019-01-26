<?php

namespace App\Exports\Excel\Propiedades;

use App\Lote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LotesExport implements FromView
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
        return view('exports.excel.propiedades.lotes', [
            'lotes' => $this->data,
            'fechaActual'=> $this->fechaActual
        ]);
    }
}

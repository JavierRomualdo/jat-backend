@extends('layouts.pdf')
@section('content')
<h6 class="page-header">
    <strong>Propiedad:</strong> Lotes | 
    @if ($estadocontrato !== null)
        @if ($estadocontrato == 'V' || $estadocontrato == 'A') 
            <strong>Contrato: </strong>
            @if ($estadocontrato == 'V')
                Venta
            @else
                Alquiler
            @endif | 
        @else
            <strong>Pre - Contrato: </strong>
            @if ($estadocontrato == 'L')
                Libre
            @else
                Reservado
            @endif | 
        @endif
    @else
        <strong>Listado: </strong> 
        @if ($activos) 
            Activos
        @else
            Todos
        @endif
    @endif
</h6>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered" style="font-size: 11px">
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Contrato</th>
            <th>Estado contrato</th>
            <th>Propietario</th>
            <th>Hab. urbana</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Área</th>
            <th>Precio adquisición</th>
            <th>Precio contrato</th>
            <th>Ganancia</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lotes as $index=>$lote)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $lote['codigo'] }}</td>
                <td>
                    @if ($lote['contrato'] == "V")
                        Venta
                    @elseif ($lote['contrato'] == "A")
                        Alquiler
                    @endif
                </td>
                <td>
                    @if ($lote['estadocontrato'] == "L")
                        Libre
                    @elseif ($lote['estadocontrato'] == "V")
                        Vendido
                    @elseif ($lote['estadocontrato'] == "A")
                        Alquilado
                    @endif
                </td>
                <td>{{ $lote['propietario'] }}</td>
                <td>{{ $lote['siglas'] }}</td>
                <td>{{ $lote['ubicacion'] }}</td>
                <td>{{ $lote['direccion'] }}</td>
                <td>{{ $lote['ancho'] }}x{{ $lote['largo'] }}</td>
                <td>S/ {{ $lote['precioadquisicion'] }}</td>
                <td>S/ {{ $lote['preciocontrato'] }}</td>
                <td>S/ {{ $lote['ganancia'] }}</td>
                <td>
                    @if ($lote['estado'])
                        Si
                    @else
                        No
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
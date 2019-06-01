@extends('layouts.pdf')
@section('content')
<h6 class="page-header">
    <strong>Propiedad:</strong> Cocheras | 
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
        @endif | 
    @endif
</h6>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered" style="font-size: 12px">
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
        @foreach($cocheras as $index=>$cochera)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $cochera['codigo'] }}</td>
                <td>
                    @if ($cochera['contrato'] == "V")
                        Venta
                    @elseif ($cochera['contrato'] == "A")
                        Alquiler
                    @endif
                </td>
                <td>
                    @if ($cochera['estadocontrato'] == "L")
                        Libre
                    @elseif ($cochera['estadocontrato'] == "V")
                        Vendido
                    @elseif ($cochera['estadocontrato'] == "A")
                        Alquilado
                    @endif
                </td>
                <td>{{ $cochera['propietario'] }}</td>
                <td>{{ $cochera['siglas'] }}</td>
                <td>{{ $cochera['ubicacion'] }}</td>
                <td>{{ $cochera['direccion'] }}</td>
                <td>{{ $cochera['ancho'] }}x{{ $cochera['largo'] }}</td>
                <td>S/ {{ $cochera['precioadquisicion'] }}</td>
                <td>S/ {{ $cochera['preciocontrato'] }}</td>
                <td>S/ {{ $cochera['ganancia'] }}</td>
                <td>
                    @if ($cochera['estado'])
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
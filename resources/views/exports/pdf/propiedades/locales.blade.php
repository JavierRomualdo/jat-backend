@extends('layouts.pdf')
@section('content')
<h6 class="page-header">
    <strong>Propiedad:</strong> Locales | 
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
            <th>¿Baño?</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($locales as $index=>$local)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $local['codigo'] }}</td>
                <td>
                    @if ($local['contrato'] == "V")
                        Venta
                    @elseif ($local['contrato'] == "A")
                        Alquiler
                    @endif
                </td>
                <td>
                    @if ($local['estadocontrato'] == "L")
                        Libre
                    @elseif ($local['estadocontrato'] == "V")
                        Vendido
                    @elseif ($local['estadocontrato'] == "A")
                        Alquilado
                    @endif
                </td>
                <td>{{ $local['propietario'] }}</td>
                <td>{{ $local['siglas'] }}</td>
                <td>{{ $local['ubicacion'] }}</td>
                <td>{{ $local['direccion'] }}</td>
                <td>{{ $local['ancho'] }}x{{ $local['largo'] }}</td>
                <td>S/ {{ $local['precioadquisicion'] }}</td>
                <td>S/ {{ $local['preciocontrato'] }}</td>
                <td>S/ {{ $local['ganancia'] }}</td>
                <td>
                    @if ($local['tbanio'])
                        Si
                    @else
                        No
                    @endif
                </td>
                <td>
                    @if ($local['estado'])
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
@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Propiedad:</strong> Habitaciones | 
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
    <strong>Fecha:</strong> {{$fechaActual}}
</h5>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Contrato</th>
            <th>Estado Contrato</th>
            <th>Propietario</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Área</th>
            <th>Precio adquisición</th>
            <th>Precio contrato</th>
            <th>Ganancia</th>
            <th>Camas</th>
            <th>¿Baño?</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($habitaciones as $index=>$habitacion)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $habitacion['codigo'] }}</td>
                <td>
                    @if ($habitacion['contrato'] == "V")
                        Venta
                    @elseif ($habitacion['contrato'] == "A")
                        Alquiler
                    @endif
                </td>
                <td>
                    @if ($habitacion['estadocontrato'] == "L")
                        Libre
                    @elseif ($habitacion['estadocontrato'] == "V")
                        Vendido
                    @elseif ($habitacion['estadocontrato'] == "A")
                        Alquilado
                    @endif
                </td>
                <td>{{ $habitacion['propietario'] }}</td>
                <td>{{ $habitacion['ubicacion'] }}</td>
                <td>{{ $habitacion['direccion'] }}</td>
                <td>{{ $habitacion['ancho'] }} x {{ $habitacion['largo'] }}</td>
                <td>{{ $habitacion['precioadquisicion'] }}</td>
                <td>{{ $habitacion['preciocontrato'] }}</td>
                <td>{{ $habitacion['ganancia'] }}</td>
                <td>{{ $habitacion['ncamas'] }}</td>
                <td>
                    @if ($habitacion['tbanio'])
                        Si
                    @else
                        No
                    @endif
                </td>
                <td>
                    @if ($habitacion['estado'])
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
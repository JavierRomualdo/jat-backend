@extends('layouts.pdf')
@section('content')

<h5 class="page-header">
    <strong>Propiedad:</strong> Casas | 
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
            <th>Pisos</th>
            <th>Cuartos</th>
            <th>Baños</th>
            <!-- <th>¿Jardín?</th>
            <th>¿Cochera?</th> -->
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($casas as $index=>$casa)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $casa['codigo'] }}</td>
                <td>
                    @if ($casa['contrato'] == "V")
                        Venta
                    @elseif ($casa['contrato'] == "A")
                        Alquiler
                    @endif
                </td>
                <td>
                    @if ($casa['estadocontrato'] == "L")
                        Libre
                    @elseif ($casa['estadocontrato'] == "V")
                        Vendido
                    @elseif ($casa['estadocontrato'] == "A")
                        Alquilado
                    @endif
                </td>
                <td>{{ $casa['propietario'] }}</td>
                <td>{{ $casa['ubicacion'] }}</td>
                <td>{{ $casa['direccion'] }}</td>
                <td>{{ $casa['ancho'] }} x {{ $casa['largo'] }}</td>
                <td>S/ {{ $casa['precioadquisicion'] }}</td>
                <td>S/ {{ $casa['preciocontrato'] }}</td>
                <td>S/ {{ $casa['ganancia'] }}</td>
                <td>{{ $casa['npisos'] }}</td>
                <td>{{ $casa['ncuartos'] }}</td>
                <td>{{ $casa['nbanios'] }}</td>
                <!-- <td>
                    @if ($casa['tjardin'])
                        Si
                    @else
                        No
                    @endif
                </td>
                <td>
                    @if ($casa['tcochera'])
                        Si
                    @else
                        No
                    @endif
                </td> -->
                <td>
                    @if ($casa['estado'])
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
@extends('layouts.excel')
@section('content')
<h3>Listado de habitaciones</h3>
<h4>Fecha: {{$fechaActual}}</h4>
    <table>
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
                <td>{{ $habitacion['siglas'] }}</td>
                <td>{{ $habitacion['ubicacion'] }}</td>
                <td>{{ $habitacion['direccion'] }}</td>
                <td>{{ $habitacion['ancho'] }}x{{ $habitacion['largo'] }} m2</td>
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
@endsection
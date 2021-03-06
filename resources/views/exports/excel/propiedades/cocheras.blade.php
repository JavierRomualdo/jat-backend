@extends('layouts.excel')
@section('content')
<h3>Listado de cocheras</h3>
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
                <td>{{ $cochera['ancho'] }}x{{ $cochera['largo'] }} m2</td>
                <td>{{ $cochera['precioadquisicion'] }}</td>
                <td>{{ $cochera['preciocontrato'] }}</td>
                <td>{{ $cochera['ganancia'] }}</td>
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
@endsection
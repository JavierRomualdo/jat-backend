@extends('layouts.excel')
@section('content')
<h3>Listado de casas</h3>
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
            <th>Pisos</th>
            <th>Cuartos</th>
            <th>Baños</th>
            <th>¿Jardín?</th>
            <th>¿Cochera?</th>
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
                <td>{{ $casa['siglas'] }}</td>
                <td>{{ $casa['ubicacion'] }}</td>
                <td>{{ $casa['direccion'] }}</td>
                <td>{{ $casa['ancho'] }}x{{ $casa['largo'] }} m2</td>
                <td>{{ $casa['precioadquisicion'] }}</td>
                <td>{{ $casa['preciocontrato'] }}</td>
                <td>{{ $casa['ganancia'] }}</td>
                <td>{{ $casa['npisos'] }}</td>
                <td>{{ $casa['ncuartos'] }}</td>
                <td>{{ $casa['nbanios'] }}</td>
                <td>
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
                </td>
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
@endsection
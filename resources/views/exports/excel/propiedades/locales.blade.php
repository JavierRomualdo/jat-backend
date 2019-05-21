@extends('layouts.excel')
@section('content')
<h3>Listado de locales</h3>
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
                <td>{{ $casa['siglas'] }}</td>
                <td>{{ $local['ubicacion'] }}</td>
                <td>{{ $local['direccion'] }}</td>
                <td>{{ $local['ancho'] }}x{{ $local['largo'] }} m2</td>
                <td>{{ $local['precioadquisicion'] }}</td>
                <td>{{ $local['preciocontrato'] }}</td>
                <td>{{ $local['ganancia'] }}</td>
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
@endsection
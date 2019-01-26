@extends('layouts.excel')
@section('content')
<h4>Fecha: {{$fechaActual}}</h4>
<h3>Listado de lotes</h3>
    <table>
        <thead>
        <tr>
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
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lotes as $lote)
            <tr>
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
                <td>{{ $lote['ubicacion'] }}</td>
                <td>{{ $lote['direccion'] }}</td>
                <td>{{ $lote['ancho'] }} x {{ $lote['largo'] }} m2</td>
                <td>{{ $lote['precioadquisicion'] }}</td>
                <td>{{ $lote['preciocontrato'] }}</td>
                <td>{{ $lote['ganancia'] }}</td>
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
@endsection
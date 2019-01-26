@extends('layouts.pdf')
@section('content')
<h4>Fecha: {{$fechaActual}}</h4>
<h4>Propiedad: {{$propiedad}}</h4>
<h3>Listado de ventas</h3>
    <table>
        <thead>
        <tr>
            <th>Código</th>
            <th>Propietario</th>
            <th>Cliente</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Precio contrato</th>
            <th>Fecha venta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta['codigo'] }}</td>
                <td>{{ $venta['propietario'] }}</td>
                <td>{{ $venta['cliente'] }}</td>
                <td>{{ $venta['ubicacion'] }}</td>
                <td>{{ $venta['direccion'] }}</td>
                <td>{{ $venta['preciocontrato'] }}</td>
                <td>{{ $venta['fechaVenta'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
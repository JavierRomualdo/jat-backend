@extends('layouts.excel')
@section('content')
<h3>Listado de ventas</h3>
<h4>Fecha: {{$fechaActual}}</h4>
<h4>Propiedad: {{$propiedad}}</h4>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Propietario</th>
            <th>Cliente</th>
            <th>Hab. Urbana</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Precio contrato</th>
            <th>Fecha venta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ventas as $index=>$venta)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $venta['propiedad_codigo'] }}</td>
                <td>{{ $venta['propietario'] }}</td>
                <td>{{ $venta['cliente'] }}</td>
                <td>{{ $venta['siglas'] }}</td>
                <td>{{ $venta['ubicacion'] }}</td>
                <td>{{ $venta['direccion'] }}</td>
                <td>{{ $venta['preciocontrato'] }}</td>
                <td>{{ $venta['fechaVenta'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
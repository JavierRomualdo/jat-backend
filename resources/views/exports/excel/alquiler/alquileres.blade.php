@extends('layouts.excel')
@section('content')
<h3>Listado de alquileres</h3>
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
            <th>Fecha desde</th>
            <th>Fecha hasta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($alquileres as $index=>$alquiler)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $alquiler['propiedad_codigo'] }}</td>
                <td>{{ $alquiler['propietario'] }}</td>
                <td>{{ $alquiler['cliente'] }}</td>
                <td>{{ $venta['siglas'] }}</td>
                <td>{{ $alquiler['ubicacion'] }}</td>
                <td>{{ $alquiler['direccion'] }}</td>
                <td>{{ $alquiler['preciocontrato'] }}</td>
                <td>{{ $alquiler['fechadesde'] }}</td>
                <td>{{ $alquiler['fechahasta'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
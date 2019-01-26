@extends('layouts.pdf')
@section('content')
<h4>Fecha: {{$fechaActual}}</h4>
<h4>Propiedad: {{$propiedad}}</h4>
<h3>Listado de alquileres</h3>
    <table>
        <thead>
        <tr>
            <th>Código</th>
            <th>Propietario</th>
            <th>Cliente</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Precio contrato</th>
            <th>Fecha desde</th>
            <th>Fecha hasta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($alquileres as $alquiler)
            <tr>
                <td>{{ $alquiler['codigo'] }}</td>
                <td>{{ $alquiler['propietario'] }}</td>
                <td>{{ $alquiler['cliente'] }}</td>
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
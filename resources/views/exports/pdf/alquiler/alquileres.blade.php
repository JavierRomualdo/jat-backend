@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Listado: </strong>Alquileres | 
    <strong>Propiedad: </strong> {{$propiedad}}
</h5>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
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
        @foreach($alquileres as $index=>$alquiler)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $alquiler['propiedad_codigo'] }}</td>
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
</div>
@endsection
@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Listado: </strong>Ventas | 
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
                <td>{{ $venta['ubicacion'] }}</td>
                <td>{{ $venta['direccion'] }}</td>
                <td>{{ $venta['preciocontrato'] }}</td>
                <td>{{ $venta['fechaVenta'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
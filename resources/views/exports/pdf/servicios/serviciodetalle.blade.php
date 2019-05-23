@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Información del servicio</strong>
</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Datos generales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Servicio <span class="text-primary">*</span></strong></td>
            <td>{{ $servicio['servicio'] }}</td>
            <td><strong>Detalle <span class="text-primary">*</span></strong></td>
            <td>{{ $servicio['detalle'] }}</td>
        </tr>
    </tbody>
</table>
@endsection
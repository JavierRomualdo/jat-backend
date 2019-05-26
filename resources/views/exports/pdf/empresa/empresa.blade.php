@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Empresa</strong>
</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Datos generales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Empresa <span class="text-primary">*</span></strong></td>
            <td>{{ $empresa->nombre}}</td>
            <td><strong>Ruc <span class="text-primary">*</span></strong></td>
            <td>{{ $empresa->ruc }}</td>
        </tr>
        <tr>
            <td><strong>Ubigeo <span class="text-primary">*</span></strong></td>
            <td>{{ $ubigeo->departamento->ubigeo }} - 
                {{ $ubigeo->provincia->ubigeo }} - 
                {{ $ubigeo->ubigeo->ubigeo }}</td>
            <td><strong>Dirección <span class="text-primary">*</span></strong></td>
            <td>{{ $empresa->direccion }}</td>
        </tr>
        <tr>
            <td><strong>Teléfono <span class="text-primary">*</span></strong></td>
            <td>{{ $empresa->telefono }}</td>
            <td><strong>Email <span class="text-primary">*</span></strong></td>
            <td>{{ $empresa->correo }}</td>
        </tr>
    </tbody>
</table>
@endsection
@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Información persona</strong>
</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Datos generales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Nombres <span class="text-primary">*</span></strong></td>
            <td>{{ $persona->nombres }}</td>
            <td><strong>Dni <span class="text-primary">*</span></strong></td>
            <td>{{ $persona->dni }}</td>
        </tr>
        <tr>
            <td><strong>Email <span class="text-primary">*</span></strong></td>
            <td>{{ $persona->correo }}</td>
            <td><strong>Ubigeo <span class="text-primary">*</span></strong></td>
            <td>{{ $ubigeo->departamento->ubigeo }} - 
                {{ $ubigeo->provincia->ubigeo }} - 
                {{ $ubigeo->ubigeo->ubigeo }}</td>
        </tr>
        <tr>
            <td><strong>Dirección <span class="text-primary">*</span></strong></td>
            <td>{{ $persona->direccion }}</td>
            <td><strong>Teléfono <span class="text-primary">*</span></strong></td>
            <td>{{ $persona->telefono }}</td>
        </tr>
        <tr>
            <td><strong>Rol <span class="text-primary">*</span></strong></td>
            <td>{{ $rol->rol }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection
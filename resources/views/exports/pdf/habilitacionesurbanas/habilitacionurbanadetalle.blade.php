@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Información de la habilitacon urbana</strong>
</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Datos generales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Nombre <span class="text-primary">*</span></strong></td>
            <td>{{ $habilitacionurbana['nombre'] }}</td>
            <td><strong>Siglas <span class="text-primary">*</span></strong></td>
            <td>{{ $habilitacionurbana['siglas'] }}</td>
        </tr>
    </tbody>
</table>
@endsection
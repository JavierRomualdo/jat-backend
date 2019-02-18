@extends('exports.pdf.propiedades.'.$tipodoc)
@section('contrato')
<h5 class="page-header">
    <strong>Alquiler</strong>
</h5>
<table class="table table-sm table-hover table-striped table-bordered">
    <thead>
    <tbody>
        <tr>
            <td><strong>Cliente <span class="text-primary">*</span></strong></td>
            <td colspan="3">{{ $alquiler['cliente'] }}</td>
        </tr>
        <tr>
            <td><strong>Desde <span class="text-primary">*</span></strong></td>
            <td>{{ $alquiler['fechadesde'] }}</td>
            <td><strong>Hasta <span class="text-primary">*</span></strong></td>
            <td>{{ $alquiler['fechahasta'] }}</td>
        </tr>
    </tbody>
</table>
@endsection
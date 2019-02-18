@extends('exports.pdf.propiedades.'.$tipodoc)
@section('contrato')
<h5 class="page-header">
    <strong>Venta</strong>
</h5>
<table class="table table-sm table-hover table-striped table-bordered">
    <thead>
    <tbody>
        <tr>
            <td><strong>Cliente <span class="text-primary">*</span></strong></td>
            <td>{{ $venta['cliente'] }}</td>
            <td><strong>Fecha Venta <span class="text-primary">*</span></strong></td>
            <td>{{ $venta['fechaVenta'] }}</td>
        </tr>
    </tbody>
</table>
@endsection
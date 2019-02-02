@extends('layouts.pdf')
@section('content')
<h5 class="page-header">
    <strong>Propiedad:</strong> Lote | 
    <strong>Código:</strong> {{ $lote->codigo }} | 
    <strong>Fecha:</strong> {{$fechaActual}}
</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Datos generales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Propietario <span class="text-primary">*</span></strong></td>
            <td>{{ $propietario['nombres'] }}</td>
            <td><strong>Contrato <span class="text-primary">*</span></strong></td>
            <td>
                @if ($lote->contrato == 'A')
                    Alquiler
                @elseif ($lote->contrato == 'V')
                    Venta
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Foto principal <span class="text-primary">*</span></strong></td>
            <td>Foto</td>
            <td><strong>Precio adquisición <span class="text-primary">*</span></strong></td>
            <td>S/ {{ $lote->precioadquisicion }}</td>
        </tr>
        <tr>
            <td><strong>Precio contrato <span class="text-primary">*</span></strong></td>
            <td>S/ {{ $lote->preciocontrato }}</td>
            <td><strong>Largo <span class="text-primary">*</span></strong></td>
            <td>{{ $lote->largo }} m</td>
        </tr>
        <tr>
            <td><strong>Ancho <span class="text-primary">*</span></strong></td>
            <td>{{ $lote->ancho }} m</td>
            <td><strong>Ubigeo <span class="text-primary">*</span></strong></td>
            <td>{{ $ubigeo->departamento->ubigeo }} - 
                {{ $ubigeo->provincia->ubigeo }} - 
                {{ $ubigeo->ubigeo->ubigeo }}</td>
        </tr>
        <tr>
            <td><strong>Dirección <span class="text-primary">*</span></strong></td>
            <td>{{ $lote->direccion }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<table class="table table-sm table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="4" class="text-center">Más datos</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Referencia <span class="text-primary">*</span></strong></td>
            <td>Cerca de la ferreteria el pintor y del puente peatonal.</td>
            <td><strong>Descripción <span class="text-primary">*</span></strong></td>
            <td>{{ $lote->descripcion }}</td>
        </tr>
    </tbody>
</table>
<br/>
<table class="table table-sm table-hover table-striped table-bordered">
    <thead>
        <tr class="table-secondary">
            <th class="text-center">Imágenes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($imagenes as $index=>$imagen)
        <tr>
            <td class="text-center">
                <img src="{{ $imagen['foto'] }}" alt="...">
            </td>
        </tr>
        <tr>
            <td class="text-center">#{{$index+1}} {{$imagen['detalle']}}</td>
        </tr>
        @endforeach
        <!-- <tr>
            <td class="text-center">
                <img src="https://firebasestorage.googleapis.com/v0/b/inmobiliaria-dd0b7.appspot.com/o/lotes%2FCA00001%2F2.jpg?alt=media&token=7b4c01d1-6d45-458a-984c-76c2f32ab774" alt="... oli" width="200">
            </td>
        </tr>
        <tr>
            <td class="text-center">#2 Fachada</td>
        </tr> -->
    </tbody>
</table>
@endsection
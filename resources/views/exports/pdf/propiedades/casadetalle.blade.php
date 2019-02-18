@extends('layouts.pdf')
@section('content')
@yield('contrato')
<h5 class="page-header">
    <strong>Propiedad:</strong> Casa | 
    <strong>Código:</strong> {{ $casa->codigo }}
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
                @if ($casa->contrato == 'A')
                    Alquiler
                @elseif ($casa->contrato == 'V')
                    Venta
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Foto principal <span class="text-primary">*</span></strong></td>
            <td>Foto</td>
            <td><strong>Precio adquisición <span class="text-primary">*</span></strong></td>
            <td>S/ {{ $casa->precioadquisicion }}</td>
        </tr>
        <tr>
            <td><strong>Precio contrato <span class="text-primary">*</span></strong></td>
            <td>S/ {{ $casa->preciocontrato }}</td>
            <td><strong>Largo <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->largo }} m</td>
        </tr>
        <tr>
            <td><strong>Ancho <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->ancho }} m</td>
            <td><strong>Ubigeo <span class="text-primary">*</span></strong></td>
            <td>{{ $ubigeo->departamento->ubigeo }} - 
                {{ $ubigeo->provincia->ubigeo }} - 
                {{ $ubigeo->ubigeo->ubigeo }}</td>
        </tr>
        <tr>
            <td><strong>Dirección <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->direccion }}</td>
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
            <td><strong>Pisos <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->npisos }}</td>
            <td><strong>Cuartos <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->ncuartos }}</td>
        </tr>
        <tr>
            <td><strong>Baños <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->nbanios }}</td>
            <td><strong>¿Jardín? <span class="text-primary">*</span></strong></td>
            <td>
                @if ($casa->tjardin)
                    Si
                @else
                    No
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>¿Cochera? <span class="text-primary">*</span></strong></td>
            <td>
                @if ($casa->tcochera)
                    Si
                @else
                    No
                @endif
            </td>
            <td><strong>Referencia <span class="text-primary">*</span></strong></td>
            <td>{{ $casa->referencia }}</td>
        </tr>
        <tr>
            <td><strong>Descripción <span class="text-primary">*</span></strong></td>
            <td colspan="3">{{ $casa->descripcion }}</td>
        </tr>
    </tbody>
</table>
<table class="table table-sm table-hover table-striped table-bordered">
    <thead>
        <tr class="table-secondary">
            <th colspan="3" class="text-center">Servicios</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Servicio</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
        @foreach($servicios as $index=>$servicio)
        <tr>
            <th scope="row">{{$index+1}}</th>
            <td>{{ $servicio['servicio'] }}</td>
            <td>{{ $servicio['detalle'] }}</td>
        </tr>
        @endforeach
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
                <img src="https://firebasestorage.googleapis.com/v0/b/inmobiliaria-dd0b7.appspot.com/o/casas%2FCA00001%2F2.jpg?alt=media&token=7b4c01d1-6d45-458a-984c-76c2f32ab774" alt="... oli" width="200">
            </td>
        </tr>
        <tr>
            <td class="text-center">#2 Fachada</td>
        </tr> -->
    </tbody>
</table>
@endsection
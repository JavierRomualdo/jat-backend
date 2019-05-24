@extends('layouts.excel')
@section('content')
<h3>Listado de personas</h3>
<h4>Fecha: {{$fechaActual}}</h4>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Nombres</th>
            <th>Dni</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($personas as $index=>$persona)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $persona['nombres'] }}</td>
                <td>{{ $persona['dni'] }}</td>
                <td>{{ $persona['ubicacion'] }}</td>
                <td>{{ $persona['direccion'] }}</td>
                <td>{{ $persona['telefono'] }}</td>
                <td>{{ $persona['correo'] }}</td>
                <td>{{ $persona['rol'] }}</td>
                <td>
                    @if ($persona['estado'])
                        Si
                    @else
                        No
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
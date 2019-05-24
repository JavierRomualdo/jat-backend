@extends('layouts.excel')
@section('content')
<h3>Listado de habilitaciones urbanas</h3>
<h4>Fecha: {{$fechaActual}}</h4>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Siglas</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($habilitacionesurbanas as $index=>$habilitacionurbana)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $habilitacionurbana['nombre'] }}</td>
                <td>{{ $habilitacionurbana['siglas'] }}</td>
                <td>
                    @if ($habilitacionurbana['estado'])
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
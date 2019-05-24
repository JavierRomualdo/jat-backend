@extends('layouts.excel')
@section('content')
<h3>Listado de servicios</h3>
<h4>Fecha: {{$fechaActual}}</h4>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Servicio</th>
            <th>Detalle</th>
            <th>Activo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servicios as $index=>$servicio)
            <tr>
                <th scope="row">{{$index+1}}</th>
                <td>{{ $servicio['servicio'] }}</td>
                <td>{{ $servicio['detalle'] }}</td>
                <td>
                    @if ($servicio['estado'])
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
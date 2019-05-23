@extends('layouts.pdf')
@section('content')
<h6 class="page-header">
    <strong>Servicios</strong> 
</h6>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered" style="font-size: 11px">
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
</div>
@endsection
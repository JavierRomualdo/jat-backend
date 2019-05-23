@extends('layouts.pdf')
@section('content')
<h6 class="page-header">
    <strong>Habilitaciones urbanas</strong> 
</h6>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-bordered" style="font-size: 11px">
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
</div>
@endsection
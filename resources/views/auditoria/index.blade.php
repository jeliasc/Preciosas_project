@extends('adminlte::page')

@section('title', 'Auditoría')

@php
    use Carbon\Carbon;
    Carbon::setLocale('es');
@endphp

@section('content_header')
    <h3>Auditoría del Sistema</h3>
@stop

@section('content')

<div class="container-fluid">
    <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Tabla</th>
                <th>Operación</th>
                <th>Registro</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($auditoria as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->tabla_afectada }}</td>
                <td>{{ $a->operacion }}</td>
                <td>{{ $a->registro_nombre }}</td>
                <td>{{ $a->usuario_nombre ?? 'Sistema' }}</td>
                <td data-order="{{ $a->fecha_hora }}">
                    {{ Carbon::parse($a->fecha_hora)->translatedFormat('d \\d\\e F Y H:i') }}
                </td>
                <td>{{ $a->descripcion }}</td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" onclick="verDetalle({{ $a->id }})">
                        Ver
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Detalle de Auditoría</h5>

                <button type="button" class="close" onclick="cerrarModalAuditoria()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="detalleAuditoria"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalAuditoria()">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

@stop

@section('js')
    <script src="{{ asset('js/preciosas/auditoria/auditoria.js') }}"></script>
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
@stop
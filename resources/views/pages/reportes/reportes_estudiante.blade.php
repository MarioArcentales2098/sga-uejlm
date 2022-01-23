@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Reportes')
@section('style-herramienta')
<style>
    .thead-custom-bg tr th {
        background: #dfdfdf;
        text-align: center;
        vertical-align: middle;
        padding: 5px;

        /* background: #dfdfdf; */
    }

    .container-tab-cust {
        border: solid 1px #dee2e6;
        /* border-top: none; */
        padding: 20px;
    }

    /*#################### ASISTENCIA #######################*/
    .text-name-estu th {
        overflow: hidden;
        white-space: nowrap;
        width: 300px;
        text-overflow: ellipsis;
    }

    .div_search {
        background: #fef8b1;
        height: 43px;
        display: flex;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #dee2e6;
        /* border-bottom: 1px solid #dee2e6; */
    }

    /*################### CALIFICACIONES ###########################*/

    /*################### REPORTE ###########################*/
    .grid_report a {
        border: solid 1px #c2c2c2;
        border-style: dashed;
        border-radius: 15%;
        height: 300px;
        width: 300px;
        cursor: pointer;
        color: #212529;
        background: #efefef52;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    .grid_report a {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        color: #212529;
    }

    .grid_report a i {
        font-size: 90px;
    }

    .grid_report a div {
        margin-top: 10px;
        font-size: 30px;
    }

    .grid_report:hover {
        transform: scale(1.1);
    }

    .has-error .select2-container--default .select2-selection--single {
        border: solid 1px #d55151;
    }

    .has-error label {
        color: #d55151;
    }

</style>
<link rel="stylesheet" href="{{asset("herramienta/lib/select2/css/select2.min.css")}}">
@endsection

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Reportes: {{ $curso->nombre }} {{ $curso->nivel }} {{ $curso->paralelo }}</h4>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<input type="hidden" id="idasignacion" value="{{ $curso->id }}">
<!-- CUERPO REPORTES -->
<div class="row">
    <div class="col-12">
        <div class="card-box" style="min-height: 400px;">
            <div class="row">
                <div class="col-md-8 form-group" id="select_asignatura-has-error">
                    <label class="text-strong">Asignaturas:</label><br>
                    <select id="select_asignatura" class="form-control form-control-sm select-destin" onchange="validateReport(this.id)" disabled style="width:100%">
                        <option value="">-- Seleccionar --</option>
                        @foreach ($asignaturas as $item)
                        <option value="{{ $item->clase_fk }}-{{ $item->clase_token }}">[{{$item->codigo_asignatura}}-{{$item->codigo_asignatura_num}}] - {{mb_strtoupper($item->nombre)}} [DOCENTE: {{mb_strtoupper($item->usuario_pa)}} {{mb_strtoupper($item->usuario_sa)}} {{mb_strtoupper($item->usuario_pn)}} {{mb_strtoupper($item->usuario_sn)}}]</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex" style="align-items: center; margin-top: 12px;">
                    <div><button type="button" class="btn btn-sm btn-danger" id="btnGenerarReportes"><i class="ti-filter"></i> Consultar</button></div>
                </div>
                {{-- <div class="col-md-2 d-flex" style="align-items: center; margin-top: 12px;">
                    <div><a href="/reportes/buscar/calificaciones-asignatura/clase" onclick="window.open(this.href,'window','width=1275, height=775');return false" class="btn btn-sm btn-info" id="btnGenerarCalificacionesMaterias"><i class="ti-filter"></i> Consultar</a></div>
                </div> --}}



                <div class="col-md-2 d-flex" style="align-items: center; justify-content: end; margin-top: 12px;">
                    <div id="content_btn_print">
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 40px; margin-bottom:60px;">
                    <div id="cont_report">
                        <table class="table table-bordered">
                            <tbody id="contenido_reporte_estudiante">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/lib/select2/js/select2.min.js?v=$ran")}}"></script>
<script src="{{asset("herramienta/reportes/reporte_estudiante.js?v=$ran")}}"></script>
@endsection

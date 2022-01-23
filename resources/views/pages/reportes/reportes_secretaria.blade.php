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
        /* border: solid 1px #c2c2c2; */
        /* border: solid 1px #f93737; */
        border-style: dashed;
        border-radius: 15%;
        height: 227px;
        width: 227px;
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
        font-size: 60px;
    }
    .grid_report a div {
        margin-top: 10px;
        font-size: 30px;
    }
    .grid_report:hover {
        transform: scale(1.03);
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
            <h4 class="page-title float-left">Reportes</h4>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<!-- CUERPO REPORTES -->
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="text-strong">Cursos:</label>
                    <select id="select_curso" class="form-control form-control-sm select-destin" onchange="searchAsignaturesCurso(this.value)" disabled>
                        <option value="">-- Seleccionar --</option>
                        @foreach ($cursos as $item)
                        <option value="{{ $item->id }}">{{ $item->nombre }} {{ $item->nivel }} "{{ $item->paralelo }}"</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="text-strong">Asignaturas:</label>
                    <select id="select_asignatura" class="form-control form-control-sm select-destin" onchange="searchEstudiantesPorClase();" disabled></select>
                </div>
                <div class="col-md-12 form-group">
                    <label class="text-strong">Alumno:</label>
                    <select id="select_estudiantes" class="form-control form-control-sm select-destin" onchange="searchEstudiantesPorClase();" disabled></select>
                </div>
                <div class="col-md-12">
                    <div id="alert_danger_solicitud" style="display: none">
                        <div class="alert alert-danger" id="msj_danger"></div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 40px; margin-bottom:60px;">
                    <div id="cont_report" style="display: none">
                        <div class="d-flex justify-content-center" style="grid-gap: 120px;">
                            <div class="grid_report">
                                <a id="report_asistencia" style="border: solid 1px #f93737; border-style: dashed;">
                                    <i class="ti-calendar"></i>
                                    <div>Asistencia</div>
                                </a>
                            </div>
                            <div class="grid_report">
                                <a id="report_calificacion" style="border: solid 1px #007bff; border-style: dashed;">
                                    <i class="ti-agenda"></i>
                                    <div>Calificaciones</div>
                                </a>
                            </div>
                        </div>
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
<script src="{{asset("herramienta/reportes/reporte_secretaria.js?v=$ran")}}"></script>
@endsection

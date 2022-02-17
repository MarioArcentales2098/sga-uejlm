@extends('layouts.herramienta.herramienta')
@section('title-herramienta', "Clase: $clase->asignatura_nombre")
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
        height: 150px;
        width: 150px;
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
        font-size: 40px;
    }
    .grid_report a div {
        margin-top: 10px;
        font-size: 17px;
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

    .text-ss{
        font-weight: bold;
    }

    .text-activity{
        height: 225px;
        width: 10px;
        cursor: pointer;
        border: 1px solid #818181;
        /* border-top: 1px solid #818181; */
        /* border-bottom: 1px solid #818181;         */
    }

    .text-activity >div{
        /* writing-mode: vertical-lr; */
        /* transform: rotate(180deg); */
        /* height: 100%; */
        /* text-align: justify; */
    }

</style>

<link rel="stylesheet" href="{{asset("herramienta/lib/select2/css/select2.min.css")}}">
@endsection
@yield('style-clases')

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">
                Clase: {{ $clase->asignatura_nombre }} <small style="font-size: 14px;">( {{$clase->asignatura_codigo}}{{$clase->asignatura_num }} )</small>
                <div style="font-size: 15px; font-weight: 400;">Curso: {{$clase->curso_nombre}} {{$clase->curso_nivel}} "{{$clase->curso_paralelo}}"</div>
            </h4>
            <div class="float-right">
                <a href="{{ route('viewClasesestudiante') }}" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i> Regresar</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<input type="hidden" id="fecha_default" value="{{date('Y-m-d')}}">
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="asistencia-tab" data-toggle="tab" href="#asistencia" role="tab" aria-controls="asistencia" aria-expanded="true"><i class="ti-calendar mr-2"></i> Asistencia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="calificacion-tab" data-toggle="tab" href="#calificacion" role="tab" aria-controls="calificacion"><i class="ti-agenda mr-2"></i> Calificaciones</a>
                        </li>
                    </ul>
                    <div class="tab-content container-tab-cust" id="myTabContent" style="min-height: 600px;">
                        <!--#################### asistencias ###################-->
                        <div class="tab-pane fade in active show" id="asistencia" role="tabpanel" aria-labelledby="asistencia-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Parcial:</label>
                                            <select id="periodoestudio" class="form-control form-control-sm" onchange="generarEstudiantesDate()">
                                                <option value="--" selected>Seleccionar</option>
                                                @foreach($quimestres as $quimestre)
                                                <optgroup label="{{$quimestre->nombre}}">
                                                    @foreach($parciales as $parcial)
                                                    @if ($parcial->quimestre_fk == $quimestre->id)
                                                    <option value="{{ $quimestre->id }}-{{ $parcial->id }}"> {{ $quimestre->nombre }} {{ $parcial->nombre }} </option>
                                                    @endif
                                                    @endforeach
                                                </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="contenedor_boton_asistencia"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- tabla asistencia -->
                                <div class="col-md-12 mt-4">
                                    <div class="mb-1" style="color: #007AC7;font-size: 18px;">Asistencias</div>
                                    <div class="div_search">
                                        <input class="form-control form-control-sm w-25" id="input_asistencia" type="text" placeholder="Buscar....." autocomplete="off">
                                        <div class="m-auto w-75 text-right">
                                            <i class="fa fa-exclamation-circle text-info" data-toggle="tooltip"  data-html="true" data-placement="left" 
                                            title='
                                                <div class="text-left">
                                                    <div><i class="fa fa-check text-success"></i> Asistió</div>
                                                    <div><i class="fa fa-check text-danger"></i> Asistencia justificada</div>
                                                    <div><i class="fa fa-times text-danger"></i> Asistencia injustificada</div>
                                                </div>
                                            '>
                                            </i> Asistencia
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="tablesaw-custom table table-striped  table-sm mb-0">
                                            <tbody id="table_asistencia"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--################### calificaciones #################-->
                        <div class="tab-pane fade" id="calificacion" role="tabpanel" aria-labelledby="calificacion-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row d-flex">
                                        <div class="col-md-8">
                                            <div class="row d-flex">
                                                <div class="col-md-4">
                                                    <label>Parcial:</label>
                                                    <select id="periodoestudio_calificacion" class="form-control form-control-sm" onchange="seleccionacionar_contenido();">
                                                        <option value="" selected>Seleccionar</option>
                                                        @foreach($quimestres as $quimestre)
                                                            @foreach($parciales as $parcial)
                                                            @if ($parcial->quimestre_fk == $quimestre->id)
                                                            <option value="{{ $quimestre->id }}-{{ $parcial->id }}" data-option="P"> {{ $quimestre->nombre }} - {{ $parcial->nombre }} </option>
                                                            @endif
                                                            @endforeach
                                                            <option value="{{ $quimestre->id }}" data-option="Q" >{{ $quimestre->nombre }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="contenedor_boton_nuevo"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="ml-auto col-md-4">
                                            <div style="border:1px solid #DEE2E6;border-radius:9px;padding:8px;">
                                                @foreach ($actividades as $actividad)
                                                <div class="d-flex align-items-center">
                                                    <div style="background:{{$actividad->color}};height:10px;width:10px;"></div>
                                                    <div style="margin-left:5px;font-size:11px;">{{$actividad->tipo_actividad_nombre}}: {{$actividad->nombre}} ({{$actividad->porcentaje}}%)</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>-->
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="mb-1" style="color: #007AC7;font-size: 18px;">Actividades</div>
                                    <div class="div_search">
                                        <input class="form-control form-control-sm w-25" id="input_calificaciones" type="text" placeholder="Buscar....." autocomplete="off">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="tablesaw-custom table table-striped  table-sm mb-0">
                                            <tbody id="table_calificaciones"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="idasignacion" value="{{ $clase->id }}">
<input type="hidden" id="tokenasignacion" value="{{ $clase->token }}">

@endsection

@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/clasesestudiante/clase_asistencia.js?v=$ran")}}"></script>
<script src="{{asset("herramienta/clasesestudiante/clases_calificaciones.js?v=$ran")}}"></script>

<script src="{{asset("herramienta/clasesestudiante/clase_reporte.js?v=$ran")}}"></script>
<script src="{{asset("herramienta/lib/select2/js/select2.min.js?v=$ran")}}"></script>
<script>
    $(document).ready(function() {
        $('.select-destin').select2();
    });

</script>
@endsection

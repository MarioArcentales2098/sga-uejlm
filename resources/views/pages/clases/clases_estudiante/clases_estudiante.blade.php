@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Clases')
@section('style-herramienta')
<style>
    .cursos_conta a{
          text-decoration: none;
          color: #212529;
    }

    .two_line{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .title_asign{
        /* font-size: 1rem; */
        font-size: 17px;
        font-weight: 500;
        line-height: 1.2;
        height: 45px;
    }
    .data_asign{
        height:55px;
        font-weight:500;
        font-size: 13px;
    }

    .class_hover:hover{
        -ms-transform: scale(1.02); /* IE 9 */
        -webkit-transform: scale(1.02); /* Safari 3-8 */
        transform: scale(1.02);
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075)!important;
    }
</style>
@endsection

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12 ">
        <div class="page-title-box" style="display: flex;">
            <h4 class="page-title float-left">Clases</h4>
            <div class="ml-auto row" style="width: 60%;">
                <div class="col-md-8">
                    <form action="{{ route('viewClasesestudiante') }}" method="GET" id="formSearch">
                        <input type="text" class="form-control form-control-sm" name="texto" value="{{$texto}}" placeholder="Buscar...." autocomplete="off">
                    </form>
                </div>
                <div class="col-md-4 d-flex" style="justify-content: center;  align-items: end;  width: 100%;">
                    <div class="w-100"><button type="submit" class="btn btn-sm btn-primary w-100" form="formSearch"><i class="fa fa-search"></i> Buscar</button></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    @if (sizeof($clases) > 0)
        @foreach ($clases as $item)
        <div class="col-md-6 col-xl-6">
            <a href="{{ route('viewDetailClaseEstudiante', ['id'=>$item->idasignacion,'token'=>$item->tokenasignacion]) }}" >
                <div class="card-box tilebox-three class_hover" style="height: 145px; border: solid 1px {{$item->asignatura_color}};">
                    <div class="bg-icon float-left" style="border: 1px dashed {{$item->asignatura_color}}!important; ">
                        <i class="ti-desktop" style="color: {{$item->asignatura_color}} !important;"></i>
                    </div>
                    <div>
                        <div class="text-uppercase two_line title_asign" style="color: {{$item->asignatura_color}} !important;">
                            {{ $item->asignatura_nombre }}
                        </div>
                        <div class="text-muted text-uppercase data_asign">
                            <i class="icon-puzzle"></i> CURSO: {{ $item->curso_nombre }} {{ $item->curso_nivel }} "{{$item->curso_paralelo}}"
                            <div><i class="ti-user"></i> DOCENTE: {{$item->docente_papellido}} {{$item->docente_sapellido}} {{$item->docente_pnombre}} {{$item->docente_snombre}}</div>
                        </div>
                        <div class="d-flex text-muted">
                            <small class="text-left">CÓDIGO: {{$item->asignatura_codigo}}{{$item->asignatura_codigo_num}}</small>
                            {{-- <small class="ml-auto"><i class="ti-face-smile"></i>&nbsp; ALUMNOS: {{ $item->alumnos }}</small></small> --}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
        <div class="mt-3 col-md-12 col-lg-12 col-sm-12 col-xs-12">
            {{ $clases->withQueryString()->links() }}
        </div>
    @else
    <div class="col-md-12 text-center" style="margin-top: 40px;">
        <div style="font-size: 25px;font-weight: 400;">No se encontrarón registros para el actual periodo.</div>
        <img src="{{ asset('recursos/classempty.svg') }}" height="280px">
    </div>
    @endif
</div>


@endsection

@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/clasesestudiante/clases.js?v=$ran")}}"></script>
@endsection

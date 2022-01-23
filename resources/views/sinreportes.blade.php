@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Reportes')
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
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Reportes: ""</h4>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">    
    <div class="col-md-12 text-center" style="margin-top: 40px;">
        <div style="font-size: 25px;font-weight: 400;">Aún no esta matriculado en un curso.</div>
        <img src="{{ asset('recursos/classempty.svg') }}" height="280px">
    </div>   
</div>
@endsection


{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

<div class="card-body">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    {{ __('You are logged in!') }}
</div>
</div>
</div>
</div>
</div>
@endsection --}}

@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Panel')
@section('style-herramienta')@endsection
@section('content-herramienta')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Bienvenido, {{Auth::user()->primer_nombre}} {{Auth::user()->segundo_nombre}} {{Auth::user()->apellido_paterno}} {{Auth::user()->apellido_materno}}</h4>
            @if (Auth::user()->tipo_usuario == 2)
            <div class="float-right">
                <a href="{{ route('calificacionesEstudianteXMateriaPDF') }}"  class="btn btn-sm btn-info" onclick="window.open(this.href,'window','width=1275, height=775');return false"><i class="ti-printer"></i> Generar Calficaciones PDF</a>
            </div>
            @endif
            <div class="clearfix"></div>
        </div>
    </div>
</div>

@if (Auth::user()->tipo_usuario == 5)
@include('home.home_admin')
@endif

@if (Auth::user()->tipo_usuario == 4)
@include('home.home_admin')
@endif

@if (Auth::user()->tipo_usuario == 3)
@include('home.home_docente')
@endif

@if (Auth::user()->tipo_usuario == 2)
@include('home.home_estudiante')
@endif

@if (Auth::user()->tipo_usuario == 1)
<div class="row" style="height: 50vh; align-items: center;">
    <div class="col-md-12 text-center">
        <img src="{{ asset('recursos/LogoRM.png') }}" alt="">
    </div>
</div>
@endif

@endsection

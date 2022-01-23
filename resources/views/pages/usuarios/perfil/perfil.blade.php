@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Perfil de usuario')
@section('style-herramienta')@endsection
@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Perfil de usuario</h4>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30"><i class="zmdi zmdi-account"></i><span> Datos de usuario</h4>
            <div class="row">
                <div class="col-lg-6 form-group">
                    <label>Primero nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pnombre" value="{{ Auth::user()->primer_nombre }}" onkeyup="validateRegisterSimple(this.id);">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Segundo nombre</label>
                    <input type="text" class="form-control" id="snombre" value="{{ Auth::user()->segundo_nombre }}">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Primero apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="papellido" value="{{ Auth::user()->apellido_paterno }}" onkeyup="validateRegisterSimple(this.id);">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Segundo apellido</label>
                    <input type="text" class="form-control" id="sapellido" value="{{ Auth::user()->apellido_materno }}">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Cédula <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cedula" value="{{ Auth::user()->cedula }}" onkeyup="validateRegisterSimple(this.id);">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Correo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="correo" value="{{ Auth::user()->email }}" onkeyup="validateRegisterSimple(this.id);">
                </div>

                <div class="col-lg-12 d-flex">
                    <button class="ml-auto btn btn-primary waves-effect waves-light" id="btnEditRegister" type="button"><i class="fa fa-check"></i> Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-20"><i class="zmdi zmdi-key"></i><span> Cambio de contraseña</h4>
            <div class="row">
                <div class="col-xl-12 m-b-20">
                    <div class="d-flex">
                        <div class="alert" id="alert-success" style="display: none; background: #1F9148; margin-bottom: 0px; padding: 5px 1.25rem; color: #fff; width: 38%; text-align: center; margin-left: auto; margin-right: auto;">
                            <small class="text-mute"><i class="fa fa-spinner fa-spin"></i> Cerrando sesión.....</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 form-group">
                    <label>Actual contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="old_pass" onkeyup="validateRegisterSimple(this.id);">
                </div>
                <div class="col-lg-6 form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" class="form-control" id="new_pass" onkeyup="validateRegisterSimple(this.id);">
                    <small class="text-muted">La contraseña debe contener mínimo 6 carácteres.</small>
                </div>
                <div class="col-lg-12 d-flex">
                    <button class="ml-auto btn btn-primary waves-effect waves-light" id="btnChangePass" type="button"><i class="fa fa-check"></i> Cambiar contraseña</button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="idusuario" value="{{ Auth::user()->id }}">
<input type="hidden" id="tokenusuario" value="{{ Auth::user()->token }}">

@endsection
@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/usuarios/perfil.js?v=$ran")}}"></script>
@endsection

@extends('layouts.auth.auth')
@section('title-auth', 'Inicio sesión')
@section('section-auth')
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class="account-bg">
        <div class="card-box mb-0">
            <div class="text-center m-t-20">
                <a href="{{route('welcome')}}" class="logo">
                    <img src="{{ asset('recursos/LogoP.png') }}" alt="logo" style="height: 100px;">
                </a>
            </div>
            <div class="m-t-10 p-20">
                <div class="row">
                    <div class="col-12 text-center">
                        <h6 class="text-muted text-uppercase m-b-0 m-t-0">Iniciar sesión</h6>
                    </div>
                </div>

                @if (session('error'))
                    <div id="alerta_error">
                        <div style="font-size: 11px;" class="alert alert-danger text-center text-uppercase">{{session('error')}}</div>
                    </div>
                    <script>
                        window.setTimeout(() => {document.getElementById('alerta_error').style.display = "none";}, 5000);
                    </script>
                @endif

                <form class="m-t-20" method="POST" action="{{ route('login') }}">@csrf
                    <div class="form-group row">
                        <div class="col-12">
                            <label>Cédula o Correo</label>
                            <input class="form-control" type="login" name="login" value="{{ old('login') }}" autocomplete="login" autofocus placeholder="Cédula" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <label>Contraseña</label>
                            <input class="form-control" type="password" name="password" required placeholder="Contraseña">
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-10">
                        <div class="col-12"><button class="btn btn-success btn-block waves-effect waves-light" type="submit">Iniciar sesión</button></div>
                    </div>

                    {{-- <div class="form-group row m-t-30 mb-0">
                        <div class="col-12">
                            <a href="pages-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                        </div>
                    </div>    --}}
                </form>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection

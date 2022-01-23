<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{route('home')}}" class="logo">
            <img src="{{ asset('recursos/icologoRM.png') }}" class="icon-c-logo" height="50px" width="50px" alt="">
            <span><img src="{{ asset('recursos/dicologoRM.svg') }}" height="50px" width="110px" alt=""></span>
        </a>
    </div>
   
    <nav class="navbar-custom">
        <ul class="list-inline float-right mb-0">            
            <!-- PERIODO ACTUAL -->
            <li class="list-inline-item dropdown notification-list text-white mr-4"><i class="zmdi zmdi-calendar-alt noti-icon" style="padding: 0 3px;"></i> {{ System::periodoActual() }}</li>
            <!-- USUARIO REGISTRADO -->
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user text-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{asset('herramienta/assets/images/users/invitado.png')}}" class="rounded-circle">
                    {{Auth::user()->cedula}}                   
                </a>                
                <div class="dropdown-menu dropdown-menu-right profile-dropdown" aria-labelledby="Preview">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <small class="text-white">Bienvenido!</small>
                        <h5 class="text-overflow"><small>{{Auth::user()->primer_nombre}} {{Auth::user()->apellido_paterno}}</small> </h5>
                    </div>

                    <a href="{{route('perfilUsuario')}}" class="dropdown-item notify-item"><i class="zmdi zmdi-account-circle"></i> <span>Perfil</span></a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-power"></i> <span>Cerrar sesión</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </li>
        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="zmdi zmdi-menu"></i>
                </button>
            </li>            
        </ul>

    </nav>

</div>
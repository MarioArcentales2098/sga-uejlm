<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="has_sub">
                    <a href="{{route('home')}}" class="waves-effect"><i class="zmdi zmdi-home"></i><span> Panel </span></a>
                </li>
                @can('viewGeneralAcademico')
                <!-- academico -->
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"> <i class="zmdi zmdi-format-underlined"></i><span> Académico </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        @can('viewGeneralPeriodoAcademico')
                        <li><a href="{{route('viewPeriodos')}}"><i class="zmdi zmdi-calendar-alt"></i>Periodo académico</a></li>
                        @endcan
                        @can('viewGeneralAsignatura')
                        <li><a href="{{route('viewAsignaturas')}}"><i class="zmdi zmdi-group"></i>Asignaturas</a></li>
                        @endcan
                        @can('viewGeneralCursos')
                        <li><a href="{{route('viewCursos')}}"><i class="zmdi zmdi-library"></i>Cursos</a></li>
                        @endcan
                        @can('viewGeneralMatricula')
                        <li><a href="{{route('viewMatriculas')}}"><i class="zmdi zmdi-widgets"></i>Matrículas</a></li>
                        @endcan
                        @can('viewGeneralEstudiantes')
                        <li><a href="{{route('viewEstudiantes')}}"><i class="zmdi zmdi-graduation-cap"></i>Estudiantes</a></li>
                        @endcan
                        @can('viewGeneralDocentes')
                        <li><a href="{{route('viewDocentes')}}"><i class="zmdi zmdi-graduation-cap"></i>Docentes</a></li>
                        @endcan
                    </ul>
                </li>                    
                @endcan
                @can('viewGeneralClases')
                    @if (Auth::user()->tipo_usuario == 3 ||Auth::user()->tipo_usuario == 5)
                    <!-- clases -->
                    <li class="has_sub">
                        <a href="{{route('viewClases')}}" class="waves-effect {{  Route::is('viewDetailClase')? 'active': ''}}"><i class="zmdi zmdi-laptop"></i><span> Clases </span></a>
                    </li>
                    @endif

                    @if (Auth::user()->tipo_usuario == 2)
                    <!-- clases -->
                    <li class="has_sub">
                        <a href="{{route('viewClasesestudiante')}}" class="waves-effect {{  Route::is('viewDetailClaseEstudiante')? 'active': ''}}"><i class="zmdi zmdi-laptop"></i><span> Clases </span></a>
                    </li>
                    @endif
                @endcan

                @if (Auth::user()->tipo_usuario == 4 ||Auth::user()->tipo_usuario == 5)
                <!-- reportes -->
                <li class="has_sub">
                    <a href="{{route('viewReportes')}}" class="waves-effect"><i class="zmdi zmdi-chart"></i><span> Reportes</span></a>
                </li>
                @endif

                @can('viewGeneralConfUsuarios')
                <!-- usuarios -->
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-accounts"></i><span> Configuración </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        @can('viewGeneralUsuarios')
                        <li><a href="{{route('viewUsuarios')}}"><i class="zmdi zmdi-account"></i> Usuarios</a></li>
                        @endcan
                        @can('viewGeneralUsuariosRoles')
                        <li class="{{Route::is('asignRolEdit')? 'active': ''}}"><a href="{{route('viewRoles')}}" class="waves-effect {{Route::is('asignRolEdit')? 'active': ''}}"><i class="zmdi zmdi-group-work"></i> Roles</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <!-- perfil -->
                <li class="has_sub">
                    <a href="{{route('perfilUsuario')}}" class="waves-effect"><i class="zmdi zmdi-settings"></i><span> Perfil </span></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>

</div>

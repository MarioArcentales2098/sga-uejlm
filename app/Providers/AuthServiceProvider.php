<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //################################################# ACADEMICO ###################################################################
            Gate::define('viewGeneralAcademico', function () {
                $role = DB::table('roles_has_permisos')
                    ->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAcademico')
                ->first();
                if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAcademico'; }else{ return null; }
            });

            //====== PERIODO ACADEMICO
                Gate::define('viewGeneralPeriodoAcademico', function () {
                    $role = DB::table('roles_has_permisos')
                    ->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademico')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralPeriodoAcademico'; }else{ return null; }
                });
                Gate::define('viewGeneralPeriodoAcademicoCreate', function () {
                    $role = DB::table('roles_has_permisos')
                    ->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademicoCreate')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralPeriodoAcademicoCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralPeriodoAcademicoEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademicoEdit')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralPeriodoAcademicoEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralPeriodoAcademicoDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademicoDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralPeriodoAcademicoDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralPeriodoAcademicoParciales', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademicoParciales')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralPeriodoAcademicoParciales'; }else{ return null; }
                });
            //==================================

            //====== ASIGNATURAS
                Gate::define('viewGeneralAsignatura', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAsignatura')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAsignatura'; }else{ return null; }
                });
                Gate::define('viewGeneralAsignaturaCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAsignaturaCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAsignaturaCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralAsignaturaEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAsignaturaEdit')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAsignaturaEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralAsignaturaDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAsignaturaDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAsignaturaDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralAsignaturaAsignDoce', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralAsignaturaAsignDoce')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralAsignaturaAsignDoce'; }else{ return null; }
                });                
            //==================================

            //====== CURSOS
                Gate::define('viewGeneralCursos', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralCursos')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralCursos'; }else{ return null; }
                });
                Gate::define('viewGeneralCursosCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralCursosCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralCursosCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralCursosPDF', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralCursosPDF')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralCursosPDF'; }else{ return null; }
                });
                Gate::define('viewGeneralCursosAsignar', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralCursosAsignar')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralCursosAsignar'; }else{ return null; }
                });
                Gate::define('viewGeneralCursosDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralCursosDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralCursosDelete'; }else{ return null; }
                });
            //==================================

            //====== MATRICULAS
                Gate::define('viewGeneralMatricula', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralMatricula')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralMatricula'; }else{ return null; }
                });
                Gate::define('viewGeneralMatriculaCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralMatriculaCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralMatriculaCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralMatriculaPDF', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralMatriculaPDF')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralMatriculaPDF'; }else{ return null; }
                });
                Gate::define('viewGeneralMatriculaEstudiantePDF', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralMatriculaEstudiantePDF')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralMatriculaEstudiantePDF'; }else{ return null; }
                });
                Gate::define('viewGeneralMatriculaDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralMatriculaDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralMatriculaDelete'; }else{ return null; }
                });

                
            //==================================

            //====== ESTUDIANTES
                Gate::define('viewGeneralEstudiantes', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralEstudiantes')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralEstudiantes'; }else{ return null; }
                });
                Gate::define('viewGeneralEstudiantesCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralEstudiantesCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralEstudiantesCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralEstudiantesEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralEstudiantesEdit')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralEstudiantesEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralEstudiantesDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralEstudiantesDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralEstudiantesDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralEstudiantesBan', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralEstudiantesBan')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralEstudiantesBan'; }else{ return null; }
                });
            //==================================

            //====== DOCENTES
                Gate::define('viewGeneralDocentes', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralDocentes')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralDocentes'; }else{ return null; }
                });
                Gate::define('viewGeneralDocentesCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralDocentesCreate')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralDocentesCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralDocentesEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralDocentesEdit')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralDocentesEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralDocentesDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralDocentesDelete')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralDocentesDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralDocentesBan', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralDocentesBan')
                    ->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralDocentesBan'; }else{ return null; }
                });
            //==================================
        //######################################################################################################################################

        //################################################# CLASES ###################################################################
            Gate::define('viewGeneralClases', function () {
                $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                ->where('roles_has_permisos.permiso', 'viewGeneralClases')->first();
                if($role || (Auth::user()->tipo_usuario == 5) || (Auth::user()->tipo_usuario == 3) || (Auth::user()->tipo_usuario == 2)){ return 'viewGeneralClases'; }else{ return null; }
            });
        //######################################################################################################################################

        //########################################## CONFIGURACION USUARIOS ###############################################################
            Gate::define('viewGeneralConfUsuarios', function () {
                $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                ->where('roles_has_permisos.permiso', 'viewGeneralConfUsuarios')->first();
                if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralConfUsuarios'; }else{ return null; }
            });

            //====== USUARIOS
                Gate::define('viewGeneralUsuarios', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuarios')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuarios'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosEdit')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosBan', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosBan')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosBan'; }else{ return null; }
                });
            //==================================

            //====== Roles
                Gate::define('viewGeneralUsuariosRoles', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRoles')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosRoles'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosRolesCreate', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRolesCreate')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosRolesCreate'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosRolesEdit', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRolesEdit')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosRolesEdit'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosRolesDelete', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRolesDelete')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosRolesDelete'; }else{ return null; }
                });
                Gate::define('viewGeneralUsuariosRolesAsign', function () {
                    $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')
                    ->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)
                    ->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRolesAsign')->first();
                    if($role || (Auth::user()->tipo_usuario == 5)){ return 'viewGeneralUsuariosRolesAsign'; }else{ return null; }
                });
            //==================================
        //######################################################################################################################################
    }
}

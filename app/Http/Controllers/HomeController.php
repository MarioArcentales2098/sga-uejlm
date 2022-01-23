<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $periodo_activo;
    public function __construct()
    {
        $this->middleware('auth');
        $consulta = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
        
        if($consulta){
            $this->periodo_activo =  $consulta->id;
        }else{
            $this->periodo_activo = 0;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->tipo_usuario == 5 || Auth::user()->tipo_usuario == 4) {
            $total_usuarios = DB::table('usuario')->whereIn('tipo_usuario', [2, 3, 4])->where('eliminado', [0])->count();
            $total_docentes = DB::table('usuario')->where('tipo_usuario', [3])->where('eliminado', [0])->count();
            $total_secretaria = DB::table('usuario')->where('tipo_usuario', [4])->where('eliminado', [0])->count();
            $total_estudiantes = DB::table('usuario')->where('tipo_usuario', [2])->where('eliminado', [0])->count();

            $total_cursos = DB::table('cursos')->where('eliminado', [0])->where('estado', [1])->count();
            $total_materias = DB::table('asignaturas')->where('eliminado', [0])->where('estado', [1])->count();

            return view('home', compact('total_usuarios', 'total_docentes', 'total_secretaria', 'total_estudiantes', 'total_cursos', 'total_materias'));
        }

        if (Auth::user()->tipo_usuario == 3) {
            $asignaturas = DB::table('asignaturas_asignacion_docentes as asign')
                ->leftJoin('asignaturas', 'asign.asignatura_fk', '=', 'asignaturas.id')
                // ->leftJoin('usuario', 'asign.docente_fk', '=', 'usuario.id')    
                ->select('asignaturas.*')           
                ->where('asign.periodo_fk', $this->periodo_activo)
                ->where('asignaturas.eliminado', [0])
                ->where('asign.docente_fk', Auth::user()->id)
            ->get();

            return view('home', compact('asignaturas'));
        }

        if (Auth::user()->tipo_usuario == 2) {
            $total_matricula = DB::table('matricula')->where('eliminado', [0])->where('estado', [1])->where('usuario_fk', Auth::user()->id)->count();
            $usuario = Auth::user()->id;
            $asignaturas = DB::table('matricula')
                ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                ->leftJoin('cursos_asignacion_materias', 'cursos.id', '=', 'cursos_asignacion_materias.curso_fk')
                ->leftJoin('asignaturas', 'cursos_asignacion_materias.asignatura_fk', '=', 'asignaturas.id')
                ->select('asignaturas.*')
                ->where('cursos_asignacion_materias.periodo_fk', $this->periodo_activo)
                ->where('matricula.periodo_fk', $this->periodo_activo)
                ->where('matricula.usuario_fk', Auth::user()->id)
            ->count();
            $faltas = DB::table('asistencia_clase')
                ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', 'periodolectivo_parcial.id')
                ->where('periodolectivo_parcial.periodolectivo_fk',  $this->periodo_activo)
                ->where('asistencia_clase.asistencia', [0])
                ->where('asistencia_clase.estudiante_fk', Auth::user()->id)
            ->count();
            $faltas_justificadas = DB::table('asistencia_clase')
                ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', 'periodolectivo_parcial.id')
                ->where('periodolectivo_parcial.periodolectivo_fk',  $this->periodo_activo)
                ->where('asistencia_clase.asistencia', [0])
                ->where('asistencia_clase.asistencia_justificada', [1])
                ->where('asistencia_clase.estudiante_fk', Auth::user()->id)
            ->count();

            return view('home', compact('asignaturas', 'faltas', 'faltas_justificadas'));
        }

        if (Auth::user()->tipo_usuario == 1) {
            return view('home');
        }
    }
}

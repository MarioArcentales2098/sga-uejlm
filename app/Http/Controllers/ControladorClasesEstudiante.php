<?php

namespace App\Http\Controllers;

use App\User;
use App\System;
use \PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorClasesEstudiante extends Controller{
    protected $date_actual;
    public function __construct(){
        $this->date_actual = date('Y-m-d H:i:s');
    }

    public function viewClasesestudiante(Request $request){
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        $texto = trim($request->get('texto'));
        if ($periodo_activo) {
            if (Auth::user()->tipo_usuario == 2) {

                $estudiante = DB::table('matricula')
                    ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                    ->select(
                        'cursos.id as id_curso',
                        'usuario.*'
                    )
                    ->where('usuario.id', Auth::user()->id)
                    ->where('usuario.tipo_usuario', [2])
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                ->first();

                if ($estudiante) {
                    $clases = DB::table('cursos_asignacion_materias as asignacion')
                        ->leftJoin('asignaturas_asignacion_docentes as doc', 'asignacion.asign_docmateria_fk', '=', 'doc.id')
                        ->leftJoin('asignaturas', 'doc.asignatura_fk', '=', 'asignaturas.id')
                        ->leftJoin('cursos', 'asignacion.curso_fk', '=', 'cursos.id')

                        // ->leftJoin('asignaturas_asignacion_docentes', 'asignaturas.id','=','asignaturas_asignacion_docentes.asignatura_fk')
                        ->leftJoin('usuario as docente', 'doc.docente_fk', '=', 'docente.id')
                        ->select(
                            'asignacion.id as idasignacion',
                            'asignacion.token as tokenasignacion',
                            'asignaturas.nombre as asignatura_nombre',
                            'asignaturas.codigo_asignatura as asignatura_codigo',
                            'asignaturas.codigo_asignatura_num as asignatura_codigo_num',
                            'asignaturas.color as asignatura_color',
                            'cursos.nombre as curso_nombre',
                            'cursos.nivel as curso_nivel',
                            'cursos.paralelo as curso_paralelo',

                            'docente.primer_nombre as docente_pnombre',
                            'docente.segundo_nombre as docente_snombre',
                            'docente.apellido_paterno as docente_papellido',
                            'docente.apellido_materno as docente_sapellido',
                            DB::raw("(
                                    SELECT COUNT(*) FROM matricula

                                    where
                                    matricula.curso_fk = cursos.id and
                                    matricula.periodo_fk = $periodo_activo->id

                            ) as alumnos")
                        )
                        ->where('asignacion.periodo_fk', $periodo_activo->id)
                        ->where('asignacion.curso_fk', $estudiante->id_curso)
                        ->where('docente.primer_nombre', '!=', 'null')
                        // ->where('doc.docente_fk', Auth::user()->id)
                        ->where(function ($query) use ($texto) {
                            $query->orWhere('asignaturas.nombre', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('asignaturas.codigo_asignatura', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('cursos.nombre', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('cursos.nivel', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('cursos.paralelo', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('docente.primer_nombre', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('docente.apellido_paterno', 'LIKE', '%' . $texto . '%');
                            $query->orWhere('docente.apellido_materno', 'LIKE', '%' . $texto . '%');
                        })
                    ->paginate(10);
                }else {
                    $clases = [];
                }

                return view('pages.clases.clases_estudiante.clases_estudiante', compact('clases', 'texto'));
            }

            if (Auth::user()->tipo_usuario != 2) {
                return view('notfoundpage', compact('texto'));
            }
        } else {
            return view('sinperiodo', compact('texto'));
        }
    }

    //############################## DETALLES DE CLASES
    public function viewDetailClaseEstudiante($idregistro, $tokenclase)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $clase = DB::table('cursos_asignacion_materias as relacion')
                ->leftJoin('asignaturas', 'relacion.asignatura_fk', '=', 'asignaturas.id')
                ->leftJoin('cursos', 'relacion.curso_fk', '=', 'cursos.id')
                ->select(
                    'relacion.*',
                    'asignaturas.nombre as asignatura_nombre',
                    'asignaturas.codigo_asignatura as asignatura_codigo',
                    'asignaturas.codigo_asignatura_num as asignatura_num',
                    'asignaturas.color as asignatura_color',
                    'cursos.nombre as curso_nombre',
                    'cursos.nivel as curso_nivel',
                    'cursos.paralelo as curso_paralelo'
                )

                ->where('relacion.id', $idregistro)
                ->where('relacion.token', $tokenclase)
                ->first();

            $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
            $estudiantes = DB::table('matricula')
                ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                ->select(
                    'usuario.*'
                )
                ->where('usuario.tipo_usuario', [2])
                ->where('matricula.estado', [1])
                ->where('matricula.eliminado', [0])
                ->where('usuario.estado', [1])
                ->where('usuario.eliminado', [0])
                ->where('matricula.curso_fk', $clase->curso_fk)
                ->where('matricula.periodo_fk', $periodo_activo->id)
                ->orderBy('usuario.apellido_paterno')
                ->orderBy('usuario.apellido_materno')
                ->orderBy('usuario.primer_nombre')
                ->orderBy('usuario.segundo_nombre')
                ->get();

            $actividades = DB::table('actividades')
                ->leftJoin('actividades_tipos', 'actividades.tipo_actividad_fk', '=', 'actividades_tipos.id')
                ->select(
                    'actividades.*',
                    'actividades_tipos.nombre as tipo_actividad_nombre'
                )
                ->where('actividades.estado', [1])
                ->where('actividades.eliminado', [0])
                ->orderBy('actividades.tipo_actividad_fk')
                ->orderBy('actividades.porcentaje')
                ->get();

            $quimestres = DB::table('periodolectivo_quimestre')->where('aniolectivo_fk', $periodo_activo->id)->get();

            $parciales = DB::table('periodolectivo_parcial')->where('periodolectivo_fk', $periodo_activo->id)->get();

            // dd($quimestres, $parciales);

            return view('pages.clases.clases_estudiante.detail_clases_estudiante', compact('actividades', 'clase', 'estudiantes', 'quimestres', 'parciales'));
        } else {
            return redirect()->route('viewClasesestudiante');
        }
    }

    //############################## DETALLES DE CLASES [ASISTENCIAS]
    public function estudiantesDetailClaseEstudiante($idregistro, $tokenclase, $periodo)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

            $alumn =  Auth::user()->id;

            $estudiantes = DB::table('matricula')
                ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                ->select(
                    'usuario.*'
                )
                ->where('usuario.id', $alumn)
                ->where('usuario.tipo_usuario', [2])
                ->where('usuario.estado', [1])
                ->where('usuario.eliminado', [0])
                ->where('matricula.estado', [1])
                ->where('matricula.eliminado', [0])
                ->where('matricula.curso_fk', $existe->curso_fk)
                ->where('matricula.periodo_fk', $periodo_activo->id)
                ->orderBy('usuario.apellido_paterno')
                ->orderBy('usuario.apellido_materno')
                ->orderBy('usuario.primer_nombre')
                ->orderBy('usuario.segundo_nombre')
            ->first();

            $porcion_parcial = explode("-", $periodo);
            $inasistencia = DB::table('asistencia_clase')
                ->where('estudiante_fk', Auth::user()->id)
                ->where('parcial_fk', $porcion_parcial[1])
                ->where('clase_fk', $idregistro)
                ->where('curso_fk', $existe->curso_fk)
                ->orderByDesc('fecha')
                // ->where('fecha', date('Y-m-d', strtotime($fecha)))
            ->get();

            return response()->json([200, 'estudiantes' => $estudiantes, 'inasistencia' => $inasistencia, 'alumn' => $alumn]);
        } else {
            return response()->json([500]);
        }
    }

    //############################## DETALLES DE CLASES [CALIFICACIONES]
    public function estudiantesDetailCalificacionesEstudiante($idregistro, $tokenclase, $periodo)
    {
        $quimestre_periodo = explode("-", $periodo);

        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $estado_parcial = DB::table('periodolectivo_parcial')->where('id', $quimestre_periodo[1])->first();
            if ($estado_parcial) {

                    $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

                    $ident = Auth::user()->id;
                    $estudiantes = DB::table('matricula')
                        ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                        ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                        ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                        ->select(
                            'matricula.id as ident_matricula',
                            'usuario.*'
                        )
                        ->where('usuario.id', $ident)
                        ->where('usuario.tipo_usuario', [2])
                        ->where('usuario.estado', [1])
                        ->where('usuario.eliminado', [0])
                        ->where('matricula.estado', [1])
                        ->where('matricula.eliminado', [0])
                        ->where('matricula.curso_fk', $existe->curso_fk)
                        ->where('matricula.periodo_fk', $periodo_activo->id)
                        ->orderBy('usuario.apellido_paterno')
                        ->orderBy('usuario.apellido_materno')
                        ->orderBy('usuario.primer_nombre')
                        ->orderBy('usuario.segundo_nombre')
                    ->first();

                    if ($estudiantes) {
                        $actividades = DB::table('actividades_parcial')
                            ->leftJoin('actividades', 'actividades_parcial.actividad_fk', '=', 'actividades.id')
                            ->select(
                                'actividades.abr as actividad_abr',
                                'actividades.nombre as actividad_nombre',
                                'actividades.color as actividad_color',
                                'actividades.porcentaje as actividad_porcentaje',
                                'actividades.max_calificacion as actividad_max_calificacion',
                                'actividades_parcial.*'
                            )
                            ->where('actividades_parcial.clase_fk', $idregistro)
                            ->where('actividades_parcial.curso_fk', $existe->curso_fk)
                            ->where('actividades_parcial.parcial_fk', $quimestre_periodo[1])
                            ->where('actividades_parcial.estado', [1])
                            ->where('actividades_parcial.eliminado', [0])
                            ->orderBy('actividades.id')
                            ->orderBy('actividades_parcial.created_at')
                        ->get();

                        $calificaciones = DB::table('actividades_calificaciones_matriculados')
                            ->where('actividades_calificaciones_matriculados.matriculado_fk', $estudiantes->ident_matricula)
                            ->where('actividades_calificaciones_matriculados.clase_fk', $idregistro)
                            ->where('actividades_calificaciones_matriculados.curso_fk', $existe->curso_fk)
                            ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
                        ->get();
                        $calificacion_parcial = DB::table('calificaciones_parcial_matriculados')
                            ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                            ->select(
                                'calificaciones_parcial_matriculados.*',
                                'periodolectivo_parcial.*'
                            )
                            ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiantes->ident_matricula)
                            ->where('calificaciones_parcial_matriculados.clase_fk', $idregistro)
                            ->where('calificaciones_parcial_matriculados.curso_fk', $existe->curso_fk)
                            ->where('periodolectivo_parcial.quimestre_fk', $quimestre_periodo[0])
                        ->first();
                    } else {
                        $actividades = [];
                        $calificaciones = [];
                        $calificacion_parcial = null;
                    }


                    return response()->json([200, 'ident' => $ident, 'estudiantes' => $estudiantes, 'actividades' => $actividades, 'calificaciones' => $calificaciones, 'calificacion_parcial' => $calificacion_parcial]);
            } else {
                return response()->json([500]);
            }
        } else {
            return response()->json([500]);
        }
    }

    public function estudiantesDetailCalificacionesQuimestreEstudiante($idregistro, $tokenclase, $periodo)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $quimestre_periodo = explode("-", $periodo);
            $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();
            $quimestres = DB::table('periodolectivo_quimestre')->where('id', $quimestre_periodo[0])->first();

            if ($quimestres) {
                $parciales = DB::table('periodolectivo_parcial')->where('quimestre_fk', $quimestre_periodo[0])->get();

                $ident =  Auth::user()->id;
                $estudiantes = DB::table('matricula')
                    ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                    ->select(
                        'matricula.id as ident_matricula',
                        'usuario.*'
                    )
                    ->where('usuario.id', $ident)
                    ->where('usuario.tipo_usuario', [2])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    ->where('matricula.curso_fk', $existe->curso_fk)
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                ->get();

                $calificaciones = DB::table('calificaciones_parcial_matriculados')
                    ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->select(
                        'calificaciones_parcial_matriculados.*',
                        'periodolectivo_parcial.*'
                    )
                    ->where('calificaciones_parcial_matriculados.clase_fk', $idregistro)
                    ->where('calificaciones_parcial_matriculados.curso_fk', $existe->curso_fk)
                    ->where('periodolectivo_parcial.quimestre_fk', $quimestre_periodo[0])
                ->get();

                $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                    ->select(
                        'calificaciones_examen_quimestral_matriculados.*',
                    )
                    ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                    ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                    ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $idregistro)
                    ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $existe->curso_fk)
                    ->where('calificaciones_examen_quimestral_matriculados.quimestre_fk', $quimestre_periodo[0])
                ->get();

                return response()->json([
                    200,
                    'ident' => $ident,
                    'quimestres' => $quimestres,
                    'parciales' => $parciales,
                    'calificaciones' => $calificaciones,
                    'calificaciones_examen' => $calificaciones_examen,
                    'estudiantes' => $estudiantes
                ]);
            } else {
                return response()->json([500]);
            }
        } else {
            return response()->json([500]);
        }
    }

    //############################## DETALLES DE CLASES [REPORTES]
    public function generarReporteCalificacionesPDFAll($clase_fk, $token)
    {
        $clase = DB::table('cursos_asignacion_materias')
            ->leftJoin('asignaturas', 'cursos_asignacion_materias.asignatura_fk', '=', 'asignaturas.id')
            ->select(
                'cursos_asignacion_materias.*',
                'asignaturas.nombre as nombre_asignatura'
            )
            ->where('cursos_asignacion_materias.id', $clase_fk)
            ->where('cursos_asignacion_materias.token', $token)
            ->first();

        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        $quimestre = DB::table('periodolectivo_quimestre')
            ->select(
                DB::raw("(
                    SELECT COUNT(*) FROM actividades_parcial
                    LEFT join periodolectivo_parcial on actividades_parcial.parcial_fk = periodolectivo_parcial.id

                    where
                    periodolectivo_parcial.quimestre_fk = periodolectivo_quimestre.id and
                    actividades_parcial.clase_fk = $clase->id and
                    actividades_parcial.estado = 1 and
                    actividades_parcial.eliminado = 0
                ) as actividades_x_quimestre"),
                "periodolectivo_quimestre.*"
            )
            ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
            ->get();

        $parciales = DB::table('periodolectivo_parcial')
            ->select(
                DB::raw("(
                    SELECT COUNT(*) FROM actividades_parcial
                    where
                    actividades_parcial.parcial_fk = periodolectivo_parcial.id and
                    actividades_parcial.clase_fk = $clase->id and
                    actividades_parcial.estado = 1 and
                    actividades_parcial.eliminado = 0
                ) as actividades_x_parcial"),
                "periodolectivo_parcial.*"
            )
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->get();

        // select * from periodolectivo_parcial where periodolectivo_parcial.periodolectivo_fk = 1

        $estudiantes = DB::table('matricula')
            ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
            ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
            ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
            ->select(
                'matricula.id as ident_matricula',
                'usuario.*'
            )
            ->where('usuario.tipo_usuario', [2])
            ->where('usuario.estado', [1])
            ->where('usuario.eliminado', [0])
            ->where('matricula.estado', [1])
            ->where('matricula.eliminado', [0])
            ->where('matricula.curso_fk', $clase->curso_fk)
            ->where('matricula.periodo_fk', $periodo_activo->id)
            ->orderBy('usuario.apellido_paterno')
            ->orderBy('usuario.apellido_materno')
            ->orderBy('usuario.primer_nombre')
            ->orderBy('usuario.segundo_nombre')
            ->get();

        $calificaciones = DB::table('actividades_calificaciones_matriculados')
            ->leftJoin('periodolectivo_parcial', 'actividades_calificaciones_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
            ->select(
                'actividades_calificaciones_matriculados.*'
            )
            ->where('actividades_calificaciones_matriculados.clase_fk', $clase->id)
            ->where('actividades_calificaciones_matriculados.curso_fk', $clase->curso_fk)
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->where('actividades_calificaciones_matriculados.estado', [1])
            ->where('actividades_calificaciones_matriculados.eliminado', [0])
            // ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
            // ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
            ->get();

        $actividades = DB::table('actividades_parcial')
            ->leftJoin('actividades', 'actividades_parcial.actividad_fk', '=', 'actividades.id')
            ->leftJoin('periodolectivo_parcial', 'actividades_parcial.parcial_fk', '=', 'periodolectivo_parcial.id')
            ->select(
                'actividades.nombre as actividad_nombre',
                'actividades.color as actividad_color',
                'actividades.porcentaje as actividad_porcentaje',
                'actividades.max_calificacion as actividad_max_calificacion',
                'actividades_parcial.*'
            )
            ->where('actividades_parcial.clase_fk', $clase->id)
            ->where('actividades_parcial.curso_fk', $clase->curso_fk)
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->where('actividades_parcial.estado', [1])
            ->where('actividades_parcial.eliminado', [0])
            ->get();

        $asignatura = DB::table('cursos_asignacion_materias as asign')
            ->leftJoin('asignaturas', 'asign.asignatura_fk', '=', 'asignaturas.id')
            ->leftJoin('cursos', 'asign.curso_fk', '=', 'cursos.id')
            ->select(
                'asignaturas.nombre as asignatura_nombre',
                'cursos.id as curso_fk',
                'cursos.nombre as curso_nombre',
                'cursos.nivel as curso_nivel',
                'cursos.paralelo as curso_paralelo'
            )
            ->where('asign.id', $clase_fk)
            ->where('asign.periodo_fk', $periodo_activo->id)
            ->first();


        $calificaciones = DB::table('calificaciones_parcial_matriculados')
            ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
            ->select(
                'calificaciones_parcial_matriculados.*',
                'periodolectivo_parcial.*'
            )
            ->where('calificaciones_parcial_matriculados.clase_fk', $clase->id)
            ->where('calificaciones_parcial_matriculados.curso_fk', $clase->curso_fk)
            // ->where('periodolectivo_parcial.quimestre_fk', $quimestre_periodo[0])
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->get();

        $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
            ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
            ->select(
                'calificaciones_examen_quimestral_matriculados.*',
            )
            ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
            ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
            ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $clase->id)
            ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $clase->curso_fk)
            ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
            ->get();


        $calificaciones_quimestre = DB::table('calificaciones_quimestre_matriculados')
            ->leftJoin('periodolectivo_quimestre', 'calificaciones_quimestre_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
            ->select(
                'calificaciones_quimestre_matriculados.*',
            )
            ->where('calificaciones_quimestre_matriculados.estado', [1])
            ->where('calificaciones_quimestre_matriculados.eliminado', [0])
            ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
            ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
            ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
            ->get();

        // dd($quimestre, $periodo_activo, $parciales, $estudiantes, $asignatura, $calificaciones, $calificaciones_examen, $calificaciones_quimestre);
        $nombre_pdf = "CALIFICACIONES - $clase->nombre_asignatura";
        $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones', compact(
            'clase',
            'quimestre',
            'actividades',
            'periodo_activo',
            'parciales',
            'estudiantes',
            'calificaciones',
            'calificaciones_examen',
            'calificaciones_quimestre',
            'nombre_pdf',
            'asignatura'
        ));

        // return $pdf->stream("$nombre - ASIGNATURAS.pdf");
        return $pdf->stream("$nombre_pdf.pdf");
    }

    public function generarReporteCalificacionesActividadesEstudiante($idregistro, $tokenclase, $periodo)
    {
        $quimestre_periodo = explode("-", $periodo);

        $curso = DB::table('cursos_asignacion_materias')
            ->leftJoin('asignaturas', 'cursos_asignacion_materias.asignatura_fk', '=', 'asignaturas.id')
            ->leftJoin('cursos', 'cursos_asignacion_materias.curso_fk', '=', 'cursos.id')
            ->select(
                'cursos_asignacion_materias.*',
                'cursos.nombre as curso_nombre',
                'cursos.nivel as curso_nivel',
                'cursos.paralelo as curso_paralelo',
                'asignaturas.nombre as nombre_asignatura'
            )
        ->where('cursos_asignacion_materias.id', $idregistro)->where('cursos_asignacion_materias.token', $tokenclase)->first();
        if ($curso) {
            $estado_parcial = DB::table('periodolectivo_parcial')->where('id', $quimestre_periodo[1])->first();
            if ($estado_parcial) {
                    $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

                    $estudiantes = DB::table('matricula')
                        ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                        ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                        ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                        ->select(
                            'matricula.id as ident_matricula',
                            'usuario.*'
                        )
                        ->where('usuario.id', Auth::user()->id)
                        ->where('usuario.tipo_usuario', [2])
                        ->where('usuario.estado', [1])
                        ->where('usuario.eliminado', [0])
                        ->where('matricula.estado', [1])
                        ->where('matricula.eliminado', [0])
                        ->where('matricula.curso_fk', $curso->curso_fk)
                        ->where('matricula.periodo_fk', $periodo_activo->id)
                        ->orderBy('usuario.apellido_paterno')
                        ->orderBy('usuario.apellido_materno')
                        ->orderBy('usuario.primer_nombre')
                        ->orderBy('usuario.segundo_nombre')
                    ->first();

                    if ($estudiantes) {
                        $actividades = DB::table('actividades_parcial')
                            ->leftJoin('actividades', 'actividades_parcial.actividad_fk', '=', 'actividades.id')
                            ->select(
                                'actividades.abr as actividad_abr',
                                'actividades.nombre as actividad_nombre',
                                'actividades.color as actividad_color',
                                'actividades.porcentaje as actividad_porcentaje',
                                'actividades.max_calificacion as actividad_max_calificacion',
                                'actividades_parcial.*'
                            )
                            ->where('actividades_parcial.clase_fk', $idregistro)
                            ->where('actividades_parcial.curso_fk', $curso->curso_fk)
                            ->where('actividades_parcial.parcial_fk', $quimestre_periodo[1])
                            ->where('actividades_parcial.estado', [1])
                            ->where('actividades_parcial.eliminado', [0])
                            ->orderBy('actividades.id')
                            ->orderBy('actividades_parcial.created_at')
                        ->get();

                        $calificaciones = DB::table('actividades_calificaciones_matriculados')
                            ->where('actividades_calificaciones_matriculados.matriculado_fk', $estudiantes->ident_matricula)
                            ->where('actividades_calificaciones_matriculados.clase_fk', $idregistro)
                            ->where('actividades_calificaciones_matriculados.curso_fk', $curso->curso_fk)
                            ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
                        ->get();
                        $calificacion_parcial = DB::table('calificaciones_parcial_matriculados')
                            ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                            ->select(
                                'calificaciones_parcial_matriculados.*',
                                'periodolectivo_parcial.*'
                            )
                            ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiantes->ident_matricula)
                            ->where('calificaciones_parcial_matriculados.clase_fk', $idregistro)
                            ->where('calificaciones_parcial_matriculados.curso_fk', $curso->curso_fk)
                            ->where('periodolectivo_parcial.quimestre_fk', $quimestre_periodo[0])
                        ->first();
                    } else {
                        $actividades = [];
                        $calificaciones = [];
                        $calificacion_parcial = null;
                    }

                    $nombrepdf = "CALIFICACIONES - $curso->nombre_asignatura";
                    $pdf = PDF::loadView('pages.reportes.clases.estudiante.PDF-clases_calificacion_actividades_individual', compact(
                        'nombrepdf',
                        'curso',
                        'estudiantes',
                        'actividades',
                        'calificaciones',
                        'calificacion_parcial'
                    ));

                    // return $pdf->stream("$nombre - ASIGNATURAS.pdf");
                    return $pdf->stream("$nombrepdf.pdf");

            } else {
                return response()->json([500]);
            }
        } else {
            return view('notfound');
        }


    }
}

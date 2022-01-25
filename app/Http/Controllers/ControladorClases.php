<?php

namespace App\Http\Controllers;

use App\User;
use App\System;
use \PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorClases extends Controller
{
    protected $date_actual;
    public function __construct()
    {
        $this->date_actual = date('Y-m-d H:i:s');
    }

    public function viewClases(Request $request)
    {
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        $texto = trim($request->get('texto'));
        if ($periodo_activo) {
            if (Auth::user()->tipo_usuario == 5) {
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
                    ->where('docente.primer_nombre', '!=', 'null')
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
                return view('pages.clases.clases.clases', compact('clases', 'texto'));
            }

            if (Auth::user()->tipo_usuario == 3) {
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
                    ->where('doc.docente_fk', Auth::user()->id)
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
                return view('pages.clases.clases.clases', compact('clases', 'texto'));
            }

            if (Auth::user()->tipo_usuario != 3 || Auth::user()->tipo_usuario != 5) {
                return view('notfoundpage', compact('texto'));
            }
        } else {
            return view('sinperiodo', compact('texto'));
        }
    }


    //############################## DETALLES DE CLASES
    public function viewDetailClase($idregistro, $tokenclase)
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

            return view('pages.clases.clases.detail_clases', compact('actividades', 'clase', 'estudiantes', 'quimestres', 'parciales'));
        } else {
            return redirect()->route('viewClases');
        }
    }

    //############################## DETALLES DE CLASES [ASISTENCIAS]
    public function estudiantesDetailClase($idregistro, $tokenclase, $periodo, $fecha)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

            $estudiantes = DB::table('matricula')
                ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                ->select(
                    'usuario.*'
                )
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

            $porcion_parcial = explode("-", $periodo);
            $inasistencia = DB::table('asistencia_clase')
                ->where('parcial_fk', $porcion_parcial[1])
                ->where('clase_fk', $idregistro)
                ->where('curso_fk', $existe->curso_fk)
                ->where('fecha', date('Y-m-d', strtotime($fecha)))
                ->get();

            return response()->json([200, 'estudiantes' => $estudiantes, 'inasistencia' => $inasistencia]);
        } else {
            return response()->json([500]);
        }
    }
    public function claseAsistenciasPost(Request $request)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $request->clase_fk)->where('token', $request->tokenclase_fk)->first();
        if ($existe) {
            $estudiantes = json_decode($request->estudiantes);
            if (sizeof($estudiantes) > 0) {
                DB::beginTransaction();
                $porcion_parcial = explode("-", $request->parcial);
                // DB::table('asistencia_clase')->where('parcial_fk',$porcion_parcial[1])->where('clase_fk', $request->clase_fk)->where('curso_fk', $existe->curso_fk)->where('fecha', $request->fecha)->delete();
                $cont = 0;
                for ($i = 0; $i < sizeof($estudiantes); $i++) {
                    DB::table('asistencia_clase')->insert([
                        'estudiante_fk' => $estudiantes[$i]->id_estudiante,
                        'parcial_fk' => $porcion_parcial[1],
                        'clase_fk' => $request->clase_fk,
                        'curso_fk' => $existe->curso_fk,
                        'fecha' => $request->fecha,
                        'asistencia' => $estudiantes[$i]->asistencia,
                        'token' => Str::random(40)
                    ]);
                    $cont++;
                }
                if ($cont == sizeof($estudiantes)) {
                    DB::commit();
                    $fecha_m = date('d/m/Y', strtotime($request->fecha));
                    return response()->json(['success' => "Asistencia modificada con éxito para el día $fecha_m"]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            } else {
                return response()->json(['warning' => 'No se encontraron estudiantes.']);
            }
        } else {
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }
    public function claseJustificarAsistencia($idregistro)
    {
        DB::beginTransaction();
        $success = DB::table('asistencia_clase')->where('id', $idregistro)->update([
            'asistencia_justificada' => 1
        ]);
        if ($success) {
            DB::commit();
            return response()->json(['success' => 'Falta justificada con éxito.']);
        } else {
            DB::rollBack();
            return response()->json(['error' => 'No se pudo modificar, vuelva a intentarlo.']);
        }
    }

    //############################## DETALLES DE CLASES [CALIFICACIONES]
    public function estudiantesDetailCalificaciones($idregistro, $tokenclase, $periodo)
    {
        $quimestre_periodo = explode("-", $periodo);

        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $estado_parcial = DB::table('periodolectivo_parcial')->where('id', $quimestre_periodo[1])->first();
            if ($estado_parcial) {
                if ($estado_parcial->activo == 1) {
                    $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

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
                        ->where('matricula.curso_fk', $existe->curso_fk)
                        ->where('matricula.periodo_fk', $periodo_activo->id)
                        ->orderBy('usuario.apellido_paterno')
                        ->orderBy('usuario.apellido_materno')
                        ->orderBy('usuario.primer_nombre')
                        ->orderBy('usuario.segundo_nombre')
                        ->get();

                    $actividades = DB::table('actividades_parcial')
                        ->leftJoin('actividades', 'actividades_parcial.actividad_fk', '=', 'actividades.id')
                        ->select(
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
                        ->where('actividades_calificaciones_matriculados.clase_fk', $idregistro)
                        ->where('actividades_calificaciones_matriculados.curso_fk', $existe->curso_fk)
                        ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
                        ->get();
                    // $porcion_parcial = explode("-", $periodo);
                    // return response()->json([200, 'estudiantes' => $estudiantes]);
                    return response()->json([200, 'estudiantes' => $estudiantes, 'actividades' => $actividades, 'calificaciones' => $calificaciones]);
                } else {
                    return response()->json([500]);
                }
            } else {
                return response()->json([500]);
            }
        } else {
            return response()->json([500]);
        }
    }

    public function estudiantesDetailCalificacionesQuimestre($idregistro, $tokenclase, $periodo)
    {
        $existe = DB::table('cursos_asignacion_materias')->where('id', $idregistro)->where('token', $tokenclase)->first();
        if ($existe) {
            $quimestre_periodo = explode("-", $periodo);
            $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();
            $quimestres = DB::table('periodolectivo_quimestre')->where('id', $quimestre_periodo[0])->first();

            if ($quimestres) {
                if ($quimestres->activo == 1) {
                    $parciales = DB::table('periodolectivo_parcial')->where('quimestre_fk', $quimestre_periodo[0])->get();

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
        } else {
            return response()->json([500]);
        }
    }

    public function crearActividadParcial(Request $request)
    {
        $tipo_actividad = $request->tipo_actividad;
        $nombre_actividad = $request->nombre_actividad;
        $descripcion_actividad = $request->descripcion_actividad;
        $fecha_actividad = $request->fecha_actividad;
        $idClass = $request->idasignacion;
        $tokenasignacion = $request->tokenasignacion;
        $periodo = explode("-", $request->periodo);

        $existe = DB::table('cursos_asignacion_materias')->where('id', $idClass)->where('token', $tokenasignacion)->first();
        $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

        // DB::beginTransaction();

        $actividad = DB::table('actividades_parcial')->insertGetId([
            'actividad_fk' => $tipo_actividad,
            // 'quimestre_fk' => $periodo[0],
            'parcial_fk' => $periodo[1],
            'clase_fk' => $idClass,
            'curso_fk' => $existe->curso_fk,

            'nombre' => $nombre_actividad,
            'slug' => Str::slug($nombre_actividad),
            'descripcion' => $descripcion_actividad,

            'token' => Str::random(40),

            'fecha' => date('Y-m-d', strtotime($this->date_actual)),
            'hora' => date('H:i:s', strtotime($this->date_actual)),

            'fecha_actividad' => $fecha_actividad,
            'created_at' => $this->date_actual,
            'updated_at' => $this->date_actual,
            'creador_fk' => Auth::user()->id
        ]);

        $estudiantes = DB::table('matricula')
            ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
            ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
            ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
            ->select(
                'matricula.id as ident_matricula',
                'usuario.*'
            )
            ->where('usuario.tipo_usuario', [2])
            ->where('matricula.curso_fk', $existe->curso_fk)
            ->where('matricula.periodo_fk', $periodo_activo->id)
            ->orderBy('usuario.apellido_paterno')
            ->orderBy('usuario.apellido_materno')
            ->orderBy('usuario.primer_nombre')
            ->orderBy('usuario.segundo_nombre')
            ->get();

        foreach ($estudiantes as $matriculado) {
            DB::table('actividades_calificaciones_matriculados')->insertGetId([
                'parcial_fk' => $periodo[1],
                'clase_fk' => $idClass,
                'curso_fk' => $existe->curso_fk,

                'matriculado_fk' => $matriculado->ident_matricula,
                'actividad_fk' => $actividad,
                'created_at' => $this->date_actual,
                'updated_at' => $this->date_actual,
                'creador_fk' => Auth::user()->id
            ]);

            $quimestres = DB::table('periodolectivo_quimestre')->where('aniolectivo_fk', $periodo_activo->id)->get();

            foreach ($quimestres as $datos_quimestre) {
                $parciales = DB::table('periodolectivo_parcial')->where('quimestre_fk', $datos_quimestre->id)->get();

                foreach ($parciales as $datos_parciales) {
                    $verificacion_calificacion_parcial = DB::table('calificaciones_parcial_matriculados')
                        ->where('estado', 1)
                        ->where('eliminado', 0)
                        ->where('parcial_fk', $datos_parciales->id)
                        ->where('clase_fk', $idClass)
                        ->where('curso_fk', $existe->curso_fk)
                        ->where('matriculado_fk', $matriculado->ident_matricula)
                        ->first();

                    if ($verificacion_calificacion_parcial == null) {
                        DB::table('calificaciones_parcial_matriculados')->insertGetId([
                            'parcial_fk' => $datos_parciales->id,
                            'clase_fk' => $idClass,
                            'curso_fk' => $existe->curso_fk,

                            'matriculado_fk' => $matriculado->ident_matricula,
                            'created_at' => $this->date_actual,
                            'updated_at' => $this->date_actual,
                            'creador_fk' => Auth::user()->id
                        ]);
                    }
                }

                $verificacion_calificacion_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                    ->where('estado', 1)
                    ->where('eliminado', 0)
                    ->where('quimestre_fk', $datos_quimestre->id)
                    ->where('clase_fk', $idClass)
                    ->where('curso_fk', $existe->curso_fk)
                    ->where('matriculado_fk', $matriculado->ident_matricula)
                    ->first();

                $verificacion_calificacion_final = DB::table('calificaciones_quimestre_matriculados')
                    ->where('estado', 1)
                    ->where('eliminado', 0)
                    ->where('quimestre_fk', $datos_quimestre->id)
                    ->where('clase_fk', $idClass)
                    ->where('curso_fk', $existe->curso_fk)
                    ->where('matriculado_fk', $matriculado->ident_matricula)
                    ->first();

                if ($verificacion_calificacion_examen == null) {
                    DB::table('calificaciones_examen_quimestral_matriculados')->insertGetId([
                        'quimestre_fk' => $datos_quimestre->id,
                        'clase_fk' => $idClass,
                        'curso_fk' => $existe->curso_fk,

                        'matriculado_fk' => $matriculado->ident_matricula,
                        'created_at' => $this->date_actual,
                        'updated_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);
                }

                if ($verificacion_calificacion_final == null) {
                    DB::table('calificaciones_quimestre_matriculados')->insertGetId([
                        'quimestre_fk' => $datos_quimestre->id,
                        'clase_fk' => $idClass,
                        'curso_fk' => $existe->curso_fk,

                        'matriculado_fk' => $matriculado->ident_matricula,
                        'created_at' => $this->date_actual,
                        'updated_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);
                }
            }
        }

        $vairable2 = DB::table('actividades_parcial')->where('id', $actividad)->first();

        return response()->json([
            'vairable2' => $vairable2,
            'success' => 'Actividad creada con éxito.'
        ]);
    }
    public function eliminarActividadParcial(Request $request)
    {
        $existe = DB::table('actividades_parcial')->where('id', $request->identificador)->first();

        if ($existe) {
            if ($existe->estado == 1 && $existe->eliminado == 0) {
                DB::beginTransaction();

                $success_actividad = DB::table('actividades_parcial')->where('id', $request->identificador)->update([
                    'estado' => 0,
                    'eliminado' => 1
                ]);

                DB::table('actividades_calificaciones_matriculados')->where('actividad_fk', $request->identificador)->update([
                    'estado' => 0,
                    'eliminado' => 1
                ]);

                if ($success_actividad) {
                    DB::commit();
                    return response()->json(['success' => "Actividad eliminada con éxito."]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            } else {
                return response()->json(['error' => 'La actividad ya ha sido eliminada.']);
            }
        } else {
            return response()->json(['error' => 'No se encontro la actividad.']);
        }
    }
    public function actualizarCalificacionActividadXMatriculadoParcial(Request $request)
    {
        $existe = DB::table('actividades_calificaciones_matriculados')
            ->where('matriculado_fk', $request->ident_est)
            ->where('actividad_fk', $request->ident_act)
            ->first();

        if ($existe) {
            if ($existe->estado == 1 && $existe->eliminado == 0) {
                DB::beginTransaction();

                $idClass = $request->idasignacion;
                $tokenasignacion = $request->tokenasignacion;
                $asignacion = DB::table('cursos_asignacion_materias')->where('id', $idClass)->where('token', $tokenasignacion)->first();

                $periodo = explode("-", $request->periodo);

                $success_calificacion = DB::table('actividades_calificaciones_matriculados')
                    ->where('matriculado_fk', $request->ident_est)
                    ->where('actividad_fk', $request->ident_act)
                    ->where('clase_fk', $idClass)
                    ->where('curso_fk', $asignacion->curso_fk)
                    ->update([
                        'calificacion' => number_format($request->calificacion, 2),
                        'calificado' => 1
                    ]);

                if ($success_calificacion) {

                    $success_calificacion_final = DB::table('calificaciones_parcial_matriculados')
                        ->where('matriculado_fk', $request->ident_est)
                        ->where('clase_fk', $idClass)
                        ->where('curso_fk', $asignacion->curso_fk)
                        ->where('parcial_fk', $periodo[1])->update([
                            'calificacion' => number_format($request->calificacion_final, 2)
                            // 'calificado' => 1
                        ]);

                    $calificaciones = DB::table('calificaciones_parcial_matriculados')
                        ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                        ->select(
                            'calificaciones_parcial_matriculados.*',
                            'periodolectivo_parcial.*'
                        )
                        ->where('calificaciones_parcial_matriculados.matriculado_fk', $request->ident_est)
                        ->where('calificaciones_parcial_matriculados.clase_fk', $idClass)
                        ->where('calificaciones_parcial_matriculados.curso_fk', $asignacion->curso_fk)
                        ->where('periodolectivo_parcial.quimestre_fk', $periodo[0])
                        ->sum("calificacion");

                    $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                        ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                        ->select(
                            'calificaciones_examen_quimestral_matriculados.*',
                        )
                        ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $request->ident_est)
                        ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                        ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                        ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $idClass)
                        ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $asignacion->curso_fk)
                        ->where('periodolectivo_quimestre.id', $periodo[0])
                        ->first();

                    $parciales = ($calificaciones / 2) * 8 / 10;
                    $examen = ($calificaciones_examen->calificacion) * 2 / 10;

                    $total_total = $parciales + $examen;

                    $success_calificacion_total = DB::table('calificaciones_quimestre_matriculados')
                        ->where('calificaciones_quimestre_matriculados.matriculado_fk', $request->ident_est)
                        ->where('calificaciones_quimestre_matriculados.estado', [1])
                        ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                        ->where('calificaciones_quimestre_matriculados.clase_fk', $idClass)
                        ->where('calificaciones_quimestre_matriculados.curso_fk', $asignacion->curso_fk)
                        ->where('calificaciones_quimestre_matriculados.quimestre_fk', $periodo[0])
                        ->update([
                            'calificacion' => number_format($total_total, 2),
                            'calificado' => 1
                        ]);

                    DB::commit();
                    return response()->json(['success' => "Actividad calificada con éxito."]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            } else {
                return response()->json(['error' => 'La calificacion ya ha sido eliminada.']);
            }
        } else {
            return response()->json(['error' => 'No se encontro la calificacion.']);
        }
    }
    public function crearExamenQuimestral(Request $request)
    {
        $nombre_examen_quimestral = $request->nombre_examen_quimestral;
        $descripcion_examen_quimestral = $request->descripcion_examen_quimestral;
        $fecha_examen_quimestral = $request->fecha_examen_quimestral;
        $idClass = $request->idasignacion;
        $tokenasignacion = $request->tokenasignacion;
        $periodo = explode("-", $request->periodo);

        $existe = DB::table('cursos_asignacion_materias')->where('id', $idClass)->where('token', $tokenasignacion)->first();
        $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

        // DB::beginTransaction();

        $verificacion_Examen = DB::table('calificaciones_examen_quimestral_matriculados')
            ->select(
                'calificaciones_examen_quimestral_matriculados.*',
            )
            ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
            ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
            ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $idClass)
            ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $existe->curso_fk)
            ->where('calificaciones_examen_quimestral_matriculados.quimestre_fk', $periodo[0])
            ->get();

        if ($existe) {
            if (sizeof($verificacion_Examen) == 0) {
                $estudiantes = DB::table('matricula')
                    ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                    ->select(
                        'matricula.id as ident_matricula',
                        'usuario.*'
                    )
                    ->where('usuario.tipo_usuario', [2])
                    ->where('matricula.curso_fk', $existe->curso_fk)
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                    ->get();

                $array_cosas = array();
                foreach ($estudiantes as $matriculado) {
                    $verificacion_calificacion_quimestral = DB::table('calificaciones_examen_quimestral_matriculados')
                        ->where('estado', 1)
                        ->where('eliminado', 0)
                        ->where('quimestre_fk', $periodo[0])
                        ->where('clase_fk', $idClass)
                        ->where('curso_fk', $existe->curso_fk)
                        ->where('matriculado_fk', $matriculado->ident_matricula)
                        ->first();

                    $verificacion_calificacion_final = DB::table('calificaciones_quimestre_matriculados')
                        ->where('estado', 1)
                        ->where('eliminado', 0)
                        ->where('quimestre_fk', $periodo[0])
                        ->where('clase_fk', $idClass)
                        ->where('curso_fk', $existe->curso_fk)
                        ->where('matriculado_fk', $matriculado->ident_matricula)
                        ->first();


                    if ($verificacion_calificacion_quimestral == null) {
                        DB::table('calificaciones_examen_quimestral_matriculados')->insertGetId([
                            'quimestre_fk' => $periodo[0],
                            'clase_fk' => $idClass,
                            'curso_fk' => $existe->curso_fk,

                            'matriculado_fk' => $matriculado->ident_matricula,
                            'created_at' => $this->date_actual,
                            'updated_at' => $this->date_actual,
                            'creador_fk' => Auth::user()->id
                        ]);
                    }

                    if ($verificacion_calificacion_final == null) {
                        DB::table('calificaciones_quimestre_matriculados')->insertGetId([
                            'quimestre_fk' => $periodo[0],
                            'clase_fk' => $idClass,
                            'curso_fk' => $existe->curso_fk,

                            'matriculado_fk' => $matriculado->ident_matricula,
                            'created_at' => $this->date_actual,
                            'updated_at' => $this->date_actual,
                            'creador_fk' => Auth::user()->id
                        ]);
                    }
                }

                return response()->json([
                    "success" => 'Examen creado con éxito',
                    'existe' => $existe,
                    'periodo_activo' => $periodo_activo
                ]);
            } else {
                return response()->json(["error" => "Ya existe un examen creado para este quimestre"]);
            }
        } else {
            return response()->json(["error" => "Error"]);
        }
    }
    public function actualizarCalificacionExamenQuimestre(Request $request)
    {
        $existe = DB::table('calificaciones_examen_quimestral_matriculados')
            ->where('matriculado_fk', $request->ident_est)
            ->where('id', $request->ident_quim)
            ->first();

        if ($existe) {
            if ($existe->estado == 1 && $existe->eliminado == 0) {
                // DB::beginTransaction();

                $idClass = $request->idasignacion;
                $tokenasignacion = $request->tokenasignacion;
                $asignacion = DB::table('cursos_asignacion_materias')->where('id', $idClass)->where('token', $tokenasignacion)->first();

                $success_calificacion = DB::table('calificaciones_examen_quimestral_matriculados')
                    ->where('matriculado_fk', $request->ident_est)
                    ->where('id', $request->ident_quim)
                    ->where('clase_fk', $idClass)
                    ->where('curso_fk', $asignacion->curso_fk)
                    ->update([
                        'calificacion' => number_format($request->calificacion, 2),
                        'calificado' => 1
                    ]);

                if ($success_calificacion) {
                    $periodo = explode("-", $request->periodo);

                    // $periodo_activo = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', 0)->first();

                    $success_calificacion_final = DB::table('calificaciones_quimestre_matriculados')
                        ->where('matriculado_fk', $request->ident_est)
                        ->where('clase_fk', $idClass)
                        ->where('curso_fk', $asignacion->curso_fk)
                        ->where('quimestre_fk', $periodo[0])->update([
                            'calificacion' => number_format($request->calificacion_final, 2)
                            // 'calificado' => 1
                        ]);

                    DB::commit();
                    return response()->json(['success' => "Actividad calificada con éxito."]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            } else {
                return response()->json(['error' => 'La calificacion ya ha sido eliminada.']);
            }
        } else {
            return response()->json(['error' => 'No se encontro la calificacion.']);
        }
    }
    public function eliminarExamenQuimestral(Request $request)
    {
        $existe = DB::table('periodolectivo_quimestre')->where('id', $request->identificador)->first();

        if ($existe) {
            DB::beginTransaction();

            $success_quimestre = DB::table('calificaciones_examen_quimestral_matriculados')->where('quimestre_fk', $request->identificador)->update([
                'estado' => 0,
                'eliminado' => 1
            ]);

            if ($success_quimestre) {
                DB::commit();
                return response()->json(['success' => "Examen eliminado con éxito."]);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        } else {
        }
    }
    public function actualizarCalificacionGeneral(Request $request)
    {
        $datos = json_decode($request->datos);
        $idClass = $request->idasignacion;
        $tokenasignacion = $request->tokenasignacion;

        $asignacion = DB::table('cursos_asignacion_materias')->where('id', $idClass)->where('token', $tokenasignacion)->first();
        $periodo = explode("-", $request->periodo);
        DB::beginTransaction();

        foreach ($datos as $key) {
            DB::table('calificaciones_parcial_matriculados')
                ->where('matriculado_fk', $key->matriculado)
                ->where('clase_fk', $idClass)
                ->where('curso_fk', $asignacion->curso_fk)
                ->where('parcial_fk', $periodo[1])->update([
                    'calificacion' => number_format($key->sumatoria, 2),
                    'calificado' => 1
                ]);

            $calificaciones = DB::table('calificaciones_parcial_matriculados')
                ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                ->select(
                    'calificaciones_parcial_matriculados.*',
                    'periodolectivo_parcial.*'
                )
                ->where('calificaciones_parcial_matriculados.matriculado_fk', $key->matriculado)
                ->where('calificaciones_parcial_matriculados.clase_fk', $idClass)
                ->where('calificaciones_parcial_matriculados.curso_fk', $asignacion->curso_fk)
                ->where('periodolectivo_parcial.quimestre_fk', $periodo[0])
                ->sum("calificacion");

            $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                ->select(
                    'calificaciones_examen_quimestral_matriculados.*'
                )
                ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $key->matriculado)
                ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $idClass)
                ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $asignacion->curso_fk)
                ->where('periodolectivo_quimestre.id', $periodo[0])
                ->first();

            $parciales = ($calificaciones / 2) * 8 / 10;
            $examen = ($calificaciones_examen->calificacion) * 2 / 10;

            $total_total = $parciales + $examen;

            $success_calificacion_total = DB::table('calificaciones_quimestre_matriculados')
                ->where('calificaciones_quimestre_matriculados.matriculado_fk', $key->matriculado)
                ->where('calificaciones_quimestre_matriculados.estado', [1])
                ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                ->where('calificaciones_quimestre_matriculados.clase_fk', $idClass)
                ->where('calificaciones_quimestre_matriculados.curso_fk', $asignacion->curso_fk)
                ->where('calificaciones_quimestre_matriculados.quimestre_fk', $periodo[0])
                ->update([
                    'calificacion' => number_format($total_total, 2),
                    'calificado' => 1
                ]);
        }

        if (sizeof($datos) > 0) {
            DB::commit();
            return response()->json(['success' => "Correcto"]);
        } else {
            DB::rollBack();
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }

    //############################## DETALLES DE CLASES [REPORTES]
    public function generarReporteAsistenciasPDF($clase_fk, $token, $select)
    {
        $clase = DB::table('cursos_asignacion_materias')->where('id', $clase_fk)->where('token', $token)->first();
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
        if ($clase) {
            if ($select == "all") {
                $docente = DB::table('asignaturas_asignacion_docentes as asign')
                    ->leftJoin('usuario', 'asign.docente_fk', '=', 'usuario.id')
                    ->select('usuario.primer_nombre', 'usuario.segundo_nombre', 'usuario.apellido_paterno', 'usuario.apellido_materno')
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('asign.asignatura_fk', $clase->asignatura_fk)
                    ->first();
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
                $estudiantes = DB::table('usuario')
                    ->leftJoin('matricula', 'usuario.id', '=', 'matricula.usuario_fk')
                    ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                    ->select(
                        'usuario.*',
                        DB::raw("(
                                SELECT COUNT(*) FROM asistencia_clase

                                LEFT JOIN periodolectivo_parcial ON asistencia_clase.parcial_fk = periodolectivo_parcial.id
                                LEFT JOIN periodolectivo_quimestre ON periodolectivo_parcial.quimestre_fk = periodolectivo_quimestre.id

                                where
                                periodolectivo_quimestre.nombre = 'Quimestre 1' and
                                asistencia_clase.asistencia = 0 and
                                asistencia_clase.estudiante_fk = usuario.id and
                                asistencia_clase.clase_fk = $clase_fk and
                                asistencia_clase.curso_fk = $asignatura->curso_fk and
                                periodolectivo_parcial.periodolectivo_fk = $periodo_activo->id
                        ) as q1_faltas"),
                        DB::raw("(
                            SELECT COUNT(*) FROM asistencia_clase

                            LEFT JOIN periodolectivo_parcial ON asistencia_clase.parcial_fk = periodolectivo_parcial.id
                            LEFT JOIN periodolectivo_quimestre ON periodolectivo_parcial.quimestre_fk = periodolectivo_quimestre.id

                                where
                                periodolectivo_quimestre.nombre = 'Quimestre 1' and
                                asistencia_clase.asistencia = 0 and
                                asistencia_clase.asistencia_justificada = 1 and
                                asistencia_clase.estudiante_fk = usuario.id and
                                asistencia_clase.clase_fk = $clase_fk and
                                asistencia_clase.curso_fk = $asignatura->curso_fk and
                                periodolectivo_parcial.periodolectivo_fk = $periodo_activo->id
                        ) as q1_faltas_just"),
                        DB::raw("(
                                SELECT COUNT(*) FROM asistencia_clase

                                LEFT JOIN periodolectivo_parcial ON asistencia_clase.parcial_fk = periodolectivo_parcial.id
                                LEFT JOIN periodolectivo_quimestre ON periodolectivo_parcial.quimestre_fk = periodolectivo_quimestre.id

                                where
                                periodolectivo_quimestre.nombre = 'Quimestre 2' and
                                asistencia_clase.asistencia = 0 and
                                asistencia_clase.estudiante_fk = usuario.id and
                                asistencia_clase.clase_fk = $clase_fk and
                                asistencia_clase.curso_fk = $asignatura->curso_fk and
                                periodolectivo_parcial.periodolectivo_fk = $periodo_activo->id
                        ) as q2_faltas"),
                        DB::raw("(
                            SELECT COUNT(*) FROM asistencia_clase

                            LEFT JOIN periodolectivo_parcial ON asistencia_clase.parcial_fk = periodolectivo_parcial.id
                            LEFT JOIN periodolectivo_quimestre ON periodolectivo_parcial.quimestre_fk = periodolectivo_quimestre.id

                                where
                                periodolectivo_quimestre.nombre = 'Quimestre 2' and
                                asistencia_clase.asistencia = 0 and
                                asistencia_clase.asistencia_justificada = 1 and
                                asistencia_clase.estudiante_fk = usuario.id and
                                asistencia_clase.clase_fk = $clase_fk and
                                asistencia_clase.curso_fk = $asignatura->curso_fk and
                                periodolectivo_parcial.periodolectivo_fk = $periodo_activo->id
                        ) as q2_faltas_just")
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
                $nombrepdf = "Asistencias de estudiantes";
                $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_asistencias', compact('asignatura', 'clase', 'docente', 'estudiantes', 'nombrepdf'));
                return $pdf->stream("$nombrepdf.pdf");
            }
            if ($select != "all") {
                $docente = DB::table('asignaturas_asignacion_docentes as asign')
                    ->leftJoin('usuario', 'asign.docente_fk', '=', 'usuario.id')
                    ->select('usuario.primer_nombre', 'usuario.segundo_nombre', 'usuario.apellido_paterno', 'usuario.apellido_materno')
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('asign.asignatura_fk', $clase->asignatura_fk)
                    ->first();

                $estudiantes = DB::table('matricula')
                    ->leftJoin('usuario', 'matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos', 'matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo', 'matricula.id', '=', 'periodolectivo.id')
                    ->select('usuario.*')
                    ->where('usuario.tipo_usuario', [2])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    ->where('usuario.id', $select)
                    ->where('matricula.curso_fk', $clase->curso_fk)
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                    ->get();
                $pn = $estudiantes[0]->primer_nombre;
                $sn = $estudiantes[0]->segundo_nombre;
                $ap = $estudiantes[0]->apellido_paterno;
                $am = $estudiantes[0]->apellido_materno;

                //CLASE
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

                $q1_faltas = DB::table('asistencia_clase')
                    ->leftJoin('usuario', 'asistencia_clase.estudiante_fk', '=', 'usuario.id')
                    ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre', 'periodolectivo_parcial.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*', 'periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre', ['Quimestre 1'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                    ->count();
                $q1_faltas_just = DB::table('asistencia_clase')
                    ->leftJoin('usuario', 'asistencia_clase.estudiante_fk', '=', 'usuario.id')
                    ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre', 'periodolectivo_parcial.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*', 'periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre', ['Quimestre 1'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('asistencia_clase.asistencia_justificada', [1])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                    ->count();

                $q2_faltas = DB::table('asistencia_clase')
                    ->leftJoin('usuario', 'asistencia_clase.estudiante_fk', '=', 'usuario.id')
                    ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre', 'periodolectivo_parcial.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*', 'periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre', ['Quimestre 2'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                    ->count();
                $q2_faltas_just = DB::table('asistencia_clase')
                    ->leftJoin('usuario', 'asistencia_clase.estudiante_fk', '=', 'usuario.id')
                    ->leftJoin('periodolectivo_parcial', 'asistencia_clase.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre', 'periodolectivo_parcial.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*', 'periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre', ['Quimestre 2'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('asistencia_clase.asistencia_justificada', [1])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                    ->count();

                $nombrepdf = "Asistencia: $ap $am $pn $sn";
                $pdf = PDF::loadView('pages.reportes.clases.estudiante.PDF-clases_asistencias', compact('asignatura', 'clase', 'docente', 'estudiantes', 'nombrepdf', 'q1_faltas', 'q1_faltas_just', 'q2_faltas', 'q2_faltas_just'));
                return $pdf->stream("$nombrepdf.pdf");
            }
        } else {
            return view('notfound');
        }
    }
    public function generarReporteCalificacionesPDF($clase_fk, $token, $select)
    {
        $clase = DB::table('cursos_asignacion_materias')->where('id', $clase_fk)->where('token', $token)->first();
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

        $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones', compact(
            'clase',
            'quimestre',
            'actividades',
            'periodo_activo',
            'parciales',
            'estudiantes',
            'calificaciones',
            // 'curso',
            'asignatura'
            // 'nombre'
        ));
        // return $pdf->stream("$nombre - ASIGNATURAS.pdf");
        return $pdf->stream("PRUEBA - ASIGNATURAS.pdf");
    }
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

    public function generarReporteCalificacionesActividadesPDFAll($clase_fk, $token)
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

        $parciales = DB::table('periodolectivo_parcial')
            ->leftJoin('periodolectivo_quimestre', 'periodolectivo_parcial.quimestre_fk', '=', 'periodolectivo_quimestre.id')
            ->select(
                DB::raw("(
                    SELECT COUNT(*) FROM actividades_parcial
                    where
                    actividades_parcial.parcial_fk = periodolectivo_parcial.id and
                    actividades_parcial.clase_fk = $clase->id and
                    actividades_parcial.estado = 1 and
                    actividades_parcial.eliminado = 0
                ) as actividades_x_parcial"),
                "periodolectivo_quimestre.nombre as quimestre_nombre",
                "periodolectivo_parcial.*"
            )
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->where('periodolectivo_parcial.activo', 1)
            ->first();

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
            ->where('actividades_calificaciones_matriculados.parcial_fk', $parciales->id)
            // ->where('actividades_calificaciones_matriculados.parcial_fk', $quimestre_periodo[1])
            ->get();

        $calificaciones_parcial = DB::table('calificaciones_parcial_matriculados')
            ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
            ->select(
                'calificaciones_parcial_matriculados.*',
                'periodolectivo_parcial.*'
            )
            ->where('calificaciones_parcial_matriculados.clase_fk', $clase->id)
            ->where('calificaciones_parcial_matriculados.curso_fk', $clase->curso_fk)
            // ->where('periodolectivo_parcial.quimestre_fk', $quimestre_periodo[0])
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->where('periodolectivo_parcial.id', $parciales->id)
            ->get();

        $actividades = DB::table('actividades_parcial')
            ->leftJoin('actividades', 'actividades_parcial.actividad_fk', '=', 'actividades.id')
            ->leftJoin('periodolectivo_parcial', 'actividades_parcial.parcial_fk', '=', 'periodolectivo_parcial.id')
            ->select(
                'actividades.abr as actividad_abr',
                'actividades.nombre as actividad_nombre',
                'actividades.color as actividad_color',
                'actividades.porcentaje as actividad_porcentaje',
                'actividades.max_calificacion as actividad_max_calificacion',
                'actividades_parcial.*'
            )
            ->where('actividades_parcial.clase_fk', $clase->id)
            ->where('actividades_parcial.curso_fk', $clase->curso_fk)
            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->where('actividades_parcial.parcial_fk', $parciales->id)
            ->where('actividades_parcial.estado', [1])
            ->where('actividades_parcial.eliminado', [0])
            ->orderBy('actividades.id')
            ->orderBy('actividades_parcial.created_at')
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

        // dd($parciales, $estudiantes, $actividades, $asignatura, $calificaciones, $calificaciones_parcial);
        $nombre_pdf = "CALIFICACIONES - $clase->nombre_asignatura";
        $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones_actividades', compact(
            'nombre_pdf',
            'parciales',
            'estudiantes',
            'actividades',
            'asignatura',
            'calificaciones',
            'calificaciones_parcial'
        ))->setPaper('A4', 'landscape');

        // return $pdf->stream("$nombre - ASIGNATURAS.pdf");
        return $pdf->stream("$nombre_pdf.pdf");
    }
}

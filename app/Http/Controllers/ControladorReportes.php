<?php

namespace App\Http\Controllers;

use \PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorReportes extends Controller{

    public function viewReportes(){
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
        //SECRETARIA
        if(Auth::user()->tipo_usuario == 4 || Auth::user()->tipo_usuario == 5){
            $cursos = DB::table('cursos')->where('estado', [1])->where('eliminado', [0])->get();

            return view('pages.reportes.reportes_secretaria', compact('cursos'));
        }

        if(Auth::user()->tipo_usuario == 2){
            if($periodo_activo){
                $curso = DB::table('matricula')
                    ->leftJoin('cursos','matricula.curso_fk','cursos.id')
                    ->select('cursos.*')
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    ->where('matricula.usuario_fk' , Auth::user()->id)
                    ->where('matricula.periodo_fk',  $periodo_activo->id)
                ->first();
                if($curso){

                    $asignaturas = DB::table('cursos_asignacion_materias as clase')
                        ->leftJoin('asignaturas', 'clase.asignatura_fk','asignaturas.id')
                        ->leftJoin('asignaturas_asignacion_docentes as doc', 'clase.asign_docmateria_fk','doc.id')
                        ->leftJoin('usuario', 'doc.docente_fk','usuario.id')
                        ->select(
                            'asignaturas.*',
                            'clase.id as clase_fk',
                            'clase.token as clase_token',
                            'usuario.apellido_paterno as usuario_pa',
                            'usuario.apellido_materno as usuario_sa',
                            'usuario.primer_nombre as usuario_pn',
                            'usuario.segundo_nombre as usuario_sn'
                        )
                        ->where('usuario.estado', [1])
                        ->where('usuario.eliminado', [0])
                        ->where('clase.curso_fk', $curso->id)
                        ->where('clase.periodo_fk', $periodo_activo->id)
                    ->get();


                    return view('pages.reportes.reportes_estudiante', compact('curso', 'asignaturas'));
                }else{
                    return view('sinreportes');
                }
            }else{
                return view('sinreportes');
            }
        }

        if(Auth::user()->tipo_usuario != 2 || Auth::user()->tipo_usuario != 4 || Auth::user()->tipo_usuario != 5){
            return view('notfoundpage');
        }
    }
    public function reporteSearchMateriaAlumnos($curso_fk){
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        $asignaturas =  DB::table('asignaturas')
            ->leftJoin('asignaturas_asignacion_docentes as doc','asignaturas.id', 'doc.asignatura_fk')
            ->leftJoin('cursos_asignacion_materias as clase','doc.id', 'clase.asign_docmateria_fk')
            ->leftJoin('usuario','doc.docente_fk', 'usuario.id')
            ->select(
                'asignaturas.*',
                'clase.id as clase_id',
                'clase.token as clase_token',
                'usuario.apellido_paterno as usuario_papellido',
                'usuario.apellido_materno as usuario_sapellido',
                'usuario.primer_nombre as usuario_pnombre',
                'usuario.segundo_nombre as usuario_snombre'
            )
            ->where('usuario.estado', [1])
            ->where('usuario.eliminado', [0])
            ->where('clase.curso_fk', $curso_fk)
            ->where('doc.periodo_fk', $periodo_activo->id)
        ->where('clase.periodo_fk', $periodo_activo->id);

        $alumnos =  DB::table('matricula')
            ->leftJoin('usuario','matricula.usuario_fk', 'usuario.id')
            ->select('usuario.*')
            ->where('matricula.estado', [1])
            ->where('matricula.eliminado', [0])
            ->where('usuario.estado', [1])
            ->where('usuario.eliminado', [0])
            ->where('matricula.periodo_fk', $periodo_activo->id)
            ->where('matricula.curso_fk', $curso_fk)
            ->orderBy('usuario.apellido_paterno')
            ->orderBy('usuario.apellido_materno')
            ->orderBy('usuario.primer_nombre')
        ->orderBy('usuario.segundo_nombre');

        if($asignaturas->count() > 0 && $alumnos->count()){
            return response()->json(['success' => 'success', $asignaturas->get() , $alumnos->get()]);
        }else{
            if($asignaturas->count() == 0 && $alumnos->count() == 0){
                return response()->json(['error' => 'No se encontraron Asignaturas, Estudiantes matriculados en este curso.']);
            }
            if($asignaturas->count() == 0){
                return response()->json(['error' => 'No se encontraron asignaturas para este curso.']);
            }
            if($alumnos->count() == 0){
                return response()->json(['error' => 'No se encontraron estudiantes matriculados en este curso.']);
            }
        }
    }

    public function generateReporteAsistenciaPDF($clase_fk, $token, $select_alum){
        $select =  $select_alum;
        $clase = DB::table('cursos_asignacion_materias')->where('id', $clase_fk)->where('token', $token)->first();
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
        if($clase){
            if($select == "ALL"){
                $docente = DB::table('asignaturas_asignacion_docentes as asign')
                    ->leftJoin('usuario','asign.docente_fk', '=', 'usuario.id')
                    ->select('usuario.primer_nombre','usuario.segundo_nombre','usuario.apellido_paterno','usuario.apellido_materno')
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('asign.asignatura_fk', $clase->asignatura_fk)
                ->first();
                $asignatura = DB::table('cursos_asignacion_materias as asign')
                    ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=','doc.id')
                    ->leftJoin('usuario','doc.docente_fk','=','usuario.id')

                    ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
                    ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
                    ->select(
                        'asignaturas.nombre as asignatura_nombre',
                        'cursos.id as curso_fk',
                        'cursos.nombre as curso_nombre',
                        'cursos.nivel as curso_nivel',
                        'cursos.paralelo as curso_paralelo'
                    )
                    ->where('usuario.primer_nombre','!=', null)
                    ->where('asign.id', $clase_fk)
                    ->where('asign.periodo_fk', $periodo_activo->id)
                ->first();
                $estudiantes = DB::table('usuario')
                    ->leftJoin('matricula','usuario.id', '=', 'matricula.usuario_fk')
                    ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
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
                $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_asistencias', compact( 'asignatura','clase','docente','estudiantes','nombrepdf'));
                return $pdf->stream("$nombrepdf.pdf");
            }
            if($select != "ALL"){
                $docente = DB::table('asignaturas_asignacion_docentes as asign')
                    ->leftJoin('usuario','asign.docente_fk', '=', 'usuario.id')
                    ->select('usuario.primer_nombre','usuario.segundo_nombre','usuario.apellido_paterno','usuario.apellido_materno')
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('asign.asignatura_fk', $clase->asignatura_fk)
                ->first();

                $estudiantes = DB::table('matricula')
                    ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
                    ->select('usuario.*')
                    ->where('usuario.tipo_usuario', [2])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select_alum)
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    ->where('matricula.curso_fk', $clase->curso_fk)
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                ->get();
                $pn = $estudiantes[0]->primer_nombre;  $sn = $estudiantes[0]->segundo_nombre;  $ap = $estudiantes[0]->apellido_paterno;  $am = $estudiantes[0]->apellido_materno;

                //CLASE
                $asignatura = DB::table('cursos_asignacion_materias as asign')
                    ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=','doc.id')
                    ->leftJoin('usuario','doc.docente_fk','=','usuario.id')
                    ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
                    ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
                    ->select(
                        'asignaturas.nombre as asignatura_nombre',
                        'cursos.id as curso_fk',
                        'cursos.nombre as curso_nombre',
                        'cursos.nivel as curso_nivel',
                        'cursos.paralelo as curso_paralelo'
                    )
                    ->where('usuario.primer_nombre', '!=', null)
                    ->where('asign.id', $clase_fk)
                    ->where('asign.periodo_fk', $periodo_activo->id)
                ->first();

                $q1_faltas = DB::table('asistencia_clase')
                    ->leftJoin('usuario','asistencia_clase.estudiante_fk','=','usuario.id')
                    ->leftJoin('periodolectivo_parcial','asistencia_clase.parcial_fk','=','periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre','periodolectivo_parcial.quimestre_fk','=','periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*','periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre',['Quimestre 1'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                ->count();
                $q1_faltas_just = DB::table('asistencia_clase')
                    ->leftJoin('usuario','asistencia_clase.estudiante_fk','=','usuario.id')
                    ->leftJoin('periodolectivo_parcial','asistencia_clase.parcial_fk','=','periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre','periodolectivo_parcial.quimestre_fk','=','periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*','periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre',['Quimestre 1'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('asistencia_clase.asistencia_justificada', [1])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                ->count();

                $q2_faltas = DB::table('asistencia_clase')
                    ->leftJoin('usuario','asistencia_clase.estudiante_fk','=','usuario.id')
                    ->leftJoin('periodolectivo_parcial','asistencia_clase.parcial_fk','=','periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre','periodolectivo_parcial.quimestre_fk','=','periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*','periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre',['Quimestre 2'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                ->count();
                $q2_faltas_just = DB::table('asistencia_clase')
                    ->leftJoin('usuario','asistencia_clase.estudiante_fk','=','usuario.id')
                    ->leftJoin('periodolectivo_parcial','asistencia_clase.parcial_fk','=','periodolectivo_parcial.id')
                    ->leftJoin('periodolectivo_quimestre','periodolectivo_parcial.quimestre_fk','=','periodolectivo_quimestre.id')
                    ->select('asistencia_clase.*','periodolectivo_quimestre.nombre')
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->where('periodolectivo_quimestre.nombre',['Quimestre 2'])
                    ->where('asistencia_clase.clase_fk', $clase_fk)
                    ->where('asistencia_clase.curso_fk', $asignatura->curso_fk)
                    ->where('asistencia_clase.asistencia', [0])
                    ->where('asistencia_clase.asistencia_justificada', [1])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('usuario.id', $select) //comentar para obtener todos los usuarios
                ->count();

                $nombrepdf = "Asistencia: $ap $am $pn $sn";
                $pdf = PDF::loadView('pages.reportes.clases.estudiante.PDF-clases_asistencias', compact( 'asignatura','clase','docente','estudiantes','nombrepdf', 'q1_faltas','q1_faltas_just','q2_faltas','q2_faltas_just'  ));
                return $pdf->stream("$nombrepdf.pdf");
            }
        }else{
            return view('notfound');
        }
    }

    public function generateReporteAsistenciaEstudiantePDF($idregistro, $tokenclase, $periodo){
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

            $nombre = "$estudiantes->primer_nombre $estudiantes->segundo_nombre $estudiantes->apellido_paterno $estudiantes->apellido_materno";

            $porcion_parcial = explode("-", $periodo);
            $inasistencia = DB::table('asistencia_clase')
                ->where('estudiante_fk', Auth::user()->id)
                ->where('parcial_fk', $porcion_parcial[1])
                ->where('clase_fk', $idregistro)
                ->where('curso_fk', $existe->curso_fk)
                ->orderByDesc('fecha')
                // ->where('fecha', date('Y-m-d', strtotime($fecha)))
            ->get();

            $asignatura = DB::table('cursos_asignacion_materias as asign')
                ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=','doc.id')
                ->leftJoin('usuario','doc.docente_fk','=','usuario.id')

                ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
                ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
                ->select(
                    'asignaturas.nombre as asignatura_nombre',
                    'cursos.id as curso_fk',
                    'cursos.nombre as curso_nombre',
                    'cursos.nivel as curso_nivel',
                    'cursos.paralelo as curso_paralelo'
                )
                ->where('usuario.primer_nombre', '!=', null)
                ->where('asign.id', $idregistro)
                ->where('asign.periodo_fk', $periodo_activo->id)
            ->first();

            $nombrepdf = "Asistencia - $nombre";

            // dd($estudiantes, $inasistencia);
            $pdf = PDF::loadView('pages.reportes.clases.estudiante.PDF-clases_asistencias_individual', compact(
                'estudiantes',
                'inasistencia',
                'nombrepdf',
                'asignatura'
            ));

            return $pdf->stream("$nombrepdf.pdf");
        } else {
            return view('notfound');
        }
    }
    public function generateReporteCalificacionesPDF($clase_fk, $token, $select_alum){
        // dd($clase_fk, $token, $select_alum);
        $select =  $select_alum;
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

        if($clase){
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

            $asignatura = DB::table('cursos_asignacion_materias as asign')
                ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=','doc.id')
                ->leftJoin('usuario','doc.docente_fk','=','usuario.id')

                ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
                ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
                ->select(
                    'asignaturas.nombre as asignatura_nombre',
                    'cursos.id as curso_fk',
                    'cursos.nombre as curso_nombre',
                    'cursos.nivel as curso_nivel',
                    'cursos.paralelo as curso_paralelo'
                )
                ->where('usuario.primer_nombre', '!=', null)
                ->where('asign.id', $clase_fk)
                ->where('asign.periodo_fk', $periodo_activo->id)
            ->first();

            if($select == "ALL"){
                $estudiantes = DB::table('matricula')
                    ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                    ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
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
                        'calificaciones_examen_quimestral_matriculados.*'
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
                        'calificaciones_quimestre_matriculados.*'
                    )
                    ->where('calificaciones_quimestre_matriculados.estado', [1])
                    ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                    ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
                    ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
                    ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                ->get();

                $nombre_pdf = "CALIFICACIONES - $clase->nombre_asignatura";
            }
            if($select != "ALL"){

                $estudiante = DB::table('matricula')
                    ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                    ->select(
                        'matricula.id as ident_matricula',
                        'usuario.*'
                    )
                    ->where('usuario.id', $select)
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
                ->first();

                if ($estudiante) {
                    $estudiantes = DB::table('matricula')
                        ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                        ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                        ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
                        ->select(
                            'matricula.id as ident_matricula',
                            'usuario.*'
                        )
                        ->where('usuario.id', $select)
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

                    $calificaciones = DB::table('calificaciones_parcial_matriculados')
                        ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                        ->select(
                            'calificaciones_parcial_matriculados.*',
                            'periodolectivo_parcial.*'
                        )
                        ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('calificaciones_parcial_matriculados.clase_fk', $clase->id)
                        ->where('calificaciones_parcial_matriculados.curso_fk', $clase->curso_fk)
                        ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->get();

                    $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                        ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                        ->select(
                            'calificaciones_examen_quimestral_matriculados.*'
                        )
                        ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                        ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                        ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $clase->id)
                        ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $clase->curso_fk)
                        ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                    ->get();

                    $calificaciones_quimestre = DB::table('calificaciones_quimestre_matriculados')
                        ->leftJoin('periodolectivo_quimestre', 'calificaciones_quimestre_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                        ->select(
                            'calificaciones_quimestre_matriculados.*'
                        )
                        ->where('calificaciones_quimestre_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('calificaciones_quimestre_matriculados.estado', [1])
                        ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                        ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
                        ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
                        ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                    ->get();

                    $nombre_pdf = "Calificaciones: $estudiante->primer_nombre $estudiante->segundo_nombre $estudiante->apellido_paterno $estudiante->apellido_materno";

                } else {
                    $estudiantes = [];
                    $calificaciones = [];
                    $calificaciones_examen = [];
                    $calificaciones_quimestre = [];
                    $nombre_pdf = "Calificaciones: --";

                }
            }

            // dd($quimestre, $parciales, $estudiantes, $asignatura, $calificaciones, $calificaciones_examen, $calificaciones_quimestre);

            $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones', compact(
                'clase',
                'quimestre',
                'periodo_activo',
                'parciales',
                'estudiantes',
                'calificaciones',
                'calificaciones_examen',
                'calificaciones_quimestre',
                'nombre_pdf',
                'asignatura'
            ));

            return $pdf->stream("$nombre_pdf.pdf");
        }else{
            return view('notfound');
        }
    }

    // Visualizacion de calidicaciones reporte estudiante por materias

    public function searhCalificacionesEstudianteCurso($id, $token){

        $clase = DB::table('cursos_asignacion_materias')
            ->leftJoin('asignaturas', 'cursos_asignacion_materias.asignatura_fk', '=', 'asignaturas.id')
            ->select(
                'cursos_asignacion_materias.*',
                'asignaturas.nombre as nombre_asignatura'
            )
            ->where('cursos_asignacion_materias.id', $id)
            ->where('cursos_asignacion_materias.token', $token)
        ->first();

        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        $select = Auth::user()->id;

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

        $asignatura = DB::table('cursos_asignacion_materias as asign')
            ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
            ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
            ->select(
                'asignaturas.nombre as asignatura_nombre',
                'cursos.id as curso_fk',
                'cursos.nombre as curso_nombre',
                'cursos.nivel as curso_nivel',
                'cursos.paralelo as curso_paralelo'
            )
            ->where('asign.id', $id)
            ->where('asign.periodo_fk', $periodo_activo->id)
        ->first();


        $estudiante = DB::table('matricula')
            ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
            ->select(
                'matricula.id as ident_matricula',
                'usuario.*'
            )
            ->where('usuario.id', $select)
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
        ->first();

        if ($estudiante) {
            $estudiantes = DB::table('matricula')
                ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
                ->select(
                    'matricula.id as ident_matricula',
                    'usuario.*'
                )
                ->where('usuario.id', $select)
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

            $calificaciones = DB::table('calificaciones_parcial_matriculados')
                ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                ->select(
                    'calificaciones_parcial_matriculados.*',
                    'periodolectivo_parcial.*'
                )
                ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiante->ident_matricula)
                ->where('calificaciones_parcial_matriculados.clase_fk', $clase->id)
                ->where('calificaciones_parcial_matriculados.curso_fk', $clase->curso_fk)
                ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->get();

            $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                ->select(
                    'calificaciones_examen_quimestral_matriculados.*',
                )
                ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $estudiante->ident_matricula)
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
                ->where('calificaciones_quimestre_matriculados.matriculado_fk', $estudiante->ident_matricula)
                ->where('calificaciones_quimestre_matriculados.estado', [1])
                ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
                ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
                ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
            ->get();
        } else {
            $estudiantes = [];
            $calificaciones = [];
            $calificaciones_examen = [];
            $calificaciones_quimestre = [];
        }



        return response()->json([
            'select' => $select,
            'clase' => $clase,
            'periodo_activo' => $periodo_activo,
            'estudiante' => $estudiante,
            'quimestre' => $quimestre,
            'parciales' => $parciales,
            'estudiantes' => $estudiantes,
            'calificaciones' => $calificaciones,
            'calificaciones_examen' => $calificaciones_examen,
            'calificaciones_quimestre' => $calificaciones_quimestre,
            'success' => 'success'
        ]);
    }

    public function searhCalificacionesAsignatura(){
        $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

        if($periodo_activo){
            $quimestre = DB::table('periodolectivo_quimestre')
                ->select(
                    "periodolectivo_quimestre.*"
                )
                ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
            ->get();

            $parciales = DB::table('periodolectivo_parcial')
                ->select(
                    "periodolectivo_parcial.*"
                )
                ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
            ->get();

            $select = Auth::user()->id;

            $estudiante = DB::table('matricula')
                ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                ->leftJoin('cursos','matricula.curso_fk','=','cursos.id')
                ->select(
                    'matricula.curso_fk as ident_curso_fk',
                    'matricula.id as ident_matricula',
                    'cursos.id as curso_fk',
                    'cursos.nombre as curso_nombre',
                    'cursos.nivel as curso_nivel',
                    'cursos.paralelo as curso_paralelo',
                    'usuario.*'
                )
                ->where('usuario.id', $select)
                ->where('usuario.tipo_usuario', [2])
                ->where('usuario.estado', [1])
                ->where('usuario.eliminado', [0])
                ->where('matricula.estado', [1])
                ->where('matricula.eliminado', [0])
                // ->where('matricula.curso_fk', $id)
                ->where('matricula.periodo_fk', $periodo_activo->id)
                ->orderBy('usuario.apellido_paterno')
                ->orderBy('usuario.apellido_materno')
                ->orderBy('usuario.primer_nombre')
                ->orderBy('usuario.segundo_nombre')
            ->first();

            if ($estudiante) {
                $asignatura = DB::table('cursos_asignacion_materias')
                    ->leftJoin('asignaturas_asignacion_docentes as doc','cursos_asignacion_materias.asign_docmateria_fk','=','doc.id')
                    ->leftJoin('usuario','doc.docente_fk','=','usuario.id')
                    ->leftJoin('asignaturas','cursos_asignacion_materias.asignatura_fk','=','asignaturas.id')
                    ->select(
                        'cursos_asignacion_materias.*',
                        'asignaturas.nombre as nombre_asignatura'
                    )
                    ->where('usuario.primer_nombre', '!=', null)
                    ->where('cursos_asignacion_materias.curso_fk', $estudiante->ident_curso_fk)
                    ->where('cursos_asignacion_materias.periodo_fk', $periodo_activo->id)
                ->get();

                $calificaciones = DB::table('calificaciones_parcial_matriculados')
                    ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                    ->select(
                        'calificaciones_parcial_matriculados.*',
                        'periodolectivo_parcial.*'
                    )
                    ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiante->ident_matricula)
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                ->get();

                $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                    ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select(
                        'calificaciones_examen_quimestral_matriculados.*',
                    )
                    ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $estudiante->ident_matricula)
                    ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                    ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                    ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                ->get();

                $calificaciones_quimestre = DB::table('calificaciones_quimestre_matriculados')
                    ->leftJoin('periodolectivo_quimestre', 'calificaciones_quimestre_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                    ->select(
                        'calificaciones_quimestre_matriculados.*',
                    )
                    ->where('calificaciones_quimestre_matriculados.matriculado_fk', $estudiante->ident_matricula)
                    ->where('calificaciones_quimestre_matriculados.estado', [1])
                    ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                    ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                ->get();
            } else {
                $asignatura = [];
                $calificaciones = [];
                $calificaciones_examen = [];
                $calificaciones_quimestre = [];
            }



            $nombre_pdf  = "Calificaciones - Asignaturas estudiantes";
            // dd(
            //     $nombre_pdf,
            //     $estudiante,
            //     $asignatura,
            //     $quimestre,
            //     $parciales,
            //     $calificaciones,
            //     $calificaciones_examen,
            //     $calificaciones_quimestre
            // );
            $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones_asignaturas', compact(
                'nombre_pdf',
                'estudiante',
                'asignatura',
                'quimestre',
                'parciales',
                'calificaciones',
                'calificaciones_examen',
                'calificaciones_quimestre'
            ));
            return $pdf->stream("$nombre_pdf.pdf");
        }else{
            return view('notfound');
        }
    }

    //nuevo
    public function generateReporteCalificacionesGeneralPDF($id, $select_asig){
        // dd($clase_fk, $token, $select_alum);
        $selectM =  $select_asig;
        if ($selectM == 'ALL'){
            $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

            if($periodo_activo){
                $quimestre = DB::table('periodolectivo_quimestre')
                    ->select(
                        "periodolectivo_quimestre.*"
                    )
                    ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                ->get();

                $parciales = DB::table('periodolectivo_parcial')
                    ->select(
                        "periodolectivo_parcial.*"
                    )
                    ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                ->get();

                $select = $id;

                $estudiante = DB::table('matricula')
                    ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                    ->leftJoin('cursos','matricula.curso_fk','=','cursos.id')
                    ->select(
                        'matricula.curso_fk as ident_curso_fk',
                        'matricula.id as ident_matricula',
                        'cursos.id as curso_fk',
                        'cursos.nombre as curso_nombre',
                        'cursos.nivel as curso_nivel',
                        'cursos.paralelo as curso_paralelo',
                        'usuario.*'
                    )
                    ->where('usuario.id', $select)
                    ->where('usuario.tipo_usuario', [2])
                    ->where('usuario.estado', [1])
                    ->where('usuario.eliminado', [0])
                    ->where('matricula.estado', [1])
                    ->where('matricula.eliminado', [0])
                    // ->where('matricula.curso_fk', $id)
                    ->where('matricula.periodo_fk', $periodo_activo->id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                ->first();

                if ($estudiante) {
                    $asignatura = DB::table('cursos_asignacion_materias')
                        ->leftJoin('asignaturas_asignacion_docentes as doc','cursos_asignacion_materias.asign_docmateria_fk','=','doc.id')
                        ->leftJoin('usuario','doc.docente_fk','=','usuario.id')
                        ->leftJoin('asignaturas','cursos_asignacion_materias.asignatura_fk','=','asignaturas.id')
                        ->select(
                            'cursos_asignacion_materias.*',
                            'asignaturas.nombre as nombre_asignatura'
                        )
                        ->where('usuario.primer_nombre', '!=', null)
                        ->where('cursos_asignacion_materias.curso_fk', $estudiante->ident_curso_fk)
                        ->where('cursos_asignacion_materias.periodo_fk', $periodo_activo->id)
                    ->get();

                    $calificaciones = DB::table('calificaciones_parcial_matriculados')
                        ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                        ->select(
                            'calificaciones_parcial_matriculados.*',
                            'periodolectivo_parcial.*'
                        )
                        ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                    ->get();

                    $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                        ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                        ->select(
                            'calificaciones_examen_quimestral_matriculados.*',
                        )
                        ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                        ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                        ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                    ->get();

                    $calificaciones_quimestre = DB::table('calificaciones_quimestre_matriculados')
                        ->leftJoin('periodolectivo_quimestre', 'calificaciones_quimestre_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                        ->select(
                            'calificaciones_quimestre_matriculados.*',
                        )
                        ->where('calificaciones_quimestre_matriculados.matriculado_fk', $estudiante->ident_matricula)
                        ->where('calificaciones_quimestre_matriculados.estado', [1])
                        ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                        ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                    ->get();
                } else {
                    $asignatura = [];
                    $calificaciones = [];
                    $calificaciones_examen = [];
                    $calificaciones_quimestre = [];
                }



                $nombre_pdf  = "Calificaciones - Asignaturas estudiantes";
                // dd(
                //     $nombre_pdf,
                //     $estudiante,
                //     $asignatura,
                //     $quimestre,
                //     $parciales,
                //     $calificaciones,
                //     $calificaciones_examen,
                //     $calificaciones_quimestre
                // );
                $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones_asignaturas', compact(
                    'nombre_pdf',
                    'estudiante',
                    'asignatura',
                    'quimestre',
                    'parciales',
                    'calificaciones',
                    'calificaciones_examen',
                    'calificaciones_quimestre'
                ));
                return $pdf->stream("$nombre_pdf.pdf");
            }else{
                return view('notfound');
            }
        }



        if ($selectM != 'ALL') {
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

            if($clase){
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

                $asignatura = DB::table('cursos_asignacion_materias as asign')
                    ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=','doc.id')
                    ->leftJoin('usuario','doc.docente_fk','=','usuario.id')

                    ->leftJoin('asignaturas','asign.asignatura_fk','=','asignaturas.id')
                    ->leftJoin('cursos','asign.curso_fk','=','cursos.id')
                    ->select(
                        'asignaturas.nombre as asignatura_nombre',
                        'cursos.id as curso_fk',
                        'cursos.nombre as curso_nombre',
                        'cursos.nivel as curso_nivel',
                        'cursos.paralelo as curso_paralelo'
                    )
                    ->where('usuario.primer_nombre', '!=', null)
                    ->where('asign.id', $clase_fk)
                    ->where('asign.periodo_fk', $periodo_activo->id)
                ->first();

                if($select == "ALL"){
                    $estudiantes = DB::table('matricula')
                        ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                        ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                        ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
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
                            'calificaciones_examen_quimestral_matriculados.*'
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
                            'calificaciones_quimestre_matriculados.*'
                        )
                        ->where('calificaciones_quimestre_matriculados.estado', [1])
                        ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                        ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
                        ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
                        ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                    ->get();

                    $nombre_pdf = "CALIFICACIONES - $clase->nombre_asignatura";
                }
                if($select != "ALL"){

                    $estudiante = DB::table('matricula')
                        ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                        ->select(
                            'matricula.id as ident_matricula',
                            'usuario.*'
                        )
                        ->where('usuario.id', $select)
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
                    ->first();

                    if ($estudiante) {
                        $estudiantes = DB::table('matricula')
                            ->leftJoin('usuario','matricula.usuario_fk', '=', 'usuario.id')
                            ->leftJoin('cursos','matricula.curso_fk', '=', 'cursos.id')
                            ->leftJoin('periodolectivo','matricula.id', '=', 'periodolectivo.id')
                            ->select(
                                'matricula.id as ident_matricula',
                                'usuario.*'
                            )
                            ->where('usuario.id', $select)
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

                        $calificaciones = DB::table('calificaciones_parcial_matriculados')
                            ->leftJoin('periodolectivo_parcial', 'calificaciones_parcial_matriculados.parcial_fk', '=', 'periodolectivo_parcial.id')
                            ->select(
                                'calificaciones_parcial_matriculados.*',
                                'periodolectivo_parcial.*'
                            )
                            ->where('calificaciones_parcial_matriculados.matriculado_fk', $estudiante->ident_matricula)
                            ->where('calificaciones_parcial_matriculados.clase_fk', $clase->id)
                            ->where('calificaciones_parcial_matriculados.curso_fk', $clase->curso_fk)
                            ->where('periodolectivo_parcial.periodolectivo_fk', $periodo_activo->id)
                        ->get();

                        $calificaciones_examen = DB::table('calificaciones_examen_quimestral_matriculados')
                            ->leftJoin('periodolectivo_quimestre', 'calificaciones_examen_quimestral_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                            ->select(
                                'calificaciones_examen_quimestral_matriculados.*'
                            )
                            ->where('calificaciones_examen_quimestral_matriculados.matriculado_fk', $estudiante->ident_matricula)
                            ->where('calificaciones_examen_quimestral_matriculados.estado', [1])
                            ->where('calificaciones_examen_quimestral_matriculados.eliminado', [0])
                            ->where('calificaciones_examen_quimestral_matriculados.clase_fk', $clase->id)
                            ->where('calificaciones_examen_quimestral_matriculados.curso_fk', $clase->curso_fk)
                            ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                        ->get();

                        $calificaciones_quimestre = DB::table('calificaciones_quimestre_matriculados')
                            ->leftJoin('periodolectivo_quimestre', 'calificaciones_quimestre_matriculados.quimestre_fk', '=', 'periodolectivo_quimestre.id')
                            ->select(
                                'calificaciones_quimestre_matriculados.*'
                            )
                            ->where('calificaciones_quimestre_matriculados.matriculado_fk', $estudiante->ident_matricula)
                            ->where('calificaciones_quimestre_matriculados.estado', [1])
                            ->where('calificaciones_quimestre_matriculados.eliminado', [0])
                            ->where('calificaciones_quimestre_matriculados.clase_fk', $clase->id)
                            ->where('calificaciones_quimestre_matriculados.curso_fk', $clase->curso_fk)
                            ->where('periodolectivo_quimestre.aniolectivo_fk', $periodo_activo->id)
                        ->get();

                        $nombre_pdf = "Calificaciones: $estudiante->primer_nombre $estudiante->segundo_nombre $estudiante->apellido_paterno $estudiante->apellido_materno";

                    } else {
                        $estudiantes = [];
                        $calificaciones = [];
                        $calificaciones_examen = [];
                        $calificaciones_quimestre = [];
                        $nombre_pdf = "Calificaciones: --";

                    }
                }

                // dd($quimestre, $parciales, $estudiantes, $asignatura, $calificaciones, $calificaciones_examen, $calificaciones_quimestre);

                $pdf = PDF::loadView('pages.reportes.clases.general.PDF-clases_calificaciones', compact(
                    'clase',
                    'quimestre',
                    'periodo_activo',
                    'parciales',
                    'estudiantes',
                    'calificaciones',
                    'calificaciones_examen',
                    'calificaciones_quimestre',
                    'nombre_pdf',
                    'asignatura'
                ));

                return $pdf->stream("$nombre_pdf.pdf");
            }else{
                return view('notfound');
            }
        }

    }

}

<?php

namespace App\Http\Controllers;

// use Barryvdh\DomPDF\Facade as PDF;
use \PDF;
use App\User;
use App\System;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ControladorAcademico extends Controller
{
    protected $date_actual;
    public function __construct(){
        $this->date_actual = date('Y-m-d H:i:s');
    }
    //VALIDACIONES
    protected function validateEmail($parametro){
        if(filter_var($parametro, FILTER_VALIDATE_EMAIL) == false){
            return 'error';
        }else{
            return 'success';
        }
    }
    protected function validateExistEmail($parametro){
        return 'success';
        // $existencia_email = DB::table('usuario')->where('email', $parametro)->first();
        // if($existencia_email){ return 'error'; }else{ return 'success'; }
    }
    protected function validateCedula($parametro){
        if(strlen($parametro) == 10){
            return 'success';
        }else{
            return 'error';
        }
    }
    protected function validateExistCedula($parametro){
        $existencia_cedula = DB::table('usuario')->where('cedula', $parametro)->where('eliminado', [0])->first();
        if($existencia_cedula){ return 'error'; }else{  return 'success';  }
    }
    protected function validatePasswordOld($parametro){
        if(Hash::check($parametro, Auth::user()->password)){
            return 'success';
        }else{
            return 'error';
        }
    }
    protected function validatePasswordNew($parametro){
        if(strlen($parametro) >= 6){
            if(Hash::check($parametro, Auth::user()->password)){
                return 'error_existe';
            }else{
                return 'success';
            }
        }else{
            return 'error';
        }
    }
    protected function validatePeriodos($anioinicio, $aniofin){
        if($anioinicio == $aniofin){
            return "error_igual";
        }
        if($anioinicio > $aniofin){
            return "error_mayor";
        }
    }

    //############################### PARALELOS #############""#####################
        public function viewParalelos(){
            return view('pages.academico.paralelos.paralelos');
        }
        public function createNewParalelo(Request $request){

            return response()->json($request);
        }
    //##############################################################################

    //########################## PERIODOS ACADEMICOS ###############################
        public function viewPeriodos(){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralPeriodoAcademico')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){
                $periodos = DB::table('periodolectivo')->where('eliminado', [0])->orderByDesc('estado')->orderByDesc('id')->paginate(5);

                $qum1_parcial1 = DB::table('periodolectivo_parcial as parcial')
                    ->leftJoin('periodolectivo_quimestre as quimestre', 'parcial.quimestre_fk', 'quimestre.id')
                    ->select(
                        'parcial.*',
                        'parcial.quimestre_fk as quimestre_fk',
                        'parcial.periodolectivo_fk as periodo_fk',
                    )
                    ->where('parcial.nombre', 'Parcial 1')
                    ->where('quimestre.nombre', 'Quimestre 1')
                ->get();
                $qum1_parcial2 = DB::table('periodolectivo_parcial as parcial')
                    ->leftJoin('periodolectivo_quimestre as quimestre', 'parcial.quimestre_fk', 'quimestre.id')
                    ->select(
                        'parcial.*',
                        'parcial.quimestre_fk as quimestre_fk',
                        'parcial.periodolectivo_fk as periodo_fk',
                    )
                    ->where('parcial.nombre', 'Parcial 2')
                    ->where('quimestre.nombre', 'Quimestre 1')
                ->get();
                $qum2_parcial1 = DB::table('periodolectivo_parcial as parcial')
                    ->leftJoin('periodolectivo_quimestre as quimestre', 'parcial.quimestre_fk', 'quimestre.id')
                    ->select(
                        'parcial.*',
                        'parcial.quimestre_fk as quimestre_fk',
                        'parcial.periodolectivo_fk as periodo_fk',
                    )
                    ->where('parcial.nombre', 'Parcial 1')
                    ->where('quimestre.nombre', 'Quimestre 2')
                ->get();
                $qum2_parcial2 = DB::table('periodolectivo_parcial as parcial')
                    ->leftJoin('periodolectivo_quimestre as quimestre', 'parcial.quimestre_fk', 'quimestre.id')
                    ->select(
                        'parcial.*',
                        'parcial.quimestre_fk as quimestre_fk',
                        'parcial.periodolectivo_fk as periodo_fk',
                    )
                    ->where('parcial.nombre', 'Parcial 2')
                    ->where('quimestre.nombre', 'Quimestre 2')
                ->get();
                // dd($qum1_parcial1 , $qum1_parcial2 ,$qum2_parcial1 ,$qum2_parcial2);
                $activo = DB::table('periodolectivo')->where('estado', [1])->count();
                $rank = $periodos->firstItem();
                return view('pages.academico.periodo.periodo', compact('periodos','rank', 'activo', 'qum1_parcial1' , 'qum1_parcial2' ,'qum2_parcial1' ,'qum2_parcial2'));
            }else{
                return redirect()->route('home');
            }

        }
        public function crearPeriodo(Request $request){
            if($request->anio_inicio != null){
                $activos = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', [0])->count();
                if($activos > 0 && $request->estado == 1){
                    return response()->json(['warning' => 'No se puede registrar periodo, Ya existe uno "Activo".']);
                }else{
                    $Validateperiodo = $this->validatePeriodos($request->anio_inicio, $request->anio_fin);
                    if($Validateperiodo == ""){
                        $consulta = DB::table('periodolectivo')->where('anio_inicio', $request->anio_inicio)->where('anio_fin', $request->anio_fin)->where('eliminado', [0])->first();
                        if($consulta){
                            return response()->json(['warning' => 'Ya existe un periodo con los datos ingresados.']);
                        }else{
                            DB::beginTransaction();
                            $nombre = ($request->anio_inicio .'-'. $request->anio_fin);
                            $periodo = DB::table('periodolectivo')->insertGetId([
                                'nombre' => $nombre,
                                'anio_inicio' => $request->anio_inicio,
                                'anio_fin' => $request->anio_fin,
                                'estado' => $request->estado
                            ]);

                            $contador = 0;
                            for ($i=0; $i < 2 ; $i++) {
                                $quim = ($i + 1);
                                $quimestre = DB::table('periodolectivo_quimestre')->insertGetId([
                                    'nombre' => "Quimestre $quim",
                                    'aniolectivo_fk' => $periodo
                                ]);

                                for ($x=0; $x < 2 ; $x++) {
                                    $par = ($x + 1);
                                    DB::table('periodolectivo_parcial')->insert([
                                        'nombre' => "Parcial $par",
                                        'quimestre_fk' => $quimestre,
                                        'periodolectivo_fk' => $periodo
                                    ]);
                                }
                                $contador++;
                            }

                            if($periodo && ($contador == 2)){
                                DB::commit();
                                return response()->json(['success' => 'Periodo creado con éxito.']);
                            }else{
                                DB::rollback();
                                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                            }
                        }
                    }else{
                        if($request->anio_inicio == $request->anio_fin){
                            return response()->json(['warning' => 'El periodo no debe tener el mismo año.']);
                        }
                        if($request->anio_inicio > $request->anio_fin){
                            return response()->json(['warning' => 'El periodo inicio no puede mayor al siguiente.']);
                        }
                    }
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function editarPeriodo(Request $request){
            $consulta = DB::table('periodolectivo')->where('anio_inicio', $request->anio_inicio)->where('anio_fin', $request->anio_fin)->where('eliminado', [0])->where('id', '!=', $request->id)->first();
            if($consulta){
                return response()->json(['warning' => 'Ya existe un periodo con los datos ingresados.']);
            }else{
                $activos = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', [0])->count();
                if($activos > 0 && $request->estado == 1){
                    return response()->json(['warning' => 'No se puede cambiar estado del periodo, Ya existe uno "Activo".']);
                }else{
                    $Validateperiodo = $this->validatePeriodos($request->anio_inicio, $request->anio_fin);
                    if($Validateperiodo == ""){
                        $nombre = ($request->anio_inicio .'-'. $request->anio_fin);
                        $actual = DB::table('periodolectivo')->where('nombre',$nombre)->where('estado', $request->estado)->first();
                        if($actual){
                            return response()->json(['success' => 'Periodo modificado con éxito.']);
                        }else{
                            DB::beginTransaction();
                            $periodo = DB::table('periodolectivo')->where('id', $request->id)->update([
                                'nombre' => $nombre,
                                'anio_inicio' => $request->anio_inicio,
                                'anio_fin' => $request->anio_fin,
                                'estado' => $request->estado,
                            ]);

                            if($periodo){
                                DB::commit();
                                return response()->json(['success' => 'Periodo modificado con éxito.']);
                            }else{
                                DB::rollback();
                                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                            }
                        }
                    }else{
                        if($request->anio_inicio == $request->anio_fin){
                            return response()->json(['warning' => 'El periodo no debe tener el mismo año.']);
                        }
                        if($request->anio_inicio > $request->anio_fin){
                            return response()->json(['warning' => 'El periodo inicio no puede mayor al siguiente.']);
                        }
                    }
                }
            }
        }
        public function eliminarPeriodo(Request $request){
            $consulta = DB::table('periodolectivo')->where('id', $request->id)->first();
            if($consulta){
                DB::beginTransaction();
                $asignatura = DB::table('periodolectivo')->where('id',$request->id)->update([
                    'estado' => 0,
                    'eliminado' => 1,
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Periodo eliminado con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }

        public function cerrarParcialPeriodo(Request $request){
            DB::beginTransaction();
            $success = DB::table('periodolectivo_parcial')->where('id', $request->idparcial)->update([
                'activo' => 0
            ]);
            DB::table('periodolectivo_quimestre')->where('aniolectivo_fk', $request->idperiodo)->update([
                'activo' => 0
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Parcial cerrado con éxito.']);
            }else{
                DB::rollBack();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }

        public function abrirParcialPeriodo(Request $request){
            $validateuniqueactivo = DB::table('periodolectivo_parcial')->where('id', '!=', $request->idparcial)->where('activo', [1])->count();
            $validateuniqueactivoquim = DB::table('periodolectivo_quimestre')->where('aniolectivo_fk', '!=', $request->idperiodo)->where('activo', [1])->count();

            if($validateuniqueactivo > 0 && $validateuniqueactivoquim > 0){
                return response()->json(['error' => 'No se pudo abrir por que ya existe un parcial abierto, por favor recargar la pagina.']);
            }else{
                if($validateuniqueactivo == 0){
                    DB::beginTransaction();
                    $success = DB::table('periodolectivo_parcial')->where('id', $request->idparcial)->update([
                        'activo' => 1
                    ]);

                    $successq = DB::table('periodolectivo_quimestre')->where('id', $request->idquimestre)->update([
                        'activo' => 1
                    ]);

                    if($success && $successq){
                        DB::commit();
                        return response()->json(['success' => 'Parcial abierto con éxito.']);
                    }else{
                        DB::rollBack();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                }else{
                    return response()->json(['error' => 'No se pudo abrir por que ya existe un parcial abierto, por favor recargar la pagina.']);
                }
            }
        }
    //##############################################################################

    //############################## ASIGNATURAS ##################################
        public function viewAsignaturas(Request $request){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralAsignatura')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){
                if(System::periodoActivo() == true){
                    $docentes = DB::table('usuario')->where('tipo_usuario',[3])->where('eliminado',[0])->where('estado', [1])->get();

                    $texto = $request->get('texto');
                    $asignaturas = DB::table('asignaturas')
                        // ->leftJoin('asignaturas_asignacion_docentes','asignaturas.id','=','asignaturas_asignacion_docentes.asignatura_fk')
                        ->select(
                            'asignaturas.*'
                            // 'asignaturas_asignacion_docentes.docente_fk'
                        )
                        ->where('asignaturas.estado', [1])->where('asignaturas.eliminado', [0])
                        ->where(function($query) use ($texto){
                            $query->orWhere('nombre', 'LIKE', '%'.$texto.'%');
                            $query->orWhere('codigo_asignatura', 'LIKE', '%'.$texto.'%');
                        })
                        ->orderByDesc('id')
                    ->paginate(5);
                    $rank = $asignaturas->firstItem();

                    return view('pages.academico.asignaturas.asignaturas', compact('asignaturas', 'texto', 'rank','docentes'));
                }else{
                    return view('sinperiodoactio');
                }
            }else{
                return redirect()->route('home');
            }
        }
        public function crearAsignatura(Request $request){
            if($request->nombre != null){
                $color = System::randomColor();
                // return response()->json($color);
                $existe = DB::table('asignaturas')->where('slug' , Str::slug($request->nombre))->where('eliminado', [0])->first();
                if($existe){
                    return response()->json(['warning' => 'Ya existe una asignatura con el nombre ingresado.']);
                }else{
                    DB::beginTransaction();
                    $cod =  explode("-", $request->codigo_asignatura);
                    $asignatura = DB::table('asignaturas')->insertGetId([
                        'nombre' => $request->nombre,
                        'codigo_asignatura' => strtoupper($cod[0]),
                        'codigo_asignatura_num' => strtoupper($request->codigo_asignatura_num),
                        'color' => $color,
                        'slug' => Str::slug($request->nombre),
                        'created_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);
                    if($asignatura){
                        DB::commit();
                        return response()->json(['success' => 'Asignatura registrada con éxito.']);
                    }else{
                        DB::rollback();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function editarAsignatura(Request $request){
            $existe = DB::table('asignaturas')->where('slug' , Str::slug($request->nombre))->where('id','!=',$request->id)->where('eliminado', [0])->first();
            if($existe){
                return response()->json(['warning' => 'Ya existe una asignatura con el nombre ingresado.']);
            }else{
                DB::beginTransaction();
                $cod =  explode("-", $request->codigo_asignatura);
                $asignatura = DB::table('asignaturas')->where('id',$request->id)->update([
                    'nombre' => $request->nombre,
                    'codigo_asignatura' => strtoupper($cod[0]),
                    'slug' => Str::slug($request->nombre),
                    'updated_at' => $this->date_actual,
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Datos de asigatura modificados con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }

            }
        }
        public function eliminarAsignatura(Request $request){
            $consulta = DB::table('asignaturas')->where('id', $request->id)->first();
            if($consulta){
                DB::beginTransaction();
                $asignatura = DB::table('asignaturas')->where('id',$request->id)->update([
                    'estado' => 0,
                    'eliminado' => 1,
                    'updated_at' => $this->date_actual,
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Asignatura eliminada con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function listadoDocentes($identificador){
            $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

            $docentes = DB::table('usuario')
                ->where('estado', [1])
                ->where('eliminado', [0])
                ->where('tipo_usuario', [3])
            ->get();

            $consulta = DB::table('usuario')
                ->leftJoin('asignaturas_asignacion_docentes as relacion','usuario.id','=','relacion.docente_fk')
                ->select('relacion.*')
                ->where('relacion.asignatura_fk', $identificador)
                ->where('relacion.periodo_fk', $periodo_activo->id)
            ->get();

            return response()->json([$docentes, $consulta]);
        }
        public function asignaturaRelacionDocentes(Request $request){
            $consulta = DB::table('asignaturas')->where('id', $request->asignatura_fk)->first();
            if($consulta){
                $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
                DB::beginTransaction();

                $contador = 0;
                $docentes = json_decode($request->docentes);
                foreach ($docentes as $value) {
                    DB::table('asignaturas_asignacion_docentes')->insert([
                        'asignatura_fk'=> $request->asignatura_fk,
                        'docente_fk' => $value->id_docente,
                        'periodo_fk' => $periodo_activo->id,
                        'token' => Str::random(40)
                    ]);
                    $contador++;
                }

                if(sizeof($docentes) == $contador){
                    DB::commit();
                    return response()->json(['success' => 'Docentes asginados con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuela a intentarlo.']);
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuela a intentarlo.']);
            }
        }
        public function deletAsignaturaRelacionDocentes(Request $request){
            $existe = DB::table('asignaturas_asignacion_docentes')->where('id' , $request->idasignacion)->first();
            if($existe){
                DB::beginTransaction();
                $success = DB::table('asignaturas_asignacion_docentes')->where('id' , $request->idasignacion)->delete();
                if($success){
                    DB::commit();
                    return response()->json(['success' => 'Registro eliminado con éxito.']);
                }else{
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }

            }else{
                return response()->json(['warning' => 'No se encontro el registro, por favor cerrar el modal y volver a abrir.']);
            }
        }
        //PDF
        public function generatePDFAsignaturas(Request $request){
            $texto = $request->get('texto');
            $asignaturas = DB::table('asignaturas')
                ->where('estado', [1])->where('eliminado', [0])
                ->where(function($query) use ($texto){
                    $query->orWhere('nombre', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('codigo_asignatura', 'LIKE', '%'.$texto.'%');
                })
                ->orderByDesc('id')
            ->get();
            return $asignaturas;
        }
    //##############################################################################

    //################################# CURSOS #####################################
        public function viewCursos(Request $request){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralCursos')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){
                if(System::periodoActivo() == true){
                    $texto = $request->get('texto');
                    $cursos = DB::table('cursos')
                        ->where('estado', [1])->where('eliminado', [0])
                        ->where(function($query) use ($texto){
                            $query->orWhere('nombre', 'LIKE', '%'.$texto.'%');
                            $query->orWhere('paralelo', 'LIKE', '%'.$texto.'%');
                            $query->orWhere('nivel', 'LIKE', '%'.$texto.'%');
                        })
                        ->orderByDesc('id')
                    ->paginate(5);
                    $rank = $cursos->firstItem();

                    return view('pages.academico.cursos.cursos', compact('cursos', 'texto', 'rank'));
                }else{
                    return view('sinperiodoactio');
                }
            }else{
                return redirect()->route('home');
            }
        }
        public function loadCursos(){
            $consulta = DB::table('cursos')->where('estado', [1])->where('eliminado', [0])->get();

            return response()->json($consulta);
        }
        public function crearCurso(Request $request){
            $existe = DB::table('cursos')->where('slug' , Str::slug($request->nombre))->where('nivel', $request->nivel)->where('paralelo', $request->paralelo)->where('eliminado', [0])->first();

            if($existe){
                return response()->json(['warning' => 'Ya existe un curso con los datos ingresados.']);
            }else{
                DB::beginTransaction();
                $asignatura = DB::table('cursos')->insert([
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre),
                    'nivel' => $request->nivel,
                    'paralelo' => $request->paralelo,
                    'created_at' => $this->date_actual,
                    'creador_fk' => Auth::user()->id
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Curso registrado con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            }
        }
        public function editarCurso(Request $request){
            // $consulta =  DB::table('asignaturas')->where('id',$request->id)->first();
            $existe = DB::table('cursos')->where('slug' , Str::slug($request->nombre))->where('id','!=',$request->id)->where('eliminado', [0])->first();

            if($existe){
                return response()->json(['warning' => 'Ya existe un curso con el nombre ingresado.']);
            }else{
                DB::beginTransaction();
                $asignatura = DB::table('cursos')->where('id',$request->id)->update([
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre),
                    'updated_at' => $this->date_actual,
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Datos de curso modificados con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }

            }
        }
        public function eliminarCurso(Request $request){
            $consulta = DB::table('cursos')->where('id', $request->id)->first();
            if($consulta){
                DB::beginTransaction();
                $asignatura = DB::table('cursos')->where('id',$request->id)->update([
                    'estado' => 0,
                    'eliminado' => 1,
                    'updated_at' => $this->date_actual,
                ]);

                if($asignatura){
                    DB::commit();
                    return response()->json(['success' => 'Curso eliminado con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        //asignacion asignatuas a cursos
        public function listadoAsignatura($identificador){
            $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

            $asignaturas = DB::table('asignaturas')
                ->leftJoin('asignaturas_asignacion_docentes as relacion','asignaturas.id','=','relacion.asignatura_fk')
                ->leftJoin('usuario','relacion.docente_fk','=','usuario.id')
                ->select(
                    'asignaturas.*',
                    'relacion.id as id_relacion_docente_materia',
                    'usuario.apellido_paterno as usuario_papellido',
                    'usuario.apellido_materno as usuario_sapellido',
                    'usuario.primer_nombre as usuario_pnombre',
                    'usuario.segundo_nombre as usuario_snombre'
                )
                ->where('asignaturas.estado', [1])
                ->where('asignaturas.eliminado', [0])
                ->where('relacion.periodo_fk', $periodo_activo->id)
                ->orderBy('asignaturas.nombre')
            ->get();

            $consulta = DB::table('asignaturas')
                ->leftJoin('cursos_asignacion_materias as relacion','asignaturas.id','=','relacion.asignatura_fk')
                ->select('relacion.*')
                ->where('asignaturas.estado', [1])
                ->where('asignaturas.eliminado', [0])
                ->where('relacion.periodo_fk', $periodo_activo->id)
                ->where('relacion.curso_fk', $identificador)
            ->get();


            return response()->json([$asignaturas, $consulta]);
        }
        public function asignaturaRelacionMaterias(Request $request){
            $consulta = DB::table('cursos')->where('id', $request->curso_fk)->first();
            if($consulta){
                $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
                DB::beginTransaction();
                $contador = 0;
                $asignaturas = json_decode($request->materias);
                foreach ($asignaturas as $value) {
                    DB::table('cursos_asignacion_materias')->insert([
                        'curso_fk' => $request->curso_fk,
                        'asignatura_fk'=> $value->id_asignatura,
                        'asign_docmateria_fk' => $value->id_docmateria,
                        'periodo_fk' => $periodo_activo->id,
                        'token' => Str::random(40)
                    ]);
                    $contador++;
                }

                if(sizeof($asignaturas) == $contador){
                    DB::commit();
                    if(sizeof($asignaturas) <= 0){
                        return response()->json(['success' => 'Asignaturas eliminadas con éxito.']);
                    }else{
                        return response()->json(['success' => 'Asignaturas asginadas con éxito.']);
                    }
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuela a intentarlo.']);
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuela a intentarlo.']);
            }
        }
        public function deletAsignaturaRelacionCursos(Request $request){
            $existe = DB::table('cursos_asignacion_materias')->where('id' , $request->idasignacion)->first();
            if($existe){
                DB::beginTransaction();
                $success = DB::table('cursos_asignacion_materias')->where('id' , $request->idasignacion)->delete();
                if($success){
                    DB::commit();
                    return response()->json(['success' => 'Registro eliminado con éxito.']);
                }else{
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }

            }else{
                return response()->json(['warning' => 'No se encontro el registro, por favor cerrar el modal y volver a abrir.']);
            }
        }

        //PDF
        public function generatePDFCursosAsing($id){
            $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();
            $curso = DB::table('cursos')->where('id', $id)->first();

            $asignaturas = DB::table('asignaturas')
                ->leftJoin('cursos_asignacion_materias as asign','asignaturas.id','=', 'asign.asignatura_fk')

                ->leftJoin('asignaturas_asignacion_docentes as doc','asign.asign_docmateria_fk','=', 'doc.id')
                ->leftJoin('usuario','doc.docente_fk','=', 'usuario.id')

                ->select(
                    'asignaturas.*',
                    'usuario.apellido_paterno as papellido',
                    'usuario.apellido_materno as sapellido',
                    'usuario.primer_nombre as pnombre',
                    'usuario.segundo_nombre as snombre'
                )
                ->where('asignaturas.estado', [1])
                ->where('asignaturas.eliminado', [0])
                ->where('asign.curso_fk', $id)
                ->where('asign.periodo_fk', $periodo_activo->id)
                ->orderBy('asignaturas.nombre')
            ->get();

            // dd($asignaturas);
            $nombre = strtoupper($curso->nombre.' '.$curso->nivel.' "'.$curso->paralelo.'"');

            $pdf = PDF::loadView('pages.academico.cursos.PDF-curso_asignaturas', compact('curso','asignaturas','nombre', 'periodo_activo'));

            return $pdf->stream("$nombre - ASIGNATURAS.pdf");
        }
    //##############################################################################

    //############################### MATRICULAS ###################################
        public function viewMatriculas(Request $request){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralMatricula')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){
                if(System::periodoActivo() == true){
                    $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

                    $periodos = DB::table('periodolectivo')->where('estado', [1])->where('eliminado', [0])->get();
                    $cursos = DB::table('cursos')->where('eliminado', [0])->get();

                    $texto = trim($request->get('texto'));

                    $matriculas = DB::table('matricula')
                        ->leftJoin('usuario','matricula.usuario_fk','=','usuario.id')
                        ->leftJoin('periodolectivo','matricula.periodo_fk','=','periodolectivo.id')
                        ->leftJoin('cursos','matricula.curso_fk','=','cursos.id')
                        ->select(
                            'matricula.*',
                            'matricula.id as matricula_id',
                            //usuario
                            'usuario.token as usuario_token',
                            'usuario.primer_nombre as usuario_pnombre',
                            'usuario.segundo_nombre as usuario_snombre',
                            'usuario.apellido_paterno as usuario_papellido',
                            'usuario.apellido_materno as usuario_sapellido',
                            'usuario.cedula as usuario_cedula',
                            //periodo
                            'periodolectivo.nombre as periodo_nombre',
                            //curso
                            'cursos.nombre as curso_nombre',
                            'cursos.nivel as curso_nivel',
                            'cursos.paralelo as curso_paralelo',
                        )
                        ->where('matricula.estado', [1])
                        ->where('matricula.eliminado', [0])
                        ->where('matricula.periodo_fk', $periodo_activo->id)
                        ->where(function($query) use ($texto){
                            $query->orWhere('usuario.apellido_paterno','LIKE', '%'.$texto.'%' );
                            $query->orWhere('usuario.apellido_materno','LIKE', '%'.$texto.'%' );
                            $query->orWhere('usuario.primer_nombre','LIKE', '%'.$texto.'%' );
                            $query->orWhere('usuario.segundo_nombre','LIKE', '%'.$texto.'%' );
                            $query->orWhere('cursos.nombre','LIKE', '%'.$texto.'%' );
                            $query->orWhere('cursos.nivel','LIKE', '%'.$texto.'%' );
                            $query->orWhere('cursos.paralelo','LIKE', '%'.$texto.'%' );
                            $query->orWhere('periodolectivo.nombre','LIKE', '%'.$texto.'%' );
                        })
                        ->orderByDesc('matricula.id')
                        ->orderByDesc('matricula.id')
                        ->orderByDesc('usuario_papellido')
                        ->orderByDesc('usuario_sapellido')
                        ->orderByDesc('usuario_pnombre')
                        ->orderByDesc('usuario_snombre')
                    ->paginate(10);

                    // dd($matriculas);
                    $rank = $matriculas->firstItem();

                    return view('pages.academico.matriculas.matriculas', compact('cursos', 'periodos', 'matriculas', 'rank', 'texto'));
                }else{
                    return view('sinperiodoactio');
                }
            }else{
                return redirect()->route('home');
            }
        }
        public function crearAsignMatriculas(Request $request){
            if($request->usuario_fk != null){
                $existe = DB::table('matricula')
                    ->where('usuario_fk', $request->usuario_fk)
                    ->where('periodo_fk', $request->periodo_fk)
                    ->where('curso_fk', $request->curso_fk)
                    ->where('estado', 1)
                    ->where('eliminado', 0)
                ->first();
                if($existe){
                    return response()->json(['warning' => 'Ya existe un registro con los datos ingresados.']);
                }else{
                    DB::beginTransaction();

                    $existe = DB::table('matricula')
                        ->where('periodo_fk', $request->periodo_fk)
                        ->where('curso_fk', $request->curso_fk)
                        ->where('estado', [1])
                        ->where('eliminado', [0])
                    ->get();

                    $success = DB::table('matricula')->insertGetId([
                        'fecha_matricula' => date('Y-m-d'),
                        'usuario_fk' => $request->usuario_fk,
                        'periodo_fk' =>  $request->periodo_fk,
                        'curso_fk' => $request->curso_fk
                    ]);

                    if (sizeof($existe) > 0) {
                        $materias = DB::table('cursos_asignacion_materias')
                            ->where('cursos_asignacion_materias.periodo_fk', $request->periodo_fk)
                            ->where('cursos_asignacion_materias.curso_fk', $request->curso_fk)
                        ->get();
                        foreach ($materias as $materia) {
                            $actividades = DB::table('actividades_parcial')
                                ->where('actividades_parcial.clase_fk', $materia->id)
                                ->where('actividades_parcial.estado', [1])
                                ->where('actividades_parcial.eliminado', [0])
                            ->get();

                            foreach ($actividades as $actividad) {
                                DB::table('actividades_calificaciones_matriculados')->insertGetId([
                                    'parcial_fk' => $actividad->parcial_fk,
                                    'clase_fk' => $materia->id,
                                    'curso_fk' => $request->curso_fk,

                                    'matriculado_fk' => $success,
                                    'actividad_fk' => $actividad->id,
                                    'created_at' => $this->date_actual,
                                    'updated_at' => $this->date_actual,
                                    'creador_fk' => Auth::user()->id
                                ]);
                            }

                            $quimestres = DB::table('periodolectivo_quimestre')->where('aniolectivo_fk', $request->periodo_fk)->get();

                            foreach ($quimestres as $datos_quimestre) {
                                $parciales = DB::table('periodolectivo_parcial')->where('quimestre_fk', $datos_quimestre->id)->get();

                                foreach ($parciales as $datos_parciales) {
                                    $verificacion_calificacion_parcial = DB::table('calificaciones_parcial_matriculados')
                                        ->where('estado', 1)
                                        ->where('eliminado', 0)
                                        ->where('parcial_fk', $datos_parciales->id)
                                        ->where('clase_fk', $materia->id)
                                        ->where('curso_fk', $request->curso_fk)
                                        ->where('matriculado_fk', $success)
                                        ->first();

                                    if ($verificacion_calificacion_parcial == null) {
                                        DB::table('calificaciones_parcial_matriculados')->insertGetId([
                                            'parcial_fk' => $datos_parciales->id,
                                            'clase_fk' => $materia->id,
                                            'curso_fk' => $request->curso_fk,

                                            'matriculado_fk' => $success,
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
                                    ->where('clase_fk', $materia->id)
                                    ->where('curso_fk', $request->curso_fk)
                                    ->where('matriculado_fk', $success)
                                    ->first();

                                $verificacion_calificacion_final = DB::table('calificaciones_quimestre_matriculados')
                                    ->where('estado', 1)
                                    ->where('eliminado', 0)
                                    ->where('quimestre_fk', $datos_quimestre->id)
                                    ->where('clase_fk', $materia->id)
                                    ->where('curso_fk', $request->curso_fk)
                                    ->where('matriculado_fk', $success)
                                    ->first();

                                if ($verificacion_calificacion_examen == null) {
                                    DB::table('calificaciones_examen_quimestral_matriculados')->insertGetId([
                                        'quimestre_fk' => $datos_quimestre->id,
                                        'clase_fk' => $materia->id,
                                        'curso_fk' => $request->curso_fk,

                                        'matriculado_fk' => $success,
                                        'created_at' => $this->date_actual,
                                        'updated_at' => $this->date_actual,
                                        'creador_fk' => Auth::user()->id
                                    ]);
                                }

                                if ($verificacion_calificacion_final == null) {
                                    DB::table('calificaciones_quimestre_matriculados')->insertGetId([
                                        'quimestre_fk' => $datos_quimestre->id,
                                        'clase_fk' => $materia->id,
                                        'curso_fk' => $request->curso_fk,

                                        'matriculado_fk' => $success,
                                        'created_at' => $this->date_actual,
                                        'updated_at' => $this->date_actual,
                                        'creador_fk' => Auth::user()->id
                                    ]);
                                }
                            }
                        }
                    }

                    if($success){
                        DB::commit();
                        return response()->json(['success'=> 'Matrícula registrada con éxito.']);
                    }else{
                        DB::rollback();
                        return response()->json(['error'=> 'Algo salió mal, vuelva a intentarlo.']);
                    }
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function eliminarAsignMatriculas(Request $request){
            DB::beginTransaction();
            $success = DB::table('matricula')->where('id', $request->id)->update([
                'estado' => 0,
                'eliminado' => 1
            ]);
            if($success){
                DB::commit();
                return response()->json(['success' => 'Matrícula eliminada con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        //PDF
        public function generatePDFMatriculasEstudiante($matricula_fk, $usuario_fk, $usuario_token){
            $existe = DB::table('matricula')->where('id', $matricula_fk)->first();
            $consulta = DB::table('usuario')->where('id', $usuario_fk)->where('token', $usuario_token)->first();
            if($existe && $consulta){
                $matricula =  DB::table('matricula')
                    ->leftJoin('cursos', 'matricula.curso_fk', 'cursos.id')
                    ->select('cursos.*')
                    ->where('matricula.id', $matricula_fk)
                ->first();
                $estudiante = DB::table('usuario')->where('id', $usuario_fk)->where('token', $usuario_token)->first();
                $periodo_activo = DB::table('periodolectivo')->where('estado', 1)->where('eliminado', 0)->first();

                $nombrepdf = "MATRICULA DE ESTUDIANTE C.I: $estudiante->cedula.pdf";

                $pdf = PDF::loadView('pages.academico.matriculas.PDF-matricula_estudiante',[
                    'curso' => $matricula,
                    'estudiante' => $estudiante,
                    'periodo_activo' => $periodo_activo,
                    'nombrepdf' => $nombrepdf
                ]);
                return $pdf->stream($nombrepdf);
            }else{
                return redirect()->route('home');
            }
        }
        //consulta
        public function consultaUsuarioCedula($cedula){
            $consulta = User::where('cedula', $cedula)->where('tipo_usuario', [2])->where('eliminado', [0])->first();
            if($consulta){
                return response()->json(['success' => $consulta]);
            }else{
                return response()->json(['error' => 'No se encontro usuario con la cédula ingresada.']);
            }
        }
    //##############################################################################

    //############################### ESTUDIANTES ##################################
        public function viewEstudiantes(Request $request){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralEstudiantes')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){

                $texto = $request->get('texto');
                $estudiantes = DB::table('usuario')
                    ->where('eliminado', [0])->where('tipo_usuario', [2])
                    ->where(function($query) use ($texto){
                        // return $query
                        $query->orWhere('cedula', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('primer_nombre', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('segundo_nombre', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('apellido_paterno', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('apellido_materno', 'LIKE', '%'.$texto.'%');
                    })
                    ->orderBy('primer_nombre')->orderBy('segundo_nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno')
                ->paginate(5);
                $rank = $estudiantes->firstItem();

                return view('pages.academico.estudiantes.estudiantes', compact('estudiantes', 'rank', 'texto'));
            }else{
                return redirect()->route('home');
            }
        }
        public function createEstudiante(Request $request){
            $validate_cedula = $this->validateCedula($request->cedula);
            $validate_email = $this->validateEmail($request->correo);
            $validateExistCedula = $this->validateExistCedula($request->cedula);
            $validateExistEmail = $this->validateExistEmail($request->correo);

            if($validate_email == "success" && $validate_cedula == "success"){
                if($validateExistEmail == "success" && $validateExistCedula == "success"){
                    DB::beginTransaction();

                    $usuario = DB::table('usuario')->insert([
                        'cedula' => $request->cedula,
                        'primer_nombre' => $request->primernombre,
                        'segundo_nombre' => $request->segundonombre,
                        'apellido_paterno' => $request->primerapellido,
                        'apellido_materno' => $request->segundoapellido,
                        'token' => Str::random(20),
                        'tipo_usuario' => 2,
                        'email' => $request->correo,
                        'password' => Hash::make($request->cedula),
                        'created_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);

                    if($usuario){
                        DB::commit();
                        return response()->json(['success' => 'Estudiante creado con éxito.']);
                    }else{
                        DB::rollBack();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                }else{
                    if($validateExistEmail == "error" && $validateExistCedula == "error"){
                        return response()->json(['error' => 'Ya existe un usuario con la cédula y correo ingresados.']);
                    }
                    if($validateExistEmail == "error"){return response()->json(['error' => 'Ya existe un usuario con el correo ingresados']); }
                    if($validateExistCedula == "error"){return response()->json(['error' => 'Ya existe un usuario con  la cédula ingresada.']); }
                }
            }else{
                if($validate_email == "error" && $validate_cedula == "error"){
                    return response()->json(['error' => 'Cédula y Correo no son validos.']);
                }
                if($validate_email == "error"){return response()->json(['error' => 'Correo no valido.']); }
                if($validate_cedula == "error"){return response()->json(['error' => 'Cédula no valida.']); }
            }
        }
        public function postEditEstudianteFetch(Request $request){
            $usuario = DB::table('usuario')->where('id', $request->id)->where('token', $request->token)->first();
            if($usuario){
                $validate_email = $this->validateEmail($request->correo); $validate_cedula = $this->validateCedula($request->cedula);
                if($validate_email == "success" && $validate_cedula == "success"){

                    $existencia_ced = DB::table('usuario')->where('cedula', $request->cedula)->where('id', '!=' ,$request->id)->where('eliminado', [0])->first();
                    $existencia_ema = DB::table('usuario')->where('email', $request->correo)->where('id', '!=' ,$request->id)->where('eliminado', [0])->first();

                    if($existencia_ced == null && $existencia_ema == null){
                        DB::beginTransaction();
                        $success = DB::table('usuario')->where('id', $usuario->id)->update([
                            'cedula' => $request->cedula,
                            'primer_nombre' => $request->primernombre,
                            'segundo_nombre' => $request->segundonombre,
                            'apellido_paterno' => $request->primerapellido,
                            'apellido_materno' => $request->segundoapellido,
                            'email' => $request->correo,
                            'creador_fk' => Auth::user()->id,
                            'updated_at' => $this->date_actual
                        ]);

                        if($success){
                            DB::commit();
                            return response()->json(['success' => 'Estudiante actualizado con éxito.']);
                        }else{
                            DB::rollBack();
                            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                        }
                    }else{
                        if($existencia_ced && $existencia_ema){return response()->json(['error'=> 'La cédula y el correo ingresados ya existen.']); }
                        if($existencia_ced){ return response()->json(['error'=> 'La cédula ingresada ya existen.']); }
                        if($existencia_ema){  return response()->json(['error'=> 'El correo ingresado ya existen.']); }
                    }
                }else{
                    if($validate_email == "error" && $validate_cedula == "error"){
                        return response()->json(['error' => 'Cédula y Correo no son validos.']);
                    }
                    if($validate_email == "error"){return response()->json(['error' => 'Correo no valido.']); }
                    if($validate_cedula == "error"){return response()->json(['error' => 'Cédula no valida.']); }
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo1.']);
            }
        }
        public function postBanearEstudianteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 0,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Estudiante deshabilitado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function postHabilitarEstudianteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 1,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Estudiante habilitado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function postDeleteEstudianteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 0,
                'eliminado' => 1,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Estudiante eliminado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }

        //PDF
        public function generatePDFEstudiantes(Request $request){
            $texto = trim($request->get('texto'));
            $estudiantes = DB::table('usuario')
                ->where('estado', [1])->where('eliminado', [0])->where('tipo_usuario', [2])
                ->where(function($query) use ($texto){
                    // return $query
                    $query->orWhere('cedula', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('primer_nombre', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('segundo_nombre', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('apellido_paterno', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('apellido_materno', 'LIKE', '%'.$texto.'%');
                })
                ->orderBy('primer_nombre')->orderBy('segundo_nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno')
            ->get();

            return $estudiantes;
            $pdf = PDF::loadView('pages.academico.asignaturas.PDF-asignaturas',[
                'estudiantes' => $estudiantes,
            ]);
            return $pdf->stream("ESTUDIANTES.pdf");
        }
    //##############################################################################

    //################################## DOCENTES ##################################
        public function viewDocentes(Request $request){
            $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralDocentes')->first();
            if($role || (Auth::user()->tipo_usuario == 5)){

                $texto = trim($request->get('texto'));
                $docentes = DB::table('usuario')
                    ->where('eliminado', [0])->where('tipo_usuario', [3])
                    ->where(function($query) use ($texto){
                        // return $query
                        $query->orWhere('cedula', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('primer_nombre', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('segundo_nombre', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('apellido_paterno', 'LIKE', '%'.$texto.'%');
                        $query->orWhere('apellido_materno', 'LIKE', '%'.$texto.'%');
                    })
                    ->orderBy('primer_nombre')->orderBy('segundo_nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno')
                ->paginate(5);
                $rank = $docentes->firstItem();

                return view('pages.academico.docentes.docentes', compact('docentes', 'rank', 'texto'));
            }else{
                return redirect()->route('home');
            }
        }
        public function createDocente(Request $request){
            // return response()->json($request);
            $validate_email = $this->validateEmail($request->correo); $validate_cedula = $this->validateCedula($request->cedula);
            $validateExistEmail = $this->validateExistEmail($request->correo); $validateExistCedula = $this->validateExistCedula($request->cedula);
            if($validate_email == "success" && $validate_cedula == "success"){
                if($validateExistEmail == "success" && $validateExistCedula == "success"){
                    DB::beginTransaction();

                    $usuario = DB::table('usuario')->insert([
                        'cedula' => $request->cedula,
                        'primer_nombre' => $request->primernombre,
                        'segundo_nombre' => $request->segundonombre,
                        'apellido_paterno' => $request->primerapellido,
                        'apellido_materno' => $request->segundoapellido,
                        'token' => Str::random(20),
                        'tipo_usuario' => 3,
                        'email' => $request->correo,
                        'password' => Hash::make($request->cedula),
                        'created_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);

                    if($usuario){
                        DB::commit();
                        return response()->json(['success' => 'Docente creado con éxito.']);
                    }else{
                        DB::rollBack();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                }else{
                    if($validateExistEmail == "error" && $validateExistCedula == "error"){
                        return response()->json(['error' => 'Ya existe un usuario con la cédula y correo ingresados.']);
                    }
                    if($validateExistEmail == "error"){return response()->json(['error' => 'Ya existe un usuario con el correo ingresados']); }
                    if($validateExistCedula == "error"){return response()->json(['error' => 'Ya existe un usuario con  la cédula ingresada.']); }
                }
            }else{
                if($validate_email == "error" && $validate_cedula == "error"){
                    return response()->json(['error' => 'Cédula y Correo no son validos.']);
                }
                if($validate_email == "error"){return response()->json(['error' => 'Correo no valido.']); }
                if($validate_cedula == "error"){return response()->json(['error' => 'Cédula no valida.']); }
            }
        }
        public function postEditDocenteFetch(Request $request){
            $usuario = DB::table('usuario')->where('id', $request->id)->where('token', $request->token)->first();
            if($usuario){
                $validate_email = $this->validateEmail($request->correo); $validate_cedula = $this->validateCedula($request->cedula);
                if($validate_email == "success" && $validate_cedula == "success"){

                    $existencia_ced = DB::table('usuario')->where('cedula', $request->cedula)->where('id', '!=' ,$request->id)->where('eliminado', [0])->first();
                    $existencia_ema = DB::table('usuario')->where('email', $request->correo)->where('id', '!=' ,$request->id)->where('eliminado', [0])->first();

                    if($existencia_ced == null && $existencia_ema == null){
                        DB::beginTransaction();
                        $success = DB::table('usuario')->where('id', $usuario->id)->update([
                            'cedula' => $request->cedula,
                            'primer_nombre' => $request->primernombre,
                            'segundo_nombre' => $request->segundonombre,
                            'apellido_paterno' => $request->primerapellido,
                            'apellido_materno' => $request->segundoapellido,
                            'email' => $request->correo,
                            'creador_fk' => Auth::user()->id,
                            'updated_at' => $this->date_actual
                        ]);

                        if($success){
                            DB::commit();
                            return response()->json(['success' => 'Docente actualizado con éxito.']);
                        }else{
                            DB::rollBack();
                            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                        }
                    }else{
                        if($existencia_ced && $existencia_ema){return response()->json(['error'=> 'La cédula y el correo ingresados ya existen.']); }
                        if($existencia_ced){ return response()->json(['error'=> 'La cédula ingresada ya existen.']); }
                        if($existencia_ema){  return response()->json(['error'=> 'El correo ingresado ya existen.']); }
                    }
                }else{
                    if($validate_email == "error" && $validate_cedula == "error"){
                        return response()->json(['error' => 'Cédula y Correo no son validos.']);
                    }
                    if($validate_email == "error"){return response()->json(['error' => 'Correo no valido.']); }
                    if($validate_cedula == "error"){return response()->json(['error' => 'Cédula no valida.']); }
                }
            }else{
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo1.']);
            }
        }
        public function postBanearDocenteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 0,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Docente deshabilitado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function postHabilitarDocenteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 1,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Docente habilitado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }
        public function postDeleteDocenteFetch(Request $request){
            DB::beginTransaction();
            $success = DB::table('usuario')->where('id', $request->id)->update([
                'estado' => 0,
                'eliminado' => 1,
                'updated_at' => $this->date_actual
            ]);

            if($success){
                DB::commit();
                return response()->json(['success' => 'Docente eliminado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }

        //PDF
        public function generatePDFDocentes(Request $request){
            $texto = trim($request->get('texto'));

            $docentes = DB::table('usuario')
                ->where('estado', [1])->where('eliminado', [0])->where('tipo_usuario', [3])
                ->where(function($query) use ($texto){
                    // return $query
                    $query->orWhere('cedula', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('primer_nombre', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('segundo_nombre', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('apellido_paterno', 'LIKE', '%'.$texto.'%');
                    $query->orWhere('apellido_materno', 'LIKE', '%'.$texto.'%');
                })
                ->orderBy('primer_nombre')->orderBy('segundo_nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno')
            ->get();

            return $docentes;

            $pdf = PDF::loadView('pages.academico.asignaturas.PDF-asignaturas',[
                'docentes' => $docentes,
            ]);
            return $pdf->stream("DOCENTES.pdf");
        }
    //##############################################################################
}

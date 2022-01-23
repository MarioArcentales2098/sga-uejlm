<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorAcademico;

Route::group(['middleware' => ['auth']], function () {
    //############################# PARALELOS #############################
    Route::get('/paralelos', [ControladorAcademico::class, 'viewParalelos'])->name('viewParalelos');

    //############################# PERIODO #############################
    Route::get('/periodo', [ControladorAcademico::class, 'viewPeriodos'])->name('viewPeriodos');
    Route::post('/periodo/crear/periodo/fetch', [ControladorAcademico::class, 'crearPeriodo']);
    Route::post('/periodo/editar/periodo/fetch', [ControladorAcademico::class, 'editarPeriodo']);
    Route::post('/periodo/eliminar/periodo/fetch', [ControladorAcademico::class, 'eliminarPeriodo']);

    Route::post('/periodo/cerrar/parcial/fetch', [ControladorAcademico::class, 'cerrarParcialPeriodo']);
    Route::post('/periodo/abrir/parcial/fetch', [ControladorAcademico::class, 'abrirParcialPeriodo']);

    //############################# ASIGNATURA #############################
    Route::get('/asignaturas', [ControladorAcademico::class, 'viewAsignaturas'])->name('viewAsignaturas');
    Route::post('/asignaturas/crear/asignatura/fetch', [ControladorAcademico::class, 'crearAsignatura']);
    Route::post('/asignaturas/editar/asignatura/fetch', [ControladorAcademico::class, 'editarAsignatura']);
    Route::post('/asignaturas/delete/asignatura/fetch', [ControladorAcademico::class, 'eliminarAsignatura']);
    //asign docentes 
    Route::get('/listado/docentes/{idCurso}', [ControladorAcademico::class, 'listadoDocentes']);
    Route::post('/asignaturas/asignacion/docentes/fetch', [ControladorAcademico::class, 'asignaturaRelacionDocentes']);
    Route::post('/asignaturas/delete/asignacion/docentes/fetch', [ControladorAcademico::class, 'deletAsignaturaRelacionDocentes']);

    //===== PDF CURSOS
    Route::get('/generar/pdf/asignaturas', [ControladorAcademico::class, 'generatePDFAsignaturas'])->name('generatePDFAsignaturas');
    //############################# CURSOS #############################
    Route::get('/cursos', [ControladorAcademico::class, 'viewCursos'])->name('viewCursos');
    Route::get('/cursos/lista/fetch', [ControladorAcademico::class, 'loadCursos']);
    Route::post('/cursos/crear/curso/fetch', [ControladorAcademico::class, 'crearCurso']);
    Route::post('/cursos/editar/curso/fetch', [ControladorAcademico::class, 'editarCurso']);
    Route::post('/cursos/delete/curso/fetch', [ControladorAcademico::class, 'eliminarCurso']);
    Route::post('/cursos/asignacion/cursos/materias/fetch', [ControladorAcademico::class, 'asignaturaRelacionMaterias']);
    Route::get('/listado/asignaturas/{idCurso}', [ControladorAcademico::class, 'listadoAsignatura']);
    Route::post('/cursos/delete/asignacion/asignaturas/fetch', [ControladorAcademico::class, 'deletAsignaturaRelacionCursos']);
    //===== PDF CURSOS
    Route::get('/generar/pdf/cursos/{id}', [ControladorAcademico::class, 'generatePDFCursosAsing'])->name('generatePDFCursosAsing');

    //############################# MATRICULAS #############################
    Route::get('/matriculas', [ControladorAcademico::class, 'viewMatriculas'])->name('viewMatriculas');
    Route::post('/matriculas/crear/registro/fetch', [ControladorAcademico::class, 'crearAsignMatriculas']);
    Route::post('/matriculas/eliminar/matricula/fetch', [ControladorAcademico::class, 'eliminarAsignMatriculas']);
    Route::get('/consulta/usuario/por/cedula/{buscador}', [ControladorAcademico::class, 'consultaUsuarioCedula']);
    //===== PDF MATRICULAS
    Route::get('/generar/pdf/matricula/{asign}/usuario/{id}/token/{token}', [ControladorAcademico::class, 'generatePDFMatriculasEstudiante'])->name('generatePDFMatriculasEstudiante');

    //############################# ESTUDIANTES #############################
    Route::get('/estudiantes', [ControladorAcademico::class, 'viewEstudiantes'])->name('viewEstudiantes');
    Route::post('/usuarios/crear/nuevo/estudiante/fetch', [ControladorAcademico::class, 'createEstudiante']);
    Route::post('/usuarios/editar/estudiante/fetch', [ControladorAcademico::class, 'postEditEstudianteFetch']); //crear nuevo estudiante
    Route::post('/usuarios/banear/estudiante/fetch', [ControladorAcademico::class, 'postBanearEstudianteFetch']); //deshabilitar estudiante
    Route::post('/usuarios/activar/estudiante/fetch', [ControladorAcademico::class, 'postHabilitarEstudianteFetch']); //habilitar estudiante    
    Route::post('/usuarios/eliminar/estudiante/fetch', [ControladorAcademico::class, 'postDeleteEstudianteFetch']); //eliminar estudiante
    //===== PDF ESTUDIANTES
    Route::get('/generar/pdf/estudiantes', [ControladorAcademico::class, 'generatePDFEstudiantes'])->name('generatePDFEstudiantes');

    //############################# DOCENTES #############################
    Route::get('/docentes', [ControladorAcademico::class, 'viewDocentes'])->name('viewDocentes');
    Route::post('/usuarios/crear/nuevo/docente/fetch', [ControladorAcademico::class, 'createDocente']);
    Route::post('/usuarios/editar/docente/fetch', [ControladorAcademico::class, 'postEditDocenteFetch']); //crear nuevo docente
    Route::post('/usuarios/banear/docente/fetch', [ControladorAcademico::class, 'postBanearDocenteFetch']); //deshabilitar docente
    Route::post('/usuarios/activar/docente/fetch', [ControladorAcademico::class, 'postHabilitarDocenteFetch']); //habilitar docente    
    Route::post('/usuarios/eliminar/docente/fetch', [ControladorAcademico::class, 'postDeleteDocenteFetch']); //eliminar docente
    //===== PDF DOCENTES
    Route::get('/generar/pdf/docentes', [ControladorAcademico::class, 'generatePDFDocentes'])->name('generatePDFDocentes');
});

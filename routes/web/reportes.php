<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorReportes;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/reportes', [ControladorReportes::class, 'viewReportes'])->name('viewReportes');

    Route::get('/reportes/buscar/estudiantes/clase/{id}/token/{token}', [ControladorReportes::class, 'searhEstudiantesInCurso']);


    //########################## ESTUDIANTES
    // Route::get('/reporte/estudiante/asistencias/clase/{select}', [ControladorReportes::class, 'reporteViewEstuadianteAsistenciaClase']);
    // Route::get('/reporte/estudiante/calificaciones/clase/{select}', [ControladorReportes::class, 'reporteViewEstuadianteCalificacionesClase']);

    Route::get('/reportes/buscar/asignaturas/estudiantes/curso/{curso_fk}' , [ControladorReportes::class, 'reporteSearchMateriaAlumnos']);

    //asistencia
    Route::get('/reporte/asistencia/clase/{clase}/{token}/{alumn}' , [ControladorReportes::class, 'generateReporteAsistenciaPDF']);

    Route::get('/reporte/asistencia/clase/estudiante/{clase}/{token}/{periodo}' , [ControladorReportes::class, 'generateReporteAsistenciaEstudiantePDF']);

    //calificaciones
    Route::get('/reporte/calificaciones/clase/{clase}/{token}/{alumn}' , [ControladorReportes::class, 'generateReporteCalificacionesPDF']);

    Route::get('/reportes/buscar/calificaciones-estudiantes/clase/{id}/token/{token}', [ControladorReportes::class, 'searhCalificacionesEstudianteCurso']);

    Route::get('/reportes/buscar/calificaciones-asignatura/clase', [ControladorReportes::class, 'searhCalificacionesAsignatura'])->name('calificacionesEstudianteXMateriaPDF');

    //nueva
    Route::get('/reportes/buscar/calificaciones-asignatura/clase/{alumn}/{clase}', [ControladorReportes::class, 'generateReporteCalificacionesGeneralPDF']);
    

});

<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorClases;
use App\Http\Controllers\ControladorClasesEstudiante;

Route::group(['middleware' => ['auth']], function () {

    Route::get('/clases', [ControladorClases::class, 'viewClases'])->name('viewClases');
    Route::get('/clases/detail/{id}/{token}', [ControladorClases::class, 'viewDetailClase'])->name('viewDetailClase');

    //####################### ASISTENCIAS
    Route::get('/clases/detail/estudiantes/{id}/{token}/periodo/{periodo}/{fecha}', [ControladorClases::class, 'estudiantesDetailClase']);
    Route::post('/clases/asistencia/post', [ControladorClases::class, 'claseAsistenciasPost']);
    Route::get('/clases/asistencia/justificar/{idregistro}', [ControladorClases::class, 'claseJustificarAsistencia']);

    //####################### CALIFICACIONES
    Route::get('/clases/detail/estudiantes-calificaciones/{id}/{token}/periodo/{periodo}', [ControladorClases::class, 'estudiantesDetailCalificaciones']);
    Route::get('/clases/detail/estudiantes-calificaciones-quimestres/{id}/{token}/periodo/{periodo}', [ControladorClases::class, 'estudiantesDetailCalificacionesQuimestre']);

    Route::post('/clases/crear/actividad/parcial/fetch', [ControladorClases::class, 'crearActividadParcial']);
    Route::post('/clases/eliminar/actividad/parcial/fetch', [ControladorClases::class, 'eliminarActividadParcial']);

    Route::post('/clases/crear/examen/quimestral/fetch', [ControladorClases::class, 'crearExamenQuimestral']);
    Route::post('/clases/eliminar/examen/quimestre/fetch', [ControladorClases::class, 'eliminarExamenQuimestral']);

    Route::post('/clases/actualizar/calificacion-actividad/parcial/fetch', [ControladorClases::class, 'actualizarCalificacionActividadXMatriculadoParcial']);
    Route::post('/clases/actualizar/calificacion-examen/quiemstre/fetch', [ControladorClases::class, 'actualizarCalificacionExamenQuimestre']);


    Route::post('/clases/actualizar/calificacion-general/parcial/fetch', [ControladorClases::class, 'actualizarCalificacionGeneral']);


    //####################### REPORTES
    // Route::get('/clases/detail/estudiantes/{id}/{token}', [ControladorClases::class, 'estudiantesDetailClaseReportes']);
    Route::get('/clases/{id}/token/{token}/generar/reporte/asistencias/{identificador}', [ControladorClases::class, 'generarReporteAsistenciasPDF']);
    Route::get('/clases/{id}/token/{token}/generar/reporte/calificaciones/{identificador}', [ControladorClases::class, 'generarReporteCalificacionesPDF']);
    Route::get('/clases/{id}/token/{token}/generar/reporte/calificaciones', [ControladorClases::class, 'generarReporteCalificacionesPDFAll']);

    Route::get('/clases/{id}/token/{token}/generar/reporte/calificaciones-actividades', [ControladorClases::class, 'generarReporteCalificacionesActividadesPDFAll']);













    //####################### CLASES ESTUDIANTE
    Route::get('/clases/estudiante', [ControladorClasesEstudiante::class, 'viewClasesestudiante'])->name('viewClasesestudiante');
    Route::get('/clases/estudiante/detail/{id}/{token}', [ControladorClasesEstudiante::class, 'viewDetailClaseEstudiante'])->name('viewDetailClaseEstudiante');

    Route::get('/clases/estudiante/detail/estudiantes/{id}/{token}/periodo/{periodo}', [ControladorClasesEstudiante::class, 'estudiantesDetailClaseEstudiante']);
    Route::get('/clases/estudiante/{id}/token/{token}/generar/reporte/calificaciones', [ControladorClasesEstudiante::class, 'generarReporteCalificacionesPDFAll']);

    Route::get('/clases/estudiante/detail/estudiantes-calificaciones/{id}/{token}/periodo/{periodo}', [ControladorClasesEstudiante::class, 'estudiantesDetailCalificacionesEstudiante']);
    Route::get('/clases/estudiante/detail/estudiantes-calificaciones-quimestres/{id}/{token}/periodo/{periodo}', [ControladorClasesEstudiante::class, 'estudiantesDetailCalificacionesQuimestreEstudiante']);

    Route::get('/clases/estudiante/generar/reporte/calificaciones-actividades/{id}/{token}/{periodo}', [ControladorClasesEstudiante::class, 'generarReporteCalificacionesActividadesEstudiante']);

});

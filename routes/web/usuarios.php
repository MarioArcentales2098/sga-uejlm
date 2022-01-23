<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorRoles;
use App\Http\Controllers\ControladorUsuarios;


Route::group(['middleware' => ['auth']], function () {
    //========== PERFIL USUARIO
    Route::get('/perfil/usuario', [ControladorUsuarios::class, 'perfilUsuario'])->name('perfilUsuario');
    Route::post('/perfil/usuario/editar/fetch', [ControladorUsuarios::class, 'perfilEditUsuario']);
    Route::post('/perfil/usuario/change/password/fetch', [ControladorUsuarios::class, 'perfilChangePassword']);


    //========== USUARIOS 
    Route::get('/usuarios/lista/usuarios', [ControladorUsuarios::class, 'viewUsuarios'])->name('viewUsuarios');
    Route::get('/usuarios/lista/usuarios/fetch', [ControladorUsuarios::class, 'getUsuariosFetch']); //consulta usuarios

    Route::post('/usuarios/crear/nuevo/usuario/fetch', [ControladorUsuarios::class, 'postCreateUsuarioFetch']); //crear nuevo usuario
    Route::post('/usuarios/editar/usuario/fetch', [ControladorUsuarios::class, 'postEditUsuarioFetch']); //crear nuevo usuario
    Route::post('/usuarios/banear/usuario/fetch', [ControladorUsuarios::class, 'postBanearUsuarioFetch']); //deshabilitar usuario
    Route::post('/usuarios/activar/usuario/fetch', [ControladorUsuarios::class, 'postHabilitarUsuarioFetch']); //habilitar usuario    
    Route::post('/usuarios/eliminar/usuario/fetch', [ControladorUsuarios::class, 'postDeleteUsuarioFetch']); //eliminar usuario

    //========== ROLES USUARIOS 
    Route::get('/roles/permisos', [ControladorRoles::class, 'viewRoles'])->name('viewRoles');
    Route::get('/roles/persmisos/load/roles', [ControladorRoles::class, 'loadRegistros']);
    Route::get('/roles/permisos/consulta/fetch', [ControladorRoles::class, 'loadRoles']);
    Route::post('/roles/permisos/crear/rol/fetch', [ControladorRoles::class, 'createRolPost']);

    //ASIGN ROLS
    Route::get('/roles/permisos/asign/{id}/{slug}', [ControladorRoles::class, 'asignRolEdit'])->name('asignRolEdit');
    Route::post('/roles/permisos/asign/delete/usuario', [ControladorRoles::class, 'asignRolDeleteUsuarioPost'])->name('asignRolDeleteUsuarioPost');
    Route::post('/roles/permisos/asign/usuarios', [ControladorRoles::class, 'asignUsuariosRolPost'])->name('asignUsuariosRolPost');

    //EDIT ROL
    Route::get('/roles/permisos/edit/consulta/fetch/{identificador}', [ControladorRoles::class, 'loadRolesEdit']);
    Route::post('/roles/permisos/edit/rol/fetch', [ControladorRoles::class, 'editRolsPost']);
    

    //DELETE ROL
    Route::post('/roles/permisos/delete/fetch', [ControladorRoles::class, 'deleteRol']);
});

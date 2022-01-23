<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { if(Auth::guest()){ return view('welcome'); }else{ return redirect()->route('home'); }})->name('welcome');

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    //CONFIGURACION USUARIOS
    Route::get('/usuario/cambio/contrasena', 'HomeController@changePasswordUsuarios')->name('changePasswordUsuarios');
    Route::post('/usuario/cambio/contrasena/post', 'HomeController@changePasswordUsuariosPost');
});
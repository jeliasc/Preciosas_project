<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('usuariosIndex', [UsersController::class, 'index'])->name('usuariosIndex');
Route::get('crearUsuario', [UsersController::class, 'create'])->name('crearUsuario');
Route::post('insertarUsuario', [UsersController::class, 'store'])->name('insertarUsuario');
Route::get('editarUsuario/{id}',[UsersController::class, 'edit'])->name('editarUsuario');
Route::post('usuariosUpdate/{id}',[UsersController::class, 'update'])->name('usuariosUpdate');

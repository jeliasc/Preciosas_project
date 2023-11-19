<?php

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\RolesController;
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
    return view('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('usuariosIndex', [UsersController::class, 'index'])->name('usuariosIndex');
Route::get('crearUsuario', [UsersController::class, 'create'])->name('crearUsuario');
Route::post('insertarUsuario', [UsersController::class, 'store'])->name('insertarUsuario');
Route::get('editarUsuario/{id}',[UsersController::class, 'edit'])->name('editarUsuario');
Route::post('actualizarUsuarios/{id}',[UsersController::class, 'update'])->name('actualizarUsuarios');
Route::get('eliminarUsuario/{id}',[UsersController::class, 'destroy'])->name('eliminarUsuario');

Route::get('rolesIndex', [RolesController::class, 'index'])->name('rolesIndex');
Route::get('crearRole', [RolesController::class, 'create'])->name('crearRole');
Route::post('insertarRole', [RolesController::class, 'store'])->name('insertarRole');
Route::get('/editarRole/{id}', [RolesController::class, 'edit'])->name('editarRole');
Route::post('/actualizarRole/{id}', [RolesController::class, 'update'])->name('actualizarRole');
Route::get('eliminarRole/{id}',[RolesController::class, 'destroy'])->name('eliminarRole');

Route::get('categoriasIndex', [CategoriasController::class, 'index'])->name('categoriasIndex');
Route::post('insertarCategoria', [CategoriasController::class, 'store'])->name('insertarCategoria');
Route::get('/editarCategoria/{id}', [CategoriasController::class, 'edit'])->name('editarCategoria');
Route::post('/actualizarCategoria/{id}', [CategoriasController::class, 'update'])->name('actualizarCategoria');
Route::get('eliminarCategoria/{id}',[CategoriasController::class, 'destroy'])->name('eliminarCategoria');

Route::get('proveedoresIndex', [ProveedoresController::class, 'index'])->name('proveedoresIndex');
Route::post('insertarProveedor', [ProveedoresController::class, 'store'])->name('insertarProveedor');
Route::get('/editarProveedor/{id}', [ProveedoresController::class, 'edit'])->name('editarProveedor');
Route::post('/actualizarProveedor/{id}', [ProveedoresController::class, 'update'])->name('actualizarProveedor');
Route::get('eliminarProveedor/{id}',[ProveedoresController::class, 'destroy'])->name('eliminarProveedor');

Route::get('productosIndex', [ProductosController::class, 'index'])->name('productosIndex');
Route::get('crearProucto', [ProductosController::class, 'create'])->name('crearProucto');
Route::post('insertarProducto', [ProductosController::class, 'store'])->name('insertarProducto');
Route::get('/editarProducto/{id}', [ProductosController::class, 'edit'])->name('editarProducto');
Route::post('/actualizarProducto/{id}', [ProductosController::class, 'update'])->name('actualizarProducto');
Route::get('eliminarProducto/{id}',[ProductosController::class, 'destroy'])->name('eliminarProducto');




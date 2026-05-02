<?php

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VentasController;
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

Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('usuariosIndex', [UsersController::class, 'index'])->name('usuariosIndex');
Route::get('crearUsuario', [UsersController::class, 'create'])->name('crearUsuario');
Route::post('insertarUsuario', [UsersController::class, 'store'])->name('insertarUsuario');
Route::get('editarUsuario/{id}',[UsersController::class, 'edit'])->name('editarUsuario');
Route::post('actualizarUsuarios/{id}',[UsersController::class, 'update'])->name('actualizarUsuarios');
Route::get('eliminarUsuario/{id}',[UsersController::class, 'destroy'])->name('eliminarUsuario');

Route::get('rolesIndex', [RolesController::class, 'index'])->name('rolesIndex');
Route::get('crearRole', [RolesController::class, 'create'])->name('crearRole');
Route::post('insertarRole', [RolesController::class, 'store'])->name('insertarRole');
Route::get('editarRole/{id}', [RolesController::class, 'edit'])->name('editarRole');
Route::post('actualizarRole/{id}', [RolesController::class, 'update'])->name('actualizarRole');
Route::get('eliminarRole/{id}',[RolesController::class, 'destroy'])->name('eliminarRole');

Route::get('categoriasIndex', [CategoriasController::class, 'index'])->name('categoriasIndex');
Route::post('insertarCategoria', [CategoriasController::class, 'store'])->name('insertarCategoria');
Route::get('editarCategoria/{id}', [CategoriasController::class, 'edit'])->name('editarCategoria');
Route::post('actualizarCategoria/{id}', [CategoriasController::class, 'update'])->name('actualizarCategoria');
Route::get('eliminarCategoria/{id}',[CategoriasController::class, 'destroy'])->name('eliminarCategoria');

Route::get('proveedoresIndex', [ProveedoresController::class, 'index'])->name('proveedoresIndex');
Route::post('insertarProveedor', [ProveedoresController::class, 'store'])->name('insertarProveedor');
Route::get('editarProveedor/{id}', [ProveedoresController::class, 'edit'])->name('editarProveedor');
Route::post('actualizarProveedor/{id}', [ProveedoresController::class, 'update'])->name('actualizarProveedor');
Route::get('eliminarProveedor/{id}',[ProveedoresController::class, 'destroy'])->name('eliminarProveedor');

Route::get('articulosIndex', [ProductosController::class, 'index'])->name('articulosIndex');
Route::get('crearArticulo', [ProductosController::class, 'create'])->name('crearArticulo');
Route::post('insertarArticulo', [ProductosController::class, 'store'])->name('insertarArticulo');
Route::get('editarArticulo/{id}', [ProductosController::class, 'edit'])->name('editarArticulo');
Route::post('actualizarArticulo/{id}', [ProductosController::class, 'update'])->name('actualizarArticulo');
Route::get('eliminarArticulo/{id}',[ProductosController::class, 'destroy'])->name('eliminarArticulo');

Route::get('clientesIndex', [ClientesController::class, 'index'])->name('clientesIndex');
Route::get('crearCliente', [ClientesController::class, 'create'])->name('crearCliente');
Route::post('insertarCliente', [ClientesController::class, 'store'])->name('insertarCliente');
Route::get('editarCliente/{id}', [ClientesController::class, 'edit'])->name('editarCliente');
Route::post('actualizarCliente/{id}', [ClientesController::class, 'update'])->name('actualizarCliente');
Route::get('eliminarCliente/{id}',[ClientesController::class, 'destroy'])->name('eliminarCliente');

Route::get('comprasIndex', [ComprasController::class, 'index'])->name('comprasIndex');
Route::get('comprasAnuladas',[ComprasController::class, 'comprasAnuladas'])->name('comprasAnuladas');
Route::get('crearCompra', [ComprasController::class, 'create'])->name('crearCompra');
Route::post('insertarCompra', [ComprasController::class, 'store'])->name('insertarCompra');
Route::post('eliminarCompra/{id}',[ComprasController::class, 'destroy'])->name('eliminarCompra');
Route::get('compra/reportDay',[ComprasController::class, 'reportDay'])->name('compra.reportDay');
Route::get('compra/reportDate',[ComprasController::class, 'reportDate'])->name('compra.reportDate');
Route::post('compra/reportResult',[ComprasController::class, 'reportResult'])->name('compra.reportResult');
Route::get('detalleCompra/{id}',[ComprasController::class, 'detalleCompra'])->name('detalleCompra');
Route::get('compra/pdf/{id}',[ComprasController::class, 'pdf'])->name('compra.pdf');
Route::get('compras/ReportePdf',[ComprasController::class, 'ReportePdf'])->name('compras.ReportePdf');

Route::get('ventasIndex',[VentasController::class, 'index'])->name('ventasIndex');
Route::get('ventasAnuladas',[VentasController::class, 'ventasAnuladas'])->name('ventasAnuladas');
Route::get('crearVenta', [VentasController::class, 'create'])->name('crearVenta');
Route::post('insertarVenta', [VentasController::class, 'store'])->name('insertarVenta');
Route::get('detalleVenta/{id}',[VentasController::class, 'detalleVenta'])->name('detalleVenta');
Route::post('eliminarVenta/{id}',[VentasController::class, 'destroy'])->name('eliminarVenta');
Route::get('venta/reportDay',[VentasController::class, 'reportDay'])->name('venta.reportDay');
Route::get('venta/reportDate',[VentasController::class, 'reportDate'])->name('venta.reportDate');
Route::post('venta/reportResult',[VentasController::class, 'reportResult'])->name('venta.reportResult');
Route::get('venta/pdf/{id}',[VentasController::class, 'pdf'])->name('venta.pdf');
Route::get('ventas/ReportePdf',[VentasController::class, 'ReportePdf'])->name('ventas.ReportePdf');
Route::get('venta/print/{id}',[VentasController::class, 'print'])->name('venta.print');








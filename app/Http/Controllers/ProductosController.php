<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductosRequest;
use App\Models\Categoria;
use App\Models\Foto;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->can('ver articulos')) {
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }

        try {
            $productos = Producto::orderBy('id', 'desc')->get();
            return view('productos.index', compact('productos'));
        } catch (\Throwable $th) {
            Session::flash('eAuth', 'Error ' . $th->getMessage());
            return redirect('home');
        }
    }

    public function create()
    {
        $categorias = [];
        $proveedores = [];

        if (!Auth::user()->can('crear articulos')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'categorias' => $categorias,
                'proveedores' => $proveedores
            ]);
        }

        try {
            $categorias = Categoria::where('estado_id', 1)->orderBy('nombre', 'asc')->get();
            $proveedores = Proveedor::where('estado_id', 1)->orderBy('nombre', 'asc')->get();

            if ($categorias->isEmpty()) {
                throw new \Exception('Categorías vacío');
            }

            if ($proveedores->isEmpty()) {
                throw new \Exception('Proveedores vacío');
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'categorias' => $categorias,
                'proveedores' => $proveedores
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'categorias' => $categorias,
                'proveedores' => $proveedores
            ]);
        }
    }

    public function store(ProductosRequest $request)
    {
        if (!Auth::user()->can('crear articulos')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $entrada = $request->except('_token');

            if (empty($entrada)) {
                throw new \Exception('No se pudo crear el producto');
            }

            if ($request->hasFile('foto_id')) {
                $archivo = $request->file('foto_id');

                if ($archivo->getSize() > 1000000) {
                    throw new \Exception('La imagen debe tener un tamaño menor a 1Mb');
                }

                $formatosPermitidos = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($archivo->getMimeType(), $formatosPermitidos)) {
                    throw new \Exception('La imagen debe tener formato jpg/jpeg/png/gif');
                }

                $nombre = $archivo->getClientOriginalName();
                $archivo->move('images', $nombre);

                $foto = Foto::where('ruta', $nombre)->first();

                if (!$foto) {
                    $foto = Foto::create([
                        'ruta' => $nombre
                    ]);
                }

                $entrada['foto_id'] = $foto->id;
            }

            Producto::create($entrada);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Producto creado con éxito'
            ]);

        } catch (\Throwable $th) {

            DB::connection('mysql')->rollBack();

            $mensaje = $th->getMessage();

            if (str_contains($mensaje, 'Duplicate entry') && str_contains($mensaje, 'code')) {
                $mensaje = 'El código del producto ya existe';
            } else {
                $mensaje = 'Error al guardar el producto';
            }

            return Response::json([
                'error' => true,
                'mensaje' => $mensaje
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $producto = null;
        $categorias = [];
        $proveedores = [];
        $foto = null;

        if (!Auth::user()->can('editar articulos')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'categorias' => $categorias,
                'proveedores' => $proveedores,
                'producto' => $producto,
                'foto' => $foto
            ]);
        }

        try {
            $producto = Producto::where('id', $id)->first();

            if (!$producto) {
                throw new \Exception('Producto no existe');
            }

            $categorias = Categoria::where('estado_id', 1)->orderBy('nombre', 'asc')->get();
            $proveedores = Proveedor::where('estado_id', 1)->orderBy('nombre', 'asc')->get();

            if ($producto->foto_id) {
                $foto = Foto::where('id', $producto->foto_id)->first();
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'categorias' => $categorias,
                'proveedores' => $proveedores,
                'producto' => $producto,
                'foto' => $foto
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'categorias' => $categorias,
                'proveedores' => $proveedores,
                'producto' => $producto,
                'foto' => $foto
            ]);
        }
    }

    public function update(ProductosRequest $request, $id)
    {
        if (!Auth::user()->can('editar articulos')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $producto = Producto::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$producto) {
                throw new \Exception('Producto no existe');
            }

            $entrada = $request->except(['_token', 'id']);

            if (empty($entrada)) {
                throw new \Exception('No se pudo editar el producto');
            }

            if ($request->hasFile('foto_id')) {
                $archivo = $request->file('foto_id');

                if ($archivo->getSize() > 1000000) {
                    throw new \Exception('La imagen debe tener un tamaño menor a 1Mb');
                }

                $formatosPermitidos = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($archivo->getMimeType(), $formatosPermitidos)) {
                    throw new \Exception('La imagen debe tener formato jpg/jpeg/png/gif');
                }

                $nombre = $archivo->getClientOriginalName();
                $archivo->move('images', $nombre);

                $foto = Foto::where('ruta', $nombre)->first();

                if (!$foto) {
                    $foto = Foto::create([
                        'ruta' => $nombre
                    ]);
                }

                $entrada['foto_id'] = $foto->id;
            }

            $producto->update($entrada);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Producto actualizado con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();
            $mensaje = $th->getMessage();

            if (str_contains($mensaje, 'Duplicate entry') && str_contains($mensaje, 'code')) {
                $mensaje = 'El código del producto ya existe';
            } else {
                $mensaje = 'Error al guardar el producto';
            }

            return Response::json([
                'error' => true,
                'mensaje' => $mensaje
            ]);
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('eliminar articulos')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $producto = Producto::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$producto) {
                throw new \Exception('El producto no existe');
            }

            if ($producto->estado_id == 1) {
                $producto->estado_id = 2;
                $mensaje = 'Producto deshabilitado con éxito';
            } else {
                $producto->estado_id = 1;
                $mensaje = 'Producto habilitado con éxito';
            }

            $producto->save();

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => $mensaje
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage()
            ]);
        }
    }
}
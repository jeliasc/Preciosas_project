<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriasRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CategoriasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if (Auth::user()->can('ver categorias')) {
            try {
                $categorias = Categoria::orderBy('id', 'desc')->get();

                return view('categorias.index', compact('categorias'));            
            } catch (\Throwable $th) {
                Session::flash('eAuth', 'Error ' . $th->getMessage());
                return redirect('home');
            }
        }

        Session::flash('eAuth', 'Error, permiso denegado');
        return redirect('home');
    }

    public function store(CategoriasRequest $request)
    {
        if (!Auth::user()->can('crear categorias')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $datos = $request->except('_token');

            if (empty($datos['nombre'])) {
                throw new \Exception('Debe ingresar el nombre de la categoría');
            }

            Categoria::create([
                'nombre' => $datos['nombre'],
                'estado_id' => 1
            ]);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Categoría creada con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $categoria = null;

        if (!Auth::user()->can('editar categorias')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'categoria' => $categoria
            ]);
        }

        try {
            $categoria = Categoria::where('id', $id)->first();

            if (empty($categoria)) {
                return Response::json([
                    'error' => true,
                    'mensaje' => 'Categoría no existe',
                    'categoria' => null
                ]);
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'categoria' => $categoria
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'categoria' => null
            ]);
        }
    }

    public function update(CategoriasRequest $request, $id)
    {
        if (!Auth::user()->can('editar categorias')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $categoria = Categoria::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($categoria)) {
                throw new \Exception('Categoría no existe');
            }

            $datos = $request->except(['_token', 'id']);

            if (empty($datos['nombre'])) {
                throw new \Exception('Debe ingresar el nombre de la categoría');
            }

            $categoria->update($datos);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Categoría actualizada con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('eliminar categorias')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $categoria = Categoria::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($categoria)) {
                throw new \Exception('Categoría no existe');
            }

            if ($categoria->estado_id == 1) {
                $categoria->estado_id = 2;
                $mensaje = 'Categoría deshabilitada con éxito';
            } else {
                $categoria->estado_id = 1;
                $mensaje = 'Categoría habilitada con éxito';
            }

            $categoria->save();

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
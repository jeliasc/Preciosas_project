<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedoresRequest;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
       
    public function index()
    {
        if (!Auth::user()->can('ver proveedores')) {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }

        try {
            $proveedores = Proveedor::orderBy('id', 'desc')->get();
            return view('proveedores.index', compact('proveedores'));
        } catch (\Throwable $th) {
            Session::flash('eAuth', 'Error ' . $th->getMessage());
            return redirect('home');
        }
    }

    public function store(ProveedoresRequest $request)
    {
        if (!Auth::user()->can('crear proveedores')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $entrada = $request->except('_token');

            if (empty($entrada)) {
                throw new \Exception('No se pudo crear el proveedor');
            }

            if (Proveedor::where('nit', $request->nit)->exists()) {
                throw new \Exception('El NIT del proveedor ya existe. Ingrese uno diferente.');
            }

            Proveedor::create($entrada);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Proveedor creado con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => $th->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $proveedor = null;

        if (!Auth::user()->can('editar proveedores')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'proveedor' => $proveedor
            ]);
        }

        try {
            $proveedor = Proveedor::where('id', $id)->first();

            if (empty($proveedor)) {
                return Response::json([
                    'error' => true,
                    'mensaje' => 'Proveedor no existe',
                    'proveedor' => null
                ]);
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'proveedor' => $proveedor
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'proveedor' => null
            ]);
        }
    }

    public function update(ProveedoresRequest $request, $id)
    {
        if (!Auth::user()->can('editar proveedores')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $proveedor = Proveedor::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($proveedor)) {
                throw new \Exception('Proveedor no existe');
            }

            $entrada = $request->except(['_token', 'id']);

            if (empty($entrada)) {
                throw new \Exception('No se pudo editar el proveedor');
            }

            if (
                isset($entrada['nit']) &&
                Proveedor::where('nit', $entrada['nit'])
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                throw new \Exception('El NIT del proveedor ya existe. Ingrese uno diferente.');
            }

            $proveedor->update($entrada);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Proveedor actualizado con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('eliminar proveedores')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $proveedor = Proveedor::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($proveedor)) {
                throw new \Exception('Proveedor no existe');
            }

            if ($proveedor->estado_id == 1) {
                $proveedor->estado_id = 2;
                $mensaje = 'Proveedor deshabilitado con éxito';
            } else {
                $proveedor->estado_id = 1;
                $mensaje = 'Proveedor habilitado con éxito';
            }

            $proveedor->save();

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
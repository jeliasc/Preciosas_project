<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientesRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->can('ver clientes')) {
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }

        try {
            $clientes = Cliente::orderBy('id', 'desc')->get();
            return view('clientes.index', compact('clientes'));
        } catch (\Throwable $th) {
            Session::flash('eAuth', 'Error ' . $th->getMessage());
            return redirect('home');
        }
    }

    public function store(ClientesRequest $request)
    {
        if (!Auth::user()->can('crear clientes')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $datos = $request->except('_token');

            if (empty($datos)) {
                throw new \Exception('No se pudo crear el cliente');
            }

            if (Cliente::where('nit', $request->nit)->exists()) {
                throw new \Exception('El NIT del cliente ya existe. Ingrese uno diferente.');
            }
            Cliente::create($datos);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Cliente creado con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();
            $mensaje = $th->getMessage();
  
            return Response::json([
                'error' => true,
                'mensaje' => $mensaje
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $cliente = null;

        if (!Auth::user()->can('editar clientes')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'cliente' => $cliente
            ]);
        }

        try {
            $cliente = Cliente::where('id', $id)->first();

            if (empty($cliente)) {
                return Response::json([
                    'error' => true,
                    'mensaje' => 'Cliente no existe',
                    'cliente' => null
                ]);
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'cliente' => $cliente
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'cliente' => null
            ]);
        }
    }

    public function update(ClientesRequest $request, $id)
    {
        if (!Auth::user()->can('editar clientes')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $cliente = Cliente::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($cliente)) {
                throw new \Exception('Cliente no existe');
            }

            $datos = $request->except(['_token', 'id']);

            if (empty($datos)) {
                throw new \Exception('No se pudo editar el cliente');
            }

            if (
                isset($datos['nit']) &&
                Cliente::where('nit', $datos['nit'])
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                throw new \Exception('El NIT del cliente ya existe. Ingrese uno diferente.');
            }

            $cliente->update($datos);

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Cliente actualizado con éxito'
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();
            $mensaje = $th->getMessage();

            return Response::json([
                'error' => true,
                'mensaje' => $mensaje
            ]);
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('eliminar clientes')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->beginTransaction();

        try {
            $cliente = Cliente::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($cliente)) {
                throw new \Exception('Cliente no existe');
            }

            if ($cliente->estado_id == 1) {
                $cliente->estado_id = 2;
                $mensaje = 'Cliente deshabilitado con éxito';
            } else {
                $cliente->estado_id = 1;
                $mensaje = 'Cliente habilitado con éxito';
            }

            $cliente->save();

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
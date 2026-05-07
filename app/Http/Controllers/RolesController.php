<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->can('ver roles')) {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }

        try {
            $roles = Role::orderBy('id', 'desc')->get();
            return view('roles.index', compact('roles'));
        } catch (\Throwable $th) {
            Session::flash('eAuth', 'Error ' . $th->getMessage());
            return redirect('home');
        }
    }

    public function create()
    {
        $permissions = [];

        if (!Auth::user()->can('crear roles')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'permissions' => $permissions
            ]);
        }

        try {
            $permissions = Permission::all();

            if ($permissions->isEmpty()) {
                throw new \Exception('No existen permisos registrados');
            }

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'permissions' => $permissions
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'permissions' => $permissions
            ]);
        }
    }

    public function store(RolesRequest $request)
    {
        if (!Auth::user()->can('crear roles')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $datos = $request->except('_token');

            if (empty($datos['name'])) {
                throw new \Exception('Debe ingresar el nombre del rol');
            }

            if (Role::where('name', $request->name)->exists()) {
                throw new \Exception('El nombre del rol ya existe. Ingrese uno diferente.');
            }

            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
                'estado_id' => 1
            ]);

            $role->syncPermissions($request->input('permissions', []));

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Rol creado con éxito'
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
        $rolePermissions = [];
        $role = null;

        if (!Auth::user()->can('editar roles')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'role' => $role,
                'rolePermissions' => $rolePermissions
            ]);
        }

        try {
            $role = Role::where('id', $id)->first();

            if (empty($role)) {
                return Response::json([
                    'error' => true,
                    'mensaje' => 'Rol no existe',
                    'role' => null,
                    'rolePermissions' => []
                ]);
            }

            $rolePermissions = Permission::select('permissions.*', 'rh.role_id')
                ->leftJoin('role_has_permissions as rh', function ($join) use ($id) {
                    $join->on('rh.permission_id', '=', 'permissions.id')
                        ->where('rh.role_id', '=', $id);
                })
                ->get();

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'role' => $role,
                'rolePermissions' => $rolePermissions
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'role' => null,
                'rolePermissions' => []
            ]);
        }
    }

    public function update(RolesRequest $request, $id)
    {
        if (!Auth::user()->can('editar roles')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $role = Role::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($role)) {
                throw new \Exception('Rol no existe');
            }

            if (empty($request->name)) {
                throw new \Exception('Debe ingresar el nombre del rol');
            }

            if (
                Role::where('name', $request->name)
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                throw new \Exception('El nombre del rol ya existe. Ingrese uno diferente.');
            }

            $role->name = $request->input('name');
            $role->save();

            $role->syncPermissions($request->input('permissions', []));

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Rol actualizado con éxito'
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
        if (!Auth::user()->can('eliminar roles')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $role = Role::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($role)) {
                throw new \Exception('Rol no existe');
            }

            if ($role->name === 'Super-Administrador') {
                throw new \Exception('No se puede deshabilitar el rol Super-Administrador');
            }

            if ($role->estado_id == 1) {
                $role->estado_id = 2;
                $mensaje = 'Rol deshabilitado con éxito';
            } else {
                $role->estado_id = 1;
                $mensaje = 'Rol habilitado con éxito';
            }

            $role->save();

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => $mensaje
            ]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            return Response::json([
                'error' => true,
                'mensaje' => $th->getMessage()
            ]);
        }
    }
}
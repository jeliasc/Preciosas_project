<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->can('ver usuarios')) {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }

        try {
            $roles = Role::where('estado_id', 1)->get();
            $users = User::orderBy('id', 'desc')->get();

            return view('users.index', compact('roles', 'users'));
        } catch (\Throwable $th) {
            Session::flash('eAuth', 'Error ' . $th->getMessage());
            return redirect('home');
        }
    }

    public function create()
    {
        $roles = [];

        if (!Auth::user()->can('crear usuarios')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'roles' => $roles
            ]);
        }

        try {
            $roles = Role::where('estado_id', 1)
                ->orderBy('name', 'asc')
                ->get();

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'roles' => $roles
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'roles' => $roles
            ]);
        }
    }

    public function store(UsersRequest $request)
    {
        if (!Auth::user()->can('crear usuarios')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $entrada = $request->except('_token');

            if (empty($entrada)) {
                throw new \Exception('No se pudo crear el usuario');
            }

            if (User::where('email', $request->email)->exists()) {
                throw new \Exception('El correo del usuario ya existe. Ingrese uno diferente.');
            }

            $role = null;

            if (!empty($request->role_id)) {
                $role = Role::where('id', $request->role_id)
                    ->where('estado_id', 1)
                    ->first();

                if (!$role) {
                    throw new \Exception('El rol seleccionado no es válido');
                }
            }

            $entrada['password'] = bcrypt($request->password);

            $user = User::create($entrada);

            if ($role) {
                $user->assignRole($role);
            }

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Usuario creado con éxito'
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
        $roles = [];
        $user = null;
        $role_user = null;

        if (!Auth::user()->can('editar usuarios')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado',
                'user' => $user,
                'roles' => $roles,
                'role_user' => $role_user
            ]);
        }

        try {
            $roles = Role::where('estado_id', 1)
                ->orderBy('name', 'asc')
                ->get();

            $user = User::where('id', $id)->first();

            if (empty($user)) {
                return Response::json([
                    'error' => true,
                    'mensaje' => 'Usuario no existe',
                    'user' => null,
                    'roles' => $roles,
                    'role_user' => null
                ]);
            }

            $role_user = User::select('m.model_id as id_user', 'm.role_id as id_role')
                ->join('model_has_roles as m', 'm.model_id', 'users.id')
                ->where('m.model_id', $id)
                ->first();

            return Response::json([
                'error' => false,
                'mensaje' => 'Consulta exitosa',
                'user' => $user,
                'roles' => $roles,
                'role_user' => $role_user
            ]);

        } catch (\Throwable $th) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Error ' . $th->getMessage(),
                'user' => null,
                'roles' => $roles,
                'role_user' => null
            ]);
        }
    }

    public function update(UsersUpdateRequest $request, $id)
    {
        if (!Auth::user()->can('editar usuarios')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $user = User::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                throw new \Exception('Usuario no existe');
            }

            $entrada = $request->except(['_token', 'id', 'role_id']);

            if (empty($entrada)) {
                throw new \Exception('No se pudo actualizar el usuario');
            }

            if (
                isset($entrada['email']) &&
                User::where('email', $entrada['email'])
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                throw new \Exception('El correo del usuario ya existe. Ingrese uno diferente.');
            }

            $role = null;

            if (!empty($request->role_id)) {
                $role = Role::where('id', $request->role_id)
                    ->where('estado_id', 1)
                    ->first();

                if (!$role) {
                    throw new \Exception('El rol seleccionado no es válido');
                }
            }

            if (!empty($request->password)) {
                $entrada['password'] = bcrypt($request->password);
            } else {
                unset($entrada['password']);
            }

            $user->update($entrada);

            if ($role) {
                $user->syncRoles([$role->name]);
            } else {
                $user->syncRoles([]);
            }

            DB::connection('mysql')->commit();

            return Response::json([
                'error' => false,
                'mensaje' => 'Usuario actualizado con éxito'
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
        if (!Auth::user()->can('eliminar usuarios')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::statement('SET @app_user_id = ?', [Auth::id()]);
        DB::connection('mysql')->beginTransaction();

        try {
            $user = User::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                throw new \Exception('El usuario no existe');
            }

            if ($user->id == Auth::user()->id) {
                throw new \Exception('No puede deshabilitar su propio usuario');
            }

            if ($user->estado_id == 1) {
                $user->estado_id = 2;
                $mensaje = 'Usuario deshabilitado con éxito';
            } else {
                $user->estado_id = 1;
                $mensaje = 'Usuario habilitado con éxito';
            }

            $user->save();

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
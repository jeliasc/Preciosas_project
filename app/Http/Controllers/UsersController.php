<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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

    public function index(){
        if (Auth::user()->can('ver usuarios')) {
            try {
                $roles = Role::all();
                $users = User::all();
                return view('users.index', compact('roles', 'users'));    
            } catch (\Throwable $th) {
                $error = 'Eerror';
                $error = $error.' '.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function create(){
        if(Auth::user()->can('crear usuarios')){
            try {
                $mensaje = '';
                $error = true;
                $roles = Role::all ();

                if(empty($roles)){
                    $error = true;
                    $mensaje = 'Roles vacíos';
                }else{
                    $error = false;
                    $mensaje ='Consulta exitosa';
                }
                
            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }
        }else{
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'roles' => $roles));
    }

    public function store(UsersRequest $request){
        if(Auth::user()->can('crear usuarios',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['_token']);
                $role = Role::where('id', $request->role_id)->first();

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear el usuario';
                }else{
                    $entrada['password']=bcrypt($request->password);
                    $user=User::create($entrada);
                    $user->assignRole($role);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Usuario creado con éxito';
                }
            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }
        }else{
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function edit(Request $request, $id){
        if(Auth::user()->can('editar usuarios')){
            try {
                $mensaje = '';
                $error = true;
                $roles=Role::all();
                $user = User::where('id', $id)->first();
                $role_user =  User::select('m.model_id as id_user', 'm.role_id as id_role')
                ->join('model_has_roles as m', 'm.model_id', 'users.id')
                ->where('m.model_id', $id)
                ->get()
                ->first();
            
                if(empty($user)){
                    $error = true;
                    $mensaje = 'Usuario no existe';
                }else{
                    $error = false;
                    $mensaje ='Consulta exitosa';
                }

            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }
        }else{
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'user' => $user, 'roles' => $roles, 'role_user' => $role_user));
    }

    public function update(UsersUpdateRequest $request, $id){
        if(Auth::user()->can('editar usuarios')){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['id']);
                unset($entrada['_token']);
                $role = Role::where('id', $request->role_id)->first();
                $user = User::findOrFail($id);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo actualizar el usuario';
                }else{
                    $user->update($entrada); 
                    $user->roles()->update(['role_id'=>$role->id]);
                    $user->assignRole($role); 
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Usuario actualizado con éxito';
                }

            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }

        }else{
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function destroy($id){
        if (auth::user()->can('eliminar usuarios')) {
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $user = User::where('id',$id)->first();
                
                if(empty($user)){
                    $error = true;
                    $mensaje = 'El usuario no existe';
                }else if($user->estado_id ==1){
                    $error = false;
                    $user->estado_id = 2;
                    $mensaje ='Usuario deshabilitado con éxito';
                    DB::connection('mysql')->commit();
                    $user->save();
                } else{
                    $error = false;
                    $user->estado_id = 1;
                    $mensaje ='Usuario habilitado con éxito';
                    DB::connection('mysql')->commit();
                    $user->save();
                }
            }catch(\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();  
            }
        }else{
                $error = true;
                $mensaje = 'Permiso denegado'; 
            }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }
}

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

    public function index(){
        if(Auth::user()->can('ver roles')){
            try {
                $roles = Role::get()->all();
                return view('roles.index', compact('roles'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error.' '. $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }   
        }else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function create(){
        if(Auth::user()->can('crear roles')){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $permissions = Permission::all();
                if(empty($permissions)){
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'permissions' => $permissions));     
    }

    public function store(RolesRequest $request){
        if(Auth::user()->can('crear roles',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear el role';
                }else{
                    $role = Role::create(['name'=>$request->input('name')]);
                    $role->syncPermissions($request->input('permissions'));
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Role creado con éxito';
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
        $rolePermissions = array();
        $role = "";
 
        if(Auth::user()->can('editar roles')){
            try {
                $mensaje = '';
                $error = true;
        
                $role = Role::where('id', $id)->first();

                $rolePermissions = Permission::select("*")
                ->leftJoin('role_has_permissions as rh', function ($join) use ($id) {
                    $join->on('rh.permission_id', '=', 'permissions.id')
                         ->where('rh.role_id', '=', $id);
                })->get();
           
                if(empty($role)){
                    $error = true;
                    $mensaje = 'Role no existe';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'role' => $role, 'rolePermissions' => $rolePermissions));
    }

    public function update(RolesRequest $request, $id){
        if(Auth::user()->can('editar roles',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $role=Role::find($id);
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['id']);
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo editar el role';
                }else{
                    $role->name = $request->input('name');
                    $role->save();
                    $role->syncPermissions($request->input('permissions'));
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Role actualizado con éxito';
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
        if (auth::user()->can('eliminar roles')) {

                DB::connection('mysql')->beginTransaction();
            try 
            {
                $mensaje = '';
                $error = true;
                $user = Role::where('id', $id)->first();
                
                if(empty($user)){
                    $error = true;
                    $mensaje = 'Role no existe';
                }else if($user->estado_id ==1){
                    $error = false;
                    $user->estado_id = 2;
                    $mensaje ='El role ha sido deshabilitado con éxito';
                } else{
                    $error = false;
                    $user->estado_id = 1;
                    $mensaje ='El role ha sido habilitado con éxito';
                }
                DB::connection('mysql')->commit();
                $user->save();
            }catch(\Throwable $th) 
            {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();  
            }
        }else
            {
                $error = true;
                $mensaje = 'Permiso denegado'; 
            }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }
}

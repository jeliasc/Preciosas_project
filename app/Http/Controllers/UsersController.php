<?php

namespace App\Http\Controllers;

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
                $roles = DB::table('roles')
                ->orderBy('name', 'asc')
                ->where('estado_id','1')
                ->get(); 
                return view('users.create', compact('roles'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error.' '.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('Error, permiso denegado');
            return redirect('home');
        }
    }

    public function store(Request $request){
        if(Auth::user()->can('crear usuarios',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $role=Role::where('id', $request->role_id)->first();
                $datos=$request->all();
                $datos['password']=bcrypt($request->password);
                $user=User::create($datos);
                $user->assignRole($role);
                DB::connection('mysql')->commit();
                Session::flash('usuarioCreado','El usuario ha sido creado con éxito');
                return redirect('usuariosIndex');
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error.' '.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('Error, permiso denegado');
            return redirect('home');
        }
    }

    public function edit(Request $request, $id){
        $mensaje = '';
        $error = true;
        $data = [];
        $roles = Role::get();
        if(Auth::user()->can('editar usuarios')){
            try {
                $data = User::where('id',$id)->first();
                if(empty($data)){
                    $error = true;
                    $mensaje = 'Usuario no existe';
                }else{
                    $error = false;
                    $mensaje ='Consulta exitsa';
                }

            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }
        }else{
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje,'data' => $data, 'roles' => $roles));
    }

    public function update(Request $request, $id){
        
        if(Auth::user()->can('editar usuarios')){
            DB::connection('mysql')->beginTransaction();
            try {
                $role = Role::where('id', $request->role_id)->first();
                $user = User::findOrFail($id);
                $datos = $request->all();
                
                $user->update($datos); 
                $user->roles()->update(['role_id'=>$role->id]);
                $user->assignRole($role); 
                DB::connection('mysql')->commit();
                Session::flash('usuarioActualizado','El usuario ha sido actualizado con éxito');
                return redirect('usuariosIndex');

            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error.' '.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }

        }else{
            Session::flash('Error, permiso denegado');
            return redirect('home');
        }
    }
}

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
       
    public function index(){
        if(Auth::user()->can('ver proveedores')){
            try {
                $proveedores = Proveedor::all();
                return view('proveedores.index', compact('proveedores'));
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

    public function store(ProveedoresRequest $request){
        if(Auth::user()->can('crear proveedores',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear el proveedor';
                }else{
                    Proveedor::create($entrada);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Proveedor creado con éxito';
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
        if(Auth::user()->can('editar proveedores')){
            try {
                $mensaje = '';
                $error = true;
                $proveedor = Proveedor::where('id', $id)->first();     
                if(empty($proveedor)){
                    $error = true;
                    $mensaje = 'Proveedor no existe';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'proveedor' => $proveedor));
    }

    public function update(ProveedoresRequest $request, $id){
        if(Auth::user()->can('editar proveedores',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $proveedor=Proveedor::find($id);
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['id']);
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo editar el proveedor';
                }else{
                    $proveedor->update($entrada);                  
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Proveedor actualizado con éxito';
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
        if (auth::user()->can('eliminar proveedores')) {
                DB::connection('mysql')->beginTransaction();
            try 
            {
                $mensaje = '';
                $error = true;
                $proveedor = Proveedor::where('id', $id)->first();
                
                if(empty($proveedor)){
                    $error = true;
                    $mensaje = 'Proveedor no existe';
                }else if($proveedor->estado_id ==1){
                    $error = false;
                    $proveedor->estado_id = 2;
                    $mensaje ='El proveedor ha sido deshabilitado con éxito';
                    DB::connection('mysql')->commit();
                    $proveedor->save();
                } else{
                    $error = false;
                    $proveedor->estado_id = 1;
                    $mensaje ='El proveedor ha sido habilitado con éxito';
                    DB::connection('mysql')->commit();
                    $proveedor->save();
                }
            } catch(\Throwable $th) {
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

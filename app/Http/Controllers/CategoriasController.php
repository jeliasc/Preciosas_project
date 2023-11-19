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
    
    public function index(){
        if (Auth::user()->can('ver categorias')) {
            try {
                $categorias = Categoria::all();
                return view('categorias.index', compact('categorias'));            
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error. '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function store(CategoriasRequest $request){
        if(Auth::user()->can('crear categorias',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear la categoría';
                }else{
                    Categoria::create(['nombre'=>$request->input('nombre')]);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Categoría creada con éxito';
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
        if(Auth::user()->can('editar categorias')){
            try {
                $mensaje = '';
                $error = true;
                $categoria = Categoria::where('id', $id)->first(); 

                if(empty($categoria)){
                    $error = true;
                    $mensaje = 'Categoría no existe';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'categoria' => $categoria));
    }

    public function update(CategoriasRequest $request, $id){
        if(Auth::user()->can('editar categorias',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $categoria=Categoria::find($id);
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['id']);
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo editar la categoría';
                }else{
                    $categoria->update($entrada);                  
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Categoría actualizado con éxito';
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
        if (auth::user()->can('eliminar categorias')) {

                DB::connection('mysql')->beginTransaction();
            try 
            {
                $mensaje = '';
                $error = true;
                $categoria = Categoria::where('id', $id)->first();
                
                if(empty($categoria)){
                    $error = true;
                    $mensaje = 'Categoría no existe';
                }else if($categoria->estado_id ==1){
                    $error = false;
                    $categoria->estado_id = 2;
                    $mensaje ='La categoría ha sido deshabilitado con éxito';
                    DB::connection('mysql')->commit();
                    $categoria->save();
                } else{
                    $error = false;
                    $categoria->estado_id = 1;
                    $mensaje ='La categoría ha sido habilitado con éxito';
                    DB::connection('mysql')->commit();
                    $categoria->save();
                }
            }catch(\Throwable $th) {
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

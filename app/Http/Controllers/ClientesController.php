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

    public function index(){
        if (Auth::user()->can('ver productos')) {
            try {
                $clientes = Cliente::all();
                return view('clientes.index', compact('clientes'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error. '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }
    }

    public function store(ClientesRequest $request){
        if(Auth::user()->can('crear clientes',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear el cliente';
                }else{
                    Cliente::create($entrada);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Cliente creado con éxito';
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
        if(Auth::user()->can('editar clientes')){
            try {
                $mensaje = '';
                $error = true;
                $cliente = Cliente::where('id', $id)->first();     
                if(empty($cliente)){
                    $error = true;
                    $mensaje = 'Cliente no existe';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'cliente' => $cliente));
    }

    public function update(ClientesRequest $request, $id){
        if(Auth::user()->can('editar clientes',)){
            DB::connection('mysql')->beginTransaction();
            try {
                $cliente=Cliente::find($id);
                $mensaje = '';
                $error = true;
                $entrada = $request->all();
                unset($entrada['id']);
                unset($entrada['_token']);

                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo editar el cliente';
                }else{
                    $cliente->update($entrada);                  
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Cliente actualizado con éxito';
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
        if (auth::user()->can('eliminar clientes')) {
                DB::connection('mysql')->beginTransaction();
            try 
            {
                $mensaje = '';
                $error = true;
                $cliente = Cliente::where('id', $id)->first();
                
                if(empty($cliente)){
                    $error = true;
                    $mensaje = 'Cliente no existe';
                }else if($cliente->estado_id ==1){
                    $error = false;
                    $cliente->estado_id = 2;
                    $mensaje ='Cliente deshabilitado con éxito';
                    DB::connection('mysql')->commit();
                    $cliente->save();
                } else{
                    $error = false;
                    $cliente->estado_id = 1;
                    $mensaje ='Cliente habilitado con éxito';
                    DB::connection('mysql')->commit();
                    $cliente->save();
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

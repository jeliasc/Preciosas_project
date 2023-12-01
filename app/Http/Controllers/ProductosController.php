<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductosRequest;
use App\Models\Categoria;
use App\Models\Foto;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ProductosController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        if (Auth::user()->can('ver productos')) {
            try {
                $productos = Producto::all();
                return view('productos.index', compact('productos'));
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

    public function create(){
        if(Auth::user()->can('crear productos')){
            try {
                $mensaje = '';
                $error = true;
                $categorias = Categoria::all();
                $proveedores = Proveedor::all();

                if(empty($categorias)){
                    $error = true;
                    $mensaje = 'Categorías vacío';
                }elseif(empty($proveedores)){
                    $error = true;
                    $mensaje = 'Proveedores vacío';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'categorias' => $categorias, 'proveedores' => $proveedores));
    }

    public function store(ProductosRequest $request){
        $mensaje = '';
        $error = true;
        $entrada = $request->all();
        $tipo_imagen = $_FILES['foto_id']['type'];
        $tamagno_imagen = $_FILES['foto_id']['size'];
        unset($entrada['_token']);

        if(Auth::user()->can('crear productos',)){
            DB::connection('mysql')->beginTransaction();
            try { 
                if(empty($entrada)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear el producto';
                }else{
                    if($archivo=$request->file('foto_id' )){
                           if($tamagno_imagen <= 1000000){
                            if($tipo_imagen == "image/jpg" || $tipo_imagen == "image/jpeg" || $tipo_imagen == "image/png" || $tipo_imagen == "image/gif"){
                                $nombre=$archivo->getClientOriginalName();
                                $archivo->move('images', $nombre);

                                $foto = Foto::select('p.foto_id as id_foto', 'fotos.ruta as ruta_foto')
                                ->join('productos as p', 'p.foto_id', 'fotos.id')
                                ->where ('fotos.ruta', $nombre)
                                ->get()
                                ->first();

                                if($foto){
                                    $entrada['foto_id']=$foto->id_foto; 
                                }else{
                                    $foto=Foto::create(['ruta'=>$nombre]);
                                    $entrada['foto_id']=$foto->id; 
                                } 
                            }else{
                                Producto::create($entrada);
                                DB::connection('mysql')->commit();
                                $error = true;
                                $mensaje ='La imagen debe tener un fomato jpg/jpeg/png/gif';
                            }
                        }else{
                            Producto::create($entrada);
                            DB::connection('mysql')->commit();
                            $error = true;
                            $mensaje ='La imagen debe tener un tamaño menor a 1Mb';
                        }  
                    }
                    Producto::create($entrada);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Producto creado con éxito';
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
        if(Auth::user()->can('editar productos')){
            try {
                $mensaje = '';
                $error = true;
                $producto = Producto::where('id', $id)->first();     
                $categorias = Categoria::all();
                $proveedores = Proveedor::all();

                $foto = Foto::select('p.foto_id as id_foto', 'fotos.ruta as ruta_foto')
                ->join('productos as p', 'p.foto_id', 'fotos.id')
                ->where ('fotos.id', $producto->foto_id)
                ->get()
                ->first();

                if(empty($producto)){
                    $error = true;
                    $mensaje = 'Producto vacío';
                }elseif(empty($categorias)){
                    $error = true;
                    $mensaje = 'Categorías vacío';
                }elseif(empty($proveedores)){
                    $error = true;
                    $mensaje = 'Proveedores vacío';
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
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'categorias' => $categorias, 'proveedores' => $proveedores, 'producto' => $producto, 'foto' => $foto));
    }

    public function update(ProductosRequest $request, $id){
        $producto=Producto::find($id);
        $entrada=$request->all();
        unset($entrada['id']);
        unset($entrada['_token']);
        $mensaje = '';
        $error = true;
        
        if(Auth::user()->can('editar productos',)){
            DB::connection('mysql')->beginTransaction();
            try {
                if(empty($request)){
                    $error = true;
                    $mensaje = 'Error, no se pudo editar el producto';
                }else{
                    if($archivo=$request->file('foto_id' )){
                        $tipo_imagen = $_FILES['foto_id']['type'];
                        $tamagno_imagen = $_FILES['foto_id']['size'];
                        if($tamagno_imagen <= 1000000){
                            if($tipo_imagen == "image/jpg" || $tipo_imagen == "image/jpeg" || $tipo_imagen == "image/png" || $tipo_imagen == "image/gif"){
                                $nombre=$archivo->getClientOriginalName();
                                $archivo->move('images', $nombre);
                                $foto = Foto::select('p.foto_id as id_foto', 'fotos.ruta as ruta_foto')
                                ->join('productos as p', 'p.foto_id', 'fotos.id')
                                ->where ('fotos.ruta', $nombre)
                                ->get()
                                ->first();

                                if($foto){
                                    $entrada['foto_id']=$foto->id_foto; 
                                }else{
                                    $foto=Foto::create(['ruta'=>$nombre]);
                                    $entrada['foto_id']=$foto->id; 
                                }
                            }else{
                                $producto->update($entrada);                  
                                DB::connection('mysql')->commit();
                                $error = true;
                                $mensaje ='La imagen debe tener un fomato jpg/jpeg/png/gif';
                            }
                        }else{
                            $producto->update($entrada);                  
                            DB::connection('mysql')->commit();
                            $error = true;
                            $mensaje ='La imagen debe tener un tamaño menor a 1Mb';
                        }  
                    }
                    $producto->update($entrada);                  
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje ='Producto actualizado con éxito';
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
        if (auth::user()->can('eliminar productos')) {

                DB::connection('mysql')->beginTransaction();
            try 
            {
                $mensaje = '';
                $error = true;
                $producto = Producto::where('id', $id)->first();
                
                if(empty($producto)){
                    $error = true;
                    $mensaje = 'El producto no existe';
                }else if($producto->estado_id ==1){
                    $error = false;
                    $producto->estado_id = 2;
                    $mensaje ='Producto deshabilitado con éxito';
                    DB::connection('mysql')->commit();
                    $producto->save();
                } else{
                    $error = false;
                    $producto->estado_id = 1;
                    $mensaje ='Producto habilitado con éxito';
                    DB::connection('mysql')->commit();
                    $producto->save();
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


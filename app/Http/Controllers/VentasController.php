<?php

namespace App\Http\Controllers;

use App\Models\Correlativo;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class VentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $ventas=Venta::where('estado_id','3')->get();
        if(Auth::user()->can('ver ventas')){
            try {
                return view('ventas.index', compact('ventas'));              
            }catch (\Throwable $th){
                $error="Error";
                $error=$error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return Redirect('home');
            }
        }else{
            Session::flash('eAuth', 'Error, permiso denegado');
            return Redirect('home');
        }
    }

    public function ventasAnuladas(){
        $ventas=Venta::where('estado_id','4')->get();
        if(Auth::user()->can('ver ventas')){
            try {
                return view('ventas.ventasAnuladas', compact('ventas'));              
            }catch (\Throwable $th) {
                $error="Error";
                $error=$error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return Redirect('home');
            }
        }else{
            Session::flash('eAut', 'Error, permiso denegado');
            return Redirect('admin');
        }
    }

    public function create(){
        $productos = DB::table('productos')
        ->orderBy('nombre', 'asc')
        ->where('estado_id','1')
        ->get();

        $clientes = DB::table('clientes')
        ->orderBy('nombre', 'asc')
        ->where('estado_id','1')
        ->get();

        if(Auth::user()->can('crear ventas')){
            try {
                return view('ventas.create', compact('productos','clientes'));
            } catch (\Throwable $th) {
                $error="Error";
                $error=$error. '' .$th->getMessage();
                Session::flash('eAuth',$error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth','Error, permiso denegado');
            return redirect('home');
        } 
    } 

    public function store(Request $request){
        $mensaje = '';
        $error = true;
        $entrada = $request->all();
        unset($request['_token']);
        if(Auth::user()->can('crear ventas')){
            DB::connection('mysql')->beginTransaction();
            try {
                if(empty($entrada)){
                    $error = true;
                    $mensaje = 'Error, no se pudo crear la compra';
                }else{

                $correlativo = Correlativo::pluck('ultimo_numero')->first();  
                           
                $correlativo_anterior = $correlativo;
                $nuevo_correlativo = $correlativo_anterior + 1;
                $numero_factura = $nuevo_correlativo;
                      
                $venta=Venta::create($request->all()+[
                    'user_id' => Auth::user()->id,
                    'fecha'=> Carbon::now('America/Guatemala'),
                    'numero_factura' => $numero_factura,
                ]);

                foreach ($request->producto_id as $key=>$p){
                    $resultado[] = array('producto_id' => $request->producto_id[$key],
                    "cantidad" => $request->cantidad[$key], 
                    "precio" => $request->precio[$key],
                    "descuento" => $request->descuento[$key], 
                    "comentario" => $request->comentario[$key],
                    "extra" => $request->extra[$key]);
                }   
                $venta->detalleVentas()->createMany($resultado);

                $c = Correlativo::where('id','1')->first(); 
                $c->ultimo_numero = $nuevo_correlativo;
                $c->save();
                DB::connection('mysql')->commit();
                $error = false;
                $mensaje ='Venta creada con éxito';
                }                   
            }catch (\Throwable $th){
                $error='Error ';
                $error=$error . '' . $th->getMessage();
                DB::connection('mysql')->rollBack();
                $error = true;
                $mensaje = 'Error '.$th->getMessage();
            }
        }else {
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function destroy($id){
        if(auth::user()->can('eliminar ventas')){
                DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $venta = Venta::where('id', $id)->first();
                
                if(empty($venta)){
                    $error = true;
                    $mensaje = 'La venta no existe';
                }else if($venta->estado_id == 3){
                    $error = false;
                    $venta->estado_id = 4;
                    $mensaje ='Venta anulada con éxito';
                    DB::connection('mysql')->commit();
                    $venta->save();
                }else{
                    $error = true;
                    $mensaje ='La venta esta anulada';
                }
            }catch(\Throwable $th){
                $error = true;
                $mensaje = 'Error '.$th->getMessage();  
            }
        }else{
                $error = true;
                $mensaje = 'Permiso denegado'; 
            }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function detalleVenta($id){
        $venta=Venta::findOrFail($id);
        if(Auth::user()->can('ver ventas')){
            try {
                $subTotalVenta=0;
                $detalleVentas=$venta->detalleVentas;
                foreach($detalleVentas as $detalleVenta){
                    $subTotalVenta += ((($detalleVenta->cantidad*$detalleVenta->precio)+$detalleVenta->extra)-$detalleVenta->descuento);
                }
                return view('ventas.detalleVenta', compact('venta','detalleVentas', 'subTotalVenta'));
            }catch (\Throwable $th){
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth','Error, permiso denegado');
            return redirect('home');
        }   
    }

    public function reportDay(){
        $ventas = Venta::whereDate('fecha', Carbon::today('America/Guatemala'))->where('estado_id', '3')->get();
        if(Auth::user()->can('ver reporte de ventas')){
            try{
                $total = $ventas->sum('total');
                return view('ventas.reportDay', compact('ventas', 'total'));
            }catch (\Throwable $th){
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth','Error, permiso denegado');
            return redirect('home');
        }
    }

    public function reportDate(){
        $ventas = Venta::where('estado_id', '3')->get();
        if(Auth::user()->can('ver reporte de ventas')){
            try{
                $total = $ventas->sum('total');
                return view('ventas.reportDate', compact('total', 'ventas'));
            }catch (\Throwable $th){
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');    
            }              
        }else{
            Session::flash('eAuth','Error, permiso denegado');
            return redirect('home');
        } 
    }

    public function reportResult(Request $request){
        $ventas = Venta::where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try{
                $fi = $request->fechaInicio. '00:00:00';
                $ff = $request->fechaFinal. '23:59:59';
                $ventas=$ventas->whereBetween('fecha', [$fi, $ff]);
                $total = $ventas->sum('total');
                return view('ventas.reportDate', compact('ventas', 'total'));
            }catch (\Throwable $th){
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home'); 
            }
        }else{
            Session::flash('eAuth','Error, permiso denegado');
            return redirect('home');
        }     
    } 
    public function pdf($id){
        $venta = Venta::findOrFail($id);
        if (Auth::user()->can('ver ventas')) {
            try {
                $detalleVentas=$venta->detalleVentas;
                $pdf = Pdf::loadView('ventas.pdf', compact('venta','detalleVentas'));
                return $pdf->download('Reporte_de_venta_'.$venta->id.'.pdf');            
            } catch (\Throwable $th) {
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAut', $error);
                return redirect('admin');
            }
        }else {
            Session::flash('eAut','Error, permiso denegado');
            return redirect('admin');
        }  
    }
    public function print($id){
        $venta = Venta::findOrFail($id);
        if(Auth::user()->can('ver ventas')){
            try{
                $subtotal=0;
                $detalleVentas=$venta->detalleVentas;
                foreach($detalleVentas as $detalleVenta){
                    $subtotal += ((($detalleVenta->cantidad*$detalleVenta->precio)+$detalleVenta->extra)-$detalleVenta->descuento);
                }
                $printer_name = "TM20";
                $connector = new WindowsPrintConnector($printer_name);
                $printer = new Printer($connector);   
                $printer->text("Q. 9,95\n"); 
                $printer->cut();
                $printer->close();
                return redirect()->back();
                Session::flash('print', 'Impresión realizada con éxito');
                return redirect()->back();
            } catch (\Throwable $th) {
                $error="Error";
                $error=$error. ''. $th->getMessage();
                Session::flash('eAut', $error);
                return redirect()->back();
            }
        }else{
            Session::flash('eAut','Error, permiso denegado');
            return redirect('admin');
        }  
    }
}

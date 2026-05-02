<?php

namespace App\Http\Controllers;

use App\Models\Correlativo;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function index()
    {
        $ventas = Venta::where('estado_id', '3')
        ->orderBy('fecha', 'desc')
        ->get();

        if (Auth::user()->can('ver ventas')) {
            try {
                return view('ventas.index', compact('ventas'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return Redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return Redirect('home');
        }
    }

    public function ventasAnuladas()
    {
        $ventas = Venta::where('estado_id', '4')
        ->orderBy('fecha', 'desc')
        ->get();
        
        if (Auth::user()->can('ver ventas')) {
            try {
                return view('ventas.ventasAnuladas', compact('ventas'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return Redirect('home');
            }
        } else {
            Session::flash('eAut', 'Error, permiso denegado');
            return Redirect('admin');
        }
    }

    public function create()
    {
        $productos = DB::table('productos')
            ->orderBy('nombre', 'asc')
            ->where('estado_id', '1')
            ->get();

        $clientes = DB::table('clientes')
            ->orderBy('nombre', 'asc')
            ->where('estado_id', '1')
            ->get();

        if (Auth::user()->can('crear ventas')) {
            try {
                return view('ventas.create', compact('productos', 'clientes'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

   public function store(Request $request)
    {
        $mensaje = '';
        $error = true;

        if (!Auth::user()->can('crear ventas')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        $datos = $request->except('_token');

        DB::connection('mysql')->statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
        DB::connection('mysql')->beginTransaction();

        try {
            if (empty($datos)) {
                throw new \Exception('No se pudo crear la venta');
            }

            if (empty($request->producto_id) || !is_array($request->producto_id)) {
                throw new \Exception('Debe agregar al menos un producto a la venta');
            }

            if (empty($request->cliente_id)) {
                throw new \Exception('Debe seleccionar un cliente para registrar la venta');
            }

            /*
            * Bloqueo del correlativo.
            * Evita que dos usuarios generen el mismo número de factura.
            */
            $correlativo = Correlativo::where('id', 1)
                ->lockForUpdate()
                ->first();

            if (!$correlativo) {
                throw new \Exception('No existe correlativo configurado');
            }

            $nuevo_correlativo = $correlativo->ultimo_numero + 1;
            $numero_factura = $nuevo_correlativo;

            /*
            * Validación de stock con bloqueo por producto.
            * Esto evita problemas por ventas simultáneas.
            */
            foreach ($request->producto_id as $key => $productoId) {
                $producto = DB::table('productos')
                    ->where('id', $productoId)
                    ->lockForUpdate()
                    ->first();

                if (!$producto) {
                    throw new \Exception('Producto no encontrado');
                }

                $cantidadSolicitada = (int) $request->cantidad[$key];

                if ($cantidadSolicitada <= 0) {
                    throw new \Exception('La cantidad debe ser mayor a cero');
                }

                if ($producto->stock < $cantidadSolicitada) {
                    throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
                }
            }

            $venta = Venta::create($datos + [
                'user_id' => Auth::user()->id,
                'fecha' => Carbon::now('America/Guatemala'),
                'numero_factura' => $numero_factura,
            ]);

            $resultado = [];

            foreach ($request->producto_id as $key => $p) {
                $resultado[] = [
                    'producto_id' => $request->producto_id[$key],
                    'cantidad' => $request->cantidad[$key],
                    'precio' => $request->precio[$key],
                    'descuento' => $request->descuento[$key],
                    'comentario' => $request->comentario[$key],
                    'extra' => $request->extra[$key],
                ];
            }

            /*
            * Al insertar detalle_ventas, tu trigger actual descuenta el stock.
            */
            $venta->detalleVentas()->createMany($resultado);

            $correlativo->ultimo_numero = $nuevo_correlativo;
            $correlativo->save();

            DB::connection('mysql')->commit();

            $error = false;
            $mensaje = 'Venta creada con éxito';

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();
            $error = true;
            $mensaje = 'No se pudo registrar la venta. Verifique los datos ingresados.';
        }

        return Response::json([
            'error' => $error,
            'mensaje' => $mensaje
        ]);
    }

    public function destroy($id)
    {
        $mensaje = '';
        $error = true;

        if (!Auth::user()->can('eliminar ventas')) {
            return Response::json([
                'error' => true,
                'mensaje' => 'Permiso denegado'
            ]);
        }

        DB::connection('mysql')->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
        DB::connection('mysql')->beginTransaction();

        try {
            $venta = Venta::where('id', $id)
                ->lockForUpdate()
                ->first();

            if (empty($venta)) {
                throw new \Exception('La venta no existe');
            }

            if ($venta->estado_id != 3) {
                throw new \Exception('La venta ya está anulada');
            }

            $venta->estado_id = 4;
            $venta->save();

            DB::connection('mysql')->commit();

            $error = false;
            $mensaje = 'Venta anulada con éxito';

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollBack();

            $error = true;
            $mensaje = 'Error ' . $th->getMessage();
        }

        return Response::json([
            'error' => $error,
            'mensaje' => $mensaje
        ]);
    }

    public function detalleVenta($id)
    {
        $venta = Venta::findOrFail($id);
        if (Auth::user()->can('ver ventas')) {
            try {
                $detalleVentas = $venta->detalleVentas;
                return view('ventas.detalleVenta', compact('venta', 'detalleVentas'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function reportDay()
    {
        $ventas = Venta::whereDate('fecha', Carbon::today('America/Guatemala'))->where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $total = $ventas->sum('total');
                return view('ventas.reportDay', compact('ventas', 'total'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function reportDate()
    {
        $ventas = Venta::where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $total = $ventas->sum('total');
                return view('ventas.reportDate', compact('total', 'ventas'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function reportResult(Request $request)
    {
        $ventas = Venta::where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $fi = $request->fechaInicio . ' 00:00:00';
                $ff = $request->fechaFinal . ' 23:59:59';
                $ventas = $ventas->whereBetween('fecha', [$fi, $ff]);
                $total = $ventas->sum('total');
                return view('ventas.reportDate', compact('ventas', 'total'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }
    public function pdf($id)
    {
        $venta = Venta::findOrFail($id);
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $detalleVentas = $venta->detalleVentas;
                $pdf = Pdf::loadView('ventas.pdf', compact('venta', 'detalleVentas'));
                return $pdf->download('Reporte_de_venta_' . $venta->id . '.pdf');
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAut', $error);
                return redirect('admin');
            }
        } else {
            Session::flash('eAut', 'Error, permiso denegado');
            return redirect('admin');
        }
    }
    public function ReportePdf()
    {
        $ventas = Venta::whereDate('fecha', Carbon::today('America/Guatemala'))->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $total = $ventas->sum('total');
                $pdf = Pdf::loadView('ventas.reportDayPdf', compact('ventas', 'total'));
                return $pdf->download('Reporte_de_ventas_'. Carbon::now()->format("d/m/Y H:i:s") .'.pdf');
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }
    public function print($id)
    {
        $venta = Venta::findOrFail($id);
        if (Auth::user()->can('ver ventas')) {
            try {
                $subtotal = 0;
                $detalleVentas = $venta->detalleVentas;
                foreach ($detalleVentas as $detalleVenta) {
                    $subtotal += ((($detalleVenta->cantidad * $detalleVenta->precio) + $detalleVenta->extra) - $detalleVenta->descuento);
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
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('error', $error);
                return redirect()->back();
            }
        } else {
            Session::flash('error', 'Error, permiso denegado');
            return redirect('home');
        }
    }
}

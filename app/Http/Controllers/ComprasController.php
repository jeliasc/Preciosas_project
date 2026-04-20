<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ComprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->can('ver compras')) {
            try {
                $compras = Compra::where('estado_id', '3')->get();
                return view('compras.index', compact('compras'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }
    }
    public function comprasAnuladas()
    {
        if (Auth::user()->can('ver compras')) {
            try {
                $compras = Compra::where('estado_id', '4')->get();
                return view('compras.comprasAnuladas', compact('compras'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }
    }

    public function create()
    {
        if (Auth::user()->can('crear compras')) {
            try {
                $mensaje = '';
                $error = true;
                $proveedores = Proveedor::all();
                $productos = Producto::all();

                if (empty($proveedores)) {
                    $error = true;
                    $mensaje = 'Proveedores vacío';
                } elseif (empty($productos)) {
                    $error = true;
                    $mensaje = 'Productos vacío';
                } else {
                    $error = false;
                    $mensaje = 'Consulta exitosa';
                }
            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error ' . $th->getMessage();
            }
        } else {
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje, 'productos' => $productos, 'proveedores' => $proveedores));
    }

    public function store(Request $request)
    {
        $mensaje = '';
        $error = true;
        $entrada = $request->all();
        $no_fact = $request->input('no_fac');

        unset($request['_token']);

        if (Auth::user()->can('crear compras',)) {
            DB::connection('mysql')->beginTransaction();
            try {
                if (empty($entrada)) {
                    $error = true;
                    $mensaje = 'Error, no se pudo crear la compra';
                } else {
                    $compra = Compra::create($request->all() + [
                        'user_id' => Auth::user()->id,
                        'fecha' => Carbon::now('America/Guatemala'),
                        'no_factura' => $no_fact,
                    ]);
                    foreach ($request->producto_id as $key => $producto) {
                        $resultado[] = array(
                            'producto_id' => $request->producto_id[$key],
                            "cantidad" => $request->cantidad[$key], "precio" => $request->precio[$key]
                        );
                    }
                    $compra->detalleCompras()->createMany($resultado);
                    DB::connection('mysql')->commit();
                    $error = false;
                    $mensaje = 'Compra creada con éxito';
                }
            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error ' . $th->getMessage();
            }
        } else {
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function destroy($id)
    {
        if (auth::user()->can('eliminar compras')) {

            DB::connection('mysql')->beginTransaction();
            try {
                $mensaje = '';
                $error = true;
                $compra = Compra::where('id', $id)->first();

                if (empty($compra)) {
                    $error = true;
                    $mensaje = 'La compra no existe';
                } else if ($compra->estado_id == 3) {
                    $error = false;
                    $compra->estado_id = 4;
                    $mensaje = 'Compra anulada con éxito';
                    DB::connection('mysql')->commit();
                    $compra->save();
                } else {
                    $error = true;
                    $mensaje = 'La compra esta anulada';
                }
            } catch (\Throwable $th) {
                $error = true;
                $mensaje = 'Error ' . $th->getMessage();
            }
        } else {
            $error = true;
            $mensaje = 'Permiso denegado';
        }
        return Response::json(array('error' => $error, 'mensaje' => $mensaje));
    }

    public function detalleCompra($id)
    {
        $compra = Compra::findOrFail($id);
        if (Auth::user()->can('ver compras')) {
            try {
                $detalleCompras = $compra->detalleCompras;
                return view('compras.detalleCompra', compact('compra', 'detalleCompras'));
            } catch (\Throwable $th) {
                $error = "Error";
                $error = $error . '' . $th->getMessage();
                Session::flash('eAuth', 'Error, Permiso denegado.');
                return redirect('home');
            }
        } else {
            Session::flash('eAuth', 'Error, Permiso denegado.');
            return redirect('home');
        }
    }

    public function reportDay()
    {
        $compras = Compra::whereDate('fecha', Carbon::today('America/Guatemala'))->where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $total = $compras->sum('total');
                return view('compras.reportDay', compact('compras', 'total'));
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
        $compras = Compra::where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $total = $compras->sum('total');
                return view('compras.reportDate', compact('total', 'compras'));
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
        $compras = Compra::where('estado_id', '3')->get();
        if (Auth::user()->can('ver reporte de ventas')) {
            try {
                $fi = $request->fechaInicio . ' 00:00:00';
                $ff = $request->fechaFinal . ' 23:59:59';
                $compras = $compras->whereBetween('fecha', [$fi, $ff]);
                $total = $compras->sum('total');
                return view('compras.reportDate', compact('compras', 'total'));
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
        $compra = Compra::findOrFail($id);
        if (Auth::user()->can('ver compras')) {
            try {
                $detalleCompras = $compra->detalleCompras;
                $pdf = Pdf::loadView('compras.pdf', compact('compra', 'detalleCompras'));
                return $pdf->download('Reporte_de_compra_' . $compra->id . '.pdf');
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

    public function ReportePdf()
    {
        $compras = Compra::whereDate('fecha', Carbon::today('America/Guatemala'))->get();
        if (Auth::user()->can('ver compras')) {
            try {
                $total = $compras->sum('total');
                $pdf = Pdf::loadView('compras.reportDayPdf', compact('compras', 'total'));
                return $pdf->download('Reporte_de_compras_'. Carbon::now()->format("d/m/Y H:i:s") .'.pdf');
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
}

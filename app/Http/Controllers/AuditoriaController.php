<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
public function index()
    {
        $auditoria = DB::table('auditoria_movimientos as a')
            ->leftJoin('users as u', 'a.usuario_app_id', '=', 'u.id')
            ->select('a.*', 'u.name as usuario_nombre')
            ->orderBy('a.id', 'desc')
            ->get();

        // transformar registro_id a nombre
        foreach ($auditoria as $item) {

            switch ($item->tabla_afectada) {

                case 'users':
                    $item->registro_nombre = DB::table('users')
                        ->where('id', $item->registro_id)
                        ->value('name');
                    break;

                case 'model_has_roles':
                    $usuarioRol = DB::table('users')
                        ->where('id', $item->registro_id)
                        ->value('name');

                    $item->registro_nombre = $usuarioRol ?? $item->registro_id;
                    break;

                case 'productos':
                    $item->registro_nombre = DB::table('productos')
                        ->where('id', $item->registro_id)
                        ->value('nombre');
                    break;

                case 'proveedors':
                    $item->registro_nombre = DB::table('proveedors')
                        ->where('id', $item->registro_id)
                        ->value('nombre');
                    break;

                case 'roles':
                    $item->registro_nombre = DB::table('roles')
                        ->where('id', $item->registro_id)
                        ->value('name');
                    break;

                case 'compras':
                    $item->registro_nombre = 'Compra #' . $item->registro_id;
                    break;

                case 'ventas':
                    $item->registro_nombre = 'Venta #' . $item->registro_id;
                    break;

                default:
                    $item->registro_nombre = $item->registro_id;
                    break;
            }
        }

        return view('auditoria.index', compact('auditoria'));
    }

   public function show($id)
    {
        $row = DB::table('auditoria_movimientos')->where('id', $id)->first();

        $anteriores = json_decode($row->datos_anteriores, true);
        $nuevos = json_decode($row->datos_nuevos, true);

        //  usuario
        if (isset($anteriores['user_id'])) {
            $anteriores['user_id'] = DB::table('users')
                ->where('id', $anteriores['user_id'])
                ->value('name');
        }

        if (isset($nuevos['user_id'])) {
            $nuevos['user_id'] = DB::table('users')
                ->where('id', $nuevos['user_id'])
                ->value('name');
        }

        //  proveedor
        if (isset($anteriores['proveedor_id'])) {
            $anteriores['proveedor_id'] = DB::table('proveedors')
                ->where('id', $anteriores['proveedor_id'])
                ->value('nombre');
        }

        if (isset($nuevos['proveedor_id'])) {
            $nuevos['proveedor_id'] = DB::table('proveedors')
                ->where('id', $nuevos['proveedor_id'])
                ->value('nombre');
        }

        //  estado
        if (isset($anteriores['estado_id'])) {
            $anteriores['estado_id'] = DB::table('estados')
                ->where('id', $anteriores['estado_id'])
                ->value('nombre');
        }

        if (isset($nuevos['estado_id'])) {
            $nuevos['estado_id'] = DB::table('estados')
                ->where('id', $nuevos['estado_id'])
                ->value('nombre');
        }

        // rol
        if (isset($anteriores['role_id'])) {
            $anteriores['role_id'] = DB::table('roles')
                ->where('id', $anteriores['role_id'])
                ->value('name');
        }

        if (isset($nuevos['role_id'])) {
            $nuevos['role_id'] = DB::table('roles')
                ->where('id', $nuevos['role_id'])
                ->value('name');
        }

        // cliente
        if (isset($anteriores['cliente_id'])) {
            $anteriores['cliente_id'] = DB::table('clientes')
                ->where('id', $anteriores['cliente_id'])
                ->value('nombre');
        }

        if (isset($nuevos['cliente_id'])) {
            $nuevos['cliente_id'] = DB::table('clientes')
                ->where('id', $nuevos['cliente_id'])
                ->value('nombre');
        }

        // categoría
        if (isset($anteriores['categoria_id'])) {
            $anteriores['categoria_id'] = DB::table('categorias')
                ->where('id', $anteriores['categoria_id'])
                ->value('nombre');
        }

        if (isset($nuevos['categoria_id'])) {
            $nuevos['categoria_id'] = DB::table('categorias')
                ->where('id', $nuevos['categoria_id'])
                ->value('nombre');
        }

        if ($row->tabla_afectada == 'role_has_permissions') {

        if (isset($anteriores['permission_id'])) {
            $anteriores['permission_id'] = DB::table('permissions')
                ->where('id', $anteriores['permission_id'])
                ->value('name');
        }

        if (isset($nuevos['permission_id'])) {
            $nuevos['permission_id'] = DB::table('permissions')
                ->where('id', $nuevos['permission_id'])
                ->value('name');
        }

        if (isset($anteriores['role_id'])) {
            $anteriores['role_id'] = DB::table('roles')
                ->where('id', $anteriores['role_id'])
                ->value('name');
        }

        if (isset($nuevos['role_id'])) {
            $nuevos['role_id'] = DB::table('roles')
                ->where('id', $nuevos['role_id'])
                ->value('name');
        }
    }

        return response()->json([
            'anteriores' => $anteriores,
            'nuevos' => $nuevos
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::create(['name' => 'Super-Administrador']);

        Permission::create(['name'=>'ver home'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver roles'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear roles'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar roles'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar roles'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver usuarios'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear usuarios'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar usuarios'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar usuarios'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver categorias'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear categorias'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar categorias'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar categorias'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver clientes'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear clientes'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar clientes'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar clientes'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver articulos'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear articulos'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar articulos'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar articulos'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver proveedores'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear proveedores'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar proveedores'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar proveedores'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver compras'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear compras'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar compras'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar compras'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver ventas'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'crear ventas'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar ventas'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar ventas'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'ver reporte de ventas'])->syncRoles([$superadminRole]);

        Permission::create(['name'=>'ver perfil'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar perfil'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'eliminar perfil'])->syncRoles([$superadminRole]);
        Permission::create(['name'=>'editar password'])->syncRoles([$superadminRole]);


        $user = new User();
        $user->name = 'Luis Elias';
        $user->email= 'superadmin@gmail.com';
        $user->password = bcrypt('admin');
        $user->save();
        $user->assignRole($superadminRole);
    }
}

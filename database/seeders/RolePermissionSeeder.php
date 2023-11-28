<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'crear',
            'eliminar',
            'consultar',
            'actualizar'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear rol de admin y asignar todos los permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Crear rol de cliente y asignar solo el permiso de consultar
        $clientRole = Role::create(['name' => 'cliente']);
        $clientRole->givePermissionTo('consultar');
    }
}

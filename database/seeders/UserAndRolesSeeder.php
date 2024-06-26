<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Resetea la memoria caché de roles y permisos para evitar errores
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para un sistema administrativo
        $permissions = [
            'listUsers',
            'createUser',
            'editUser',
            'detailUser',
            'deleteUser',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar los permisos
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo(Permission::all());

        // Crear un usuario de ejemplo
        $user = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Kharma Solutions - Admin',
            'email' => 'admin@kharma-s.com',
            'password' => Hash::make('123456'),
            'department' => 'Administracion General',
            'position' => 'Administracion del sistema',
            'phone' => '7775944783',
            'is_active' => true,
            'last_login' => now()
        ]);

        // Asignar el rol de Administrador al usuario
        $user->assignRole($adminRole);
    }
}

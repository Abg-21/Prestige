<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear el rol de Administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);

        // Crear usuario Admin si no existe
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('Admin123'),
        ]);

        // Asignarle el rol de Administrador
        $admin->assignRole($adminRole);
    }
}

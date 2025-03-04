<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Llamar a los seeders en orden
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class, // Asegúrate de que esta línea esté incluida
        ]);
    }
}

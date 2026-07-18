<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles y permisos siempre necesarios
        $this->call(RolePermissionSeeder::class);

        $this->call(TestDataSeeder::class);
        $this->call(HorariosSeeder::class);
        
    }
}

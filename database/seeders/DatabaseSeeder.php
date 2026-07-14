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
        $this->call(RolePermissionSeeder::class);
        $this->call(TestDataSeeder::class);
        // Horarios necesarios para pruebas en el landing (estilistas y recepcionistas)
        $this->call(HorariosSeeder::class);
    }
}

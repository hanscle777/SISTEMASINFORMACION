<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\User;

class HorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear horarios para estilistas
        $stylists = User::whereHas('role', function ($q) {
            $q->where('slug', 'estilista');
        })->get();

        foreach ($stylists as $stylist) {
            foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia) {
                Horario::updateOrCreate(
                    ['user_id' => $stylist->id, 'dia_semana' => $dia],
                    ['hora_inicio' => '09:00:00', 'hora_fin' => '18:00:00', 'activo' => true]
                );
            }

            Horario::updateOrCreate(
                ['user_id' => $stylist->id, 'dia_semana' => 'Sábado'],
                ['hora_inicio' => '09:00:00', 'hora_fin' => '17:00:00', 'activo' => true]
            );
        }

        // Crear horarios para recepcionistas (si existen)
        $receptionists = User::whereHas('role', function ($q) {
            $q->where('slug', 'recepcionista');
        })->get();

        foreach ($receptionists as $r) {
            foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia) {
                Horario::updateOrCreate(
                    ['user_id' => $r->id, 'dia_semana' => $dia],
                    ['hora_inicio' => '08:30:00', 'hora_fin' => '18:30:00', 'activo' => true]
                );
            }
        }
    }
}

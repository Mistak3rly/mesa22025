<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dia;

class DiasSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $dias = [
            'lunes',
            'martes', 
            'miercoles',
            'jueves',
            'viernes',
            'sabado',
            'domingo'
        ];

        foreach ($dias as $dia) {
            Dia::firstOrCreate(
                ['descripcion' => $dia],
                ['estado' => true]
            );
        }

        $this->command->info('✅ Días de la semana creados exitosamente');
        $this->command->info('📅 Días disponibles: ' . implode(', ', $dias));
    }
}
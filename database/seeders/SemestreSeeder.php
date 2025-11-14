<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semestre;
use Carbon\Carbon;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear semestre actual (2025-1)
        Semestre::firstOrCreate([
            'descripcion' => '2025-1'
        ], [
            'fecha_inicio' => Carbon::now()->startOfYear()->format('Y-m-d'),
            'fecha_fin' => Carbon::now()->endOfYear()->format('Y-m-d'),
            'estado' => true
        ]);

        $this->command->info('Semestre activo creado exitosamente:');
        $this->command->info('Descripción: 2025-1');
        $this->command->info('Estado: Activo');
    }
}

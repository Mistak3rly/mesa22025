<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aula;

class AulaSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $aulas = [
            // Primer Piso - Aulas 10 al 15 (Capacidad: 40 estudiantes)
            ['numero_aula' => 10, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],
            ['numero_aula' => 11, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],
            ['numero_aula' => 12, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],
            ['numero_aula' => 13, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],
            ['numero_aula' => 14, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],
            ['numero_aula' => 15, 'tipo_aula' => 'Aula - Primer Piso', 'capacidad' => 40],

            // Segundo Piso - Aulas 20 al 25 (Capacidad: 40 estudiantes)
            ['numero_aula' => 20, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],
            ['numero_aula' => 21, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],
            ['numero_aula' => 22, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],
            ['numero_aula' => 23, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],
            ['numero_aula' => 24, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],
            ['numero_aula' => 25, 'tipo_aula' => 'Aula - Segundo Piso', 'capacidad' => 40],

            // Tercer Piso - Aulas 30 al 35 (Capacidad: 60 estudiantes)
            ['numero_aula' => 30, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],
            ['numero_aula' => 31, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],
            ['numero_aula' => 32, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],
            ['numero_aula' => 33, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],
            ['numero_aula' => 34, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],
            ['numero_aula' => 35, 'tipo_aula' => 'Aula - Tercer Piso', 'capacidad' => 60],

            // Cuarto Piso - Laboratorios 40 al 45 (Capacidad: 60 estudiantes)
            ['numero_aula' => 40, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],
            ['numero_aula' => 41, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],
            ['numero_aula' => 42, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],
            ['numero_aula' => 43, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],
            ['numero_aula' => 44, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],
            ['numero_aula' => 45, 'tipo_aula' => 'Laboratorio - Cuarto Piso', 'capacidad' => 60],

            // Auditorio - Cuarto Piso (Capacidad: 150 estudiantes)
            ['numero_aula' => 100, 'tipo_aula' => 'Auditorio - Cuarto Piso', 'capacidad' => 150],
        ];

        foreach ($aulas as $aulaData) {
            Aula::firstOrCreate(
                ['numero_aula' => $aulaData['numero_aula']],
                [
                    'tipo_aula' => $aulaData['tipo_aula'],
                    'capacidad' => $aulaData['capacidad'],
                    'estado' => true
                ]
            );
        }

        $this->command->info('✅ Aulas creadas exitosamente');
        $this->command->info('🏢 Primer Piso: Aulas 10-15 (6 aulas - 40 estudiantes c/u)');
        $this->command->info('🏢 Segundo Piso: Aulas 20-25 (6 aulas - 40 estudiantes c/u)');
        $this->command->info('🏢 Tercer Piso: Aulas 30-35 (6 aulas - 60 estudiantes c/u)');
        $this->command->info('🔬 Cuarto Piso: Laboratorios 40-45 (6 laboratorios - 60 estudiantes c/u)');
        $this->command->info('🎭 Cuarto Piso: Auditorio (1 auditorio - 150 estudiantes)');
        $this->command->info('📊 Total: ' . count($aulas) . ' espacios creados');
    }
}

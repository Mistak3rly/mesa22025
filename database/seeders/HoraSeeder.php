<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hora;

class HoraSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $horas = [
            // TURNO MAÑANA (7:00 - 12:00)
            
            // Bloques de 1.5 horas (1 hora 30 minutos)
            ['hora_inicio' => '07:00', 'hora_fin' => '08:30', 'turno' => 'Mañana'],
            ['hora_inicio' => '08:30', 'hora_fin' => '10:00', 'turno' => 'Mañana'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'turno' => 'Mañana'],
            ['hora_inicio' => '11:30', 'hora_fin' => '13:00', 'turno' => 'Mañana'],
            
            // Bloques de 2 horas
            ['hora_inicio' => '07:00', 'hora_fin' => '09:00', 'turno' => 'Mañana'],
            ['hora_inicio' => '09:00', 'hora_fin' => '11:00', 'turno' => 'Mañana'],
            ['hora_inicio' => '11:00', 'hora_fin' => '13:00', 'turno' => 'Mañana'],
            
            // Bloques de 3 horas
            ['hora_inicio' => '07:00', 'hora_fin' => '10:00', 'turno' => 'Mañana'],
            ['hora_inicio' => '10:00', 'hora_fin' => '13:00', 'turno' => 'Mañana'],
            
            // TURNO TARDE (13:00 - 18:00)
            
            // Bloques de 1.5 horas
            ['hora_inicio' => '13:00', 'hora_fin' => '14:30', 'turno' => 'Tarde'],
            ['hora_inicio' => '14:30', 'hora_fin' => '16:00', 'turno' => 'Tarde'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'turno' => 'Tarde'],
            ['hora_inicio' => '17:30', 'hora_fin' => '19:00', 'turno' => 'Tarde'],
            
            // Bloques de 2 horas
            ['hora_inicio' => '13:00', 'hora_fin' => '15:00', 'turno' => 'Tarde'],
            ['hora_inicio' => '15:00', 'hora_fin' => '17:00', 'turno' => 'Tarde'],
            ['hora_inicio' => '17:00', 'hora_fin' => '19:00', 'turno' => 'Tarde'],
            
            // Bloques de 3 horas
            ['hora_inicio' => '13:00', 'hora_fin' => '16:00', 'turno' => 'Tarde'],
            ['hora_inicio' => '16:00', 'hora_fin' => '19:00', 'turno' => 'Tarde'],
            
            // TURNO NOCHE (19:00 - 22:45)
            
            // Bloques de 1.5 horas
            ['hora_inicio' => '19:00', 'hora_fin' => '20:30', 'turno' => 'Noche'],
            ['hora_inicio' => '20:30', 'hora_fin' => '22:00', 'turno' => 'Noche'],
            ['hora_inicio' => '21:15', 'hora_fin' => '22:45', 'turno' => 'Noche'],
            
            // Bloques de 2 horas
            ['hora_inicio' => '19:00', 'hora_fin' => '21:00', 'turno' => 'Noche'],
            ['hora_inicio' => '20:45', 'hora_fin' => '22:45', 'turno' => 'Noche'],
            
            // Bloques de 3 horas
            ['hora_inicio' => '19:00', 'hora_fin' => '22:00', 'turno' => 'Noche'],
            
            // HORARIOS ESPECIALES PARA LABORATORIOS
            
            // Bloques extendidos de mañana
            ['hora_inicio' => '08:00', 'hora_fin' => '11:00', 'turno' => 'Mañana'],
            ['hora_inicio' => '09:30', 'hora_fin' => '12:30', 'turno' => 'Mañana'],
            
            // Bloques extendidos de tarde
            ['hora_inicio' => '14:00', 'hora_fin' => '17:00', 'turno' => 'Tarde'],
            ['hora_inicio' => '15:30', 'hora_fin' => '18:30', 'turno' => 'Tarde'],
            
            // HORARIOS PARA FINES DE SEMANA
            
            // Sábados - Bloques intensivos
            ['hora_inicio' => '08:00', 'hora_fin' => '10:30', 'turno' => 'Sábado'],
            ['hora_inicio' => '10:30', 'hora_fin' => '13:00', 'turno' => 'Sábado'],
            ['hora_inicio' => '14:00', 'hora_fin' => '16:30', 'turno' => 'Sábado'],
            ['hora_inicio' => '16:30', 'hora_fin' => '19:00', 'turno' => 'Sábado'],
            
            // Bloques de 3 horas para sábados
            ['hora_inicio' => '08:00', 'hora_fin' => '11:00', 'turno' => 'Sábado'],
            ['hora_inicio' => '14:00', 'hora_fin' => '17:00', 'turno' => 'Sábado'],
        ];

        foreach ($horas as $horaData) {
            Hora::firstOrCreate(
                [
                    'hora_inicio' => $horaData['hora_inicio'],
                    'hora_fin' => $horaData['hora_fin']
                ],
                [
                    'turno' => $horaData['turno'],
                    'estado' => true
                ]
            );
        }

        $this->command->info('✅ Horarios de clases creados exitosamente');
        $this->command->info('🌅 Turno Mañana: 7:00 - 13:00');
        $this->command->info('🌞 Turno Tarde: 13:00 - 19:00');
        $this->command->info('🌙 Turno Noche: 19:00 - 22:45');
        $this->command->info('📅 Sábados: 8:00 - 19:00');
        $this->command->info('⏱️ Duraciones: 1.5h, 2h, 3h');
        $this->command->info('📊 Total: ' . count($horas) . ' bloques horarios creados');
    }
}
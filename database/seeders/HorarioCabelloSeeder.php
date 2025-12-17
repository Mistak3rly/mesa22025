<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\Grupo;
use App\Models\Aula;
use App\Models\Dia;
use App\Models\Hora;
use App\Models\Semestre;
use App\Models\GrupoAsignatura;

class HorarioCabelloSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Buscar al docente Cabello
        $docente = Docente::with('persona')
            ->whereHas('persona', function($q) {
                $q->where('nombre', 'LIKE', '%CABELLO%');
            })
            ->first();

        if (!$docente) {
            $this->command->error('❌ Docente Cabello no encontrado');
            return;
        }

        // Buscar miércoles
        $miercoles = Dia::where('descripcion', 'LIKE', '%miercoles%')
            ->orWhere('descripcion', 'LIKE', '%miércoles%')
            ->orWhere('descripcion', 'LIKE', '%wednesday%')
            ->first();

        if (!$miercoles) {
            $this->command->error('❌ Día miércoles no encontrado');
            return;
        }

        // Buscar horario de 16:00
        $hora16 = Hora::where('hora_inicio', '16:00:00')
            ->orWhere('hora_inicio', '16:00')
            ->first();

        if (!$hora16) {
            // Crear el horario de 16:00 a 17:30 si no existe
            $hora16 = Hora::create([
                'hora_inicio' => '16:00',
                'hora_fin' => '17:30',
                'turno' => 'Tarde',
                'estado' => true
            ]);
            $this->command->info('✅ Creado horario 16:00 - 17:30');
        }

        // Obtener semestre activo
        $semestre = Semestre::where('estado', true)->first();
        
        // Buscar una asignatura disponible (usaremos la primera)
        $asignatura = Asignatura::where('estado', true)->first();
        
        // Buscar un grupo disponible
        $grupo = Grupo::where('estado', true)->first();
        
        // Buscar un aula disponible
        $aula = Aula::where('estado', true)->first();

        // Verificar si ya existe una asignación de esta asignatura al docente
        $asignacionExiste = GrupoAsignatura::where('docente_id', $docente->id)
            ->where('asignatura_id', $asignatura->id)
            ->where('grupo_id', $grupo->id)
            ->where('semestre_id', $semestre->id)
            ->exists();

        if (!$asignacionExiste) {
            // Crear la asignación docente-asignatura-grupo
            GrupoAsignatura::create([
                'docente_id' => $docente->id,
                'asignatura_id' => $asignatura->id,
                'grupo_id' => $grupo->id,
                'semestre_id' => $semestre->id,
                'observacion' => 'Asignación para horario de miércoles 16:00'
            ]);
            $this->command->info('✅ Asignación docente-materia creada');
        }

        // Verificar si ya existe el horario
        $horarioExiste = Horario::where('asignatura_id', $asignatura->id)
            ->where('aula_id', $aula->id)
            ->where('dia_id', $miercoles->id)
            ->where('hora_id', $hora16->id)
            ->where('semestre_id', $semestre->id)
            ->exists();

        if (!$horarioExiste) {
            // Crear el horario
            $horario = Horario::create([
                'asignatura_id' => $asignatura->id,
                'aula_id' => $aula->id,
                'dia_id' => $miercoles->id,
                'hora_id' => $hora16->id,
                'semestre_id' => $semestre->id,
                'estado' => true
            ]);
            
            // Asignar el docente al horario
            $horario->docentes()->attach($docente->id);
            
            $this->command->info('✅ Horario creado para miércoles 16:00');
            $this->command->info('✅ Docente asignado al horario');
        } else {
            $this->command->info('ℹ️ El horario ya existe');
            
            // Verificar si el docente está asignado al horario existente
            $horarioExistente = Horario::where('asignatura_id', $asignatura->id)
                ->where('aula_id', $aula->id)
                ->where('dia_id', $miercoles->id)
                ->where('hora_id', $hora16->id)
                ->where('semestre_id', $semestre->id)
                ->first();
                
            if ($horarioExistente && !$horarioExistente->docentes()->where('docente_id', $docente->id)->exists()) {
                $horarioExistente->docentes()->attach($docente->id);
                $this->command->info('✅ Docente asignado al horario existente');
            }
        }

        $this->command->info('📋 Resumen:');
        $this->command->info("👨‍🏫 Docente: {$docente->persona->nombre}");
        $this->command->info("📚 Materia: {$asignatura->descripcion}");
        $this->command->info("🏫 Aula: {$aula->numero_aula}");
        $this->command->info("📅 Día: {$miercoles->descripcion}");
        $this->command->info("⏰ Hora: {$hora16->hora_inicio} - {$hora16->hora_fin}");
        $this->command->info("🎯 Ventana de asistencia: 30 minutos antes y después");
    }
}
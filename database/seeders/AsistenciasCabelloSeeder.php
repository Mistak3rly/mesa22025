<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Docente;
use App\Models\Asistencia;
use App\Models\Horario;
use Carbon\Carbon;

class AsistenciasCabelloSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('📋 Poblando asistencias del docente Cabello...');

        // Buscar al docente Cabello
        $cabello = Docente::with('persona')
            ->whereHas('persona', function($q) {
                $q->where('nombre', 'LIKE', '%CABELLO%');
            })
            ->first();

        if (!$cabello) {
            $this->command->error('❌ Docente Cabello no encontrado');
            return;
        }

        // Obtener los horarios del docente Cabello
        $horarios = $cabello->horarios()
            ->with(['dia', 'hora'])
            ->get();

        if ($horarios->isEmpty()) {
            $this->command->error('❌ No se encontraron horarios para el docente Cabello');
            return;
        }

        $this->command->info("👨‍🏫 Docente: {$cabello->persona->nombre}");
        $this->command->info("📅 Horarios encontrados: {$horarios->count()}");

        // Definir el rango de fechas (últimos 3 meses)
        $fechaFin = Carbon::now();
        $fechaInicio = Carbon::now()->subMonths(3);

        $this->command->info("📅 Rango de fechas: {$fechaInicio->format('Y-m-d')} a {$fechaFin->format('Y-m-d')}");

        // Mapear días de la semana
        $diasSemana = [
            'lunes' => Carbon::MONDAY,
            'martes' => Carbon::TUESDAY,
            'miercoles' => Carbon::WEDNESDAY,
            'jueves' => Carbon::THURSDAY,
            'viernes' => Carbon::FRIDAY,
            'sabado' => Carbon::SATURDAY,
            'domingo' => Carbon::SUNDAY,
        ];

        $totalAsistencias = 0;
        $estadisticas = ['presente' => 0, 'ausente' => 0, 'justificado' => 0];

        // Generar asistencias para cada horario
        foreach ($horarios as $horario) {
            $diaSemana = $horario->dia->descripcion;
            $diaNumero = $diasSemana[$diaSemana] ?? null;

            if ($diaNumero === null) {
                $this->command->warn("⚠️ Día no reconocido: {$diaSemana}");
                continue;
            }

            $this->command->info("📅 Procesando {$diaSemana} ({$horario->hora->hora_inicio->format('H:i')}-{$horario->hora->hora_fin->format('H:i')})");

            // Obtener todas las fechas de este día en el rango
            $fechaActual = $fechaInicio->copy();
            
            while ($fechaActual->lte($fechaFin)) {
                if ($fechaActual->dayOfWeek === $diaNumero) {
                    // Verificar si ya existe asistencia para esta fecha
                    $asistenciaExiste = Asistencia::where('horario_id', $horario->id)
                        ->whereDate('fecha_hora', $fechaActual->format('Y-m-d'))
                        ->exists();

                    if (!$asistenciaExiste) {
                        // Generar estado de asistencia con probabilidades realistas
                        $estado = $this->generarEstadoAsistencia($fechaActual);
                        
                        // Generar hora aleatoria dentro del rango de clase
                        $horaClase = $this->generarHoraAsistencia($horario, $fechaActual);

                        // Generar observación si es justificado
                        $observacion = null;
                        if ($estado === 'justificado') {
                            $observacion = $this->generarObservacionJustificacion($fechaActual);
                        }

                        // Crear registro de asistencia
                        Asistencia::create([
                            'horario_id' => $horario->id,
                            'estado' => $estado,
                            'fecha_hora' => $horaClase,
                            'observacion' => $observacion
                        ]);

                        $totalAsistencias++;
                        $estadisticas[$estado]++;
                    }
                }
                $fechaActual->addDay();
            }
        }

        // Mostrar estadísticas
        $this->command->info('✅ Asistencias creadas exitosamente');
        $this->command->info("📊 Total de registros: {$totalAsistencias}");
        $this->command->info("✅ Presente: {$estadisticas['presente']} (" . round(($estadisticas['presente'] / $totalAsistencias) * 100, 1) . "%)");
        $this->command->info("❌ Ausente: {$estadisticas['ausente']} (" . round(($estadisticas['ausente'] / $totalAsistencias) * 100, 1) . "%)");
        $this->command->info("📋 Justificado: {$estadisticas['justificado']} (" . round(($estadisticas['justificado'] / $totalAsistencias) * 100, 1) . "%)");
    }

    /**
     * Generar estado de asistencia con probabilidades realistas
     */
    private function generarEstadoAsistencia(Carbon $fecha): string
    {
        // Probabilidades más altas de ausencia en viernes y lunes
        $esFin = $fecha->dayOfWeek === Carbon::FRIDAY;
        $esInicio = $fecha->dayOfWeek === Carbon::MONDAY;
        
        // Probabilidades más altas de ausencia en fechas especiales
        $esFechaEspecial = $this->esFechaEspecial($fecha);
        
        $random = rand(1, 100);
        
        if ($esFechaEspecial) {
            // Mayor probabilidad de ausencia en fechas especiales
            if ($random <= 40) return 'ausente';
            if ($random <= 60) return 'justificado';
            return 'presente';
        } elseif ($esFin || $esInicio) {
            // Ligeramente más ausencias en lunes y viernes
            if ($random <= 15) return 'ausente';
            if ($random <= 25) return 'justificado';
            return 'presente';
        } else {
            // Días normales - alta asistencia
            if ($random <= 8) return 'ausente';
            if ($random <= 15) return 'justificado';
            return 'presente';
        }
    }

    /**
     * Verificar si es una fecha especial (feriados, etc.)
     */
    private function esFechaEspecial(Carbon $fecha): bool
    {
        $fechasEspeciales = [
            // Navidad y Año Nuevo
            '12-25', '12-24', '12-31', '01-01',
            // Día de la Independencia (Bolivia)
            '08-06',
            // Día del Trabajo
            '05-01',
            // Carnaval (fechas aproximadas)
            '02-12', '02-13', '02-14',
            // Semana Santa (fechas aproximadas)
            '03-28', '03-29', '03-30',
        ];

        $fechaFormato = $fecha->format('m-d');
        return in_array($fechaFormato, $fechasEspeciales);
    }

    /**
     * Generar hora de asistencia realista dentro del rango de clase
     */
    private function generarHoraAsistencia(Horario $horario, Carbon $fecha): string
    {
        $horaInicio = Carbon::parse($horario->hora->hora_inicio);
        $horaFin = Carbon::parse($horario->hora->hora_fin);
        
        // Generar hora aleatoria entre 30 minutos antes y 30 minutos después del inicio
        $inicioVentana = $horaInicio->copy()->subMinutes(30);
        $finVentana = $horaInicio->copy()->addMinutes(45); // Más probable que marquen al inicio
        
        $minutosRango = $finVentana->diffInMinutes($inicioVentana);
        $minutosAleatorios = rand(0, $minutosRango);
        
        $horaAsistencia = $inicioVentana->copy()->addMinutes($minutosAleatorios);
        
        // Combinar fecha con hora
        return $fecha->format('Y-m-d') . ' ' . $horaAsistencia->format('H:i:s');
    }

    /**
     * Generar observación realista para justificaciones
     */
    private function generarObservacionJustificacion(Carbon $fecha): string
    {
        $observaciones = [
            'Cita médica programada con anticipación',
            'Enfermedad viral - reposo médico recomendado',
            'Emergencia familiar que requirió atención inmediata',
            'Trámite administrativo en institución pública',
            'Capacitación docente autorizada por coordinación académica',
            'Consulta médica especializada previamente agendada',
            'Problema de salud que impidió asistencia presencial',
            'Reunión académica en otra institución educativa',
            'Gestión de documentos oficiales en entidad gubernamental',
            'Atención médica de familiar directo en situación de emergencia',
            'Participación en congreso educativo autorizado',
            'Cita odontológica de urgencia',
            'Trámite legal que requirió presencia obligatoria',
            'Capacitación en nuevas metodologías pedagógicas',
            'Consulta médica de control periódico'
        ];

        // Si es fecha especial, usar observaciones específicas
        if ($this->esFechaEspecial($fecha)) {
            $observacionesEspeciales = [
                'Celebración de festividad nacional',
                'Feriado cívico - actividades familiares',
                'Día festivo - compromisos familiares',
                'Festividad religiosa - actividades comunitarias'
            ];
            return $observacionesEspeciales[array_rand($observacionesEspeciales)];
        }

        // Si es lunes o viernes, observaciones más específicas
        if ($fecha->dayOfWeek === Carbon::MONDAY || $fecha->dayOfWeek === Carbon::FRIDAY) {
            $observacionesFinSemana = [
                'Viaje de regreso demorado por condiciones climáticas',
                'Compromiso familiar de fin de semana extendido',
                'Gestión médica que requirió desplazamiento a otra ciudad'
            ];
            
            // 30% probabilidad de usar observación específica de fin de semana
            if (rand(1, 100) <= 30) {
                return $observacionesFinSemana[array_rand($observacionesFinSemana)];
            }
        }

        return $observaciones[array_rand($observaciones)];
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Aula;
use App\Models\Semestre;
use App\Models\Dia;
use Carbon\Carbon;

class ReporteAutomaticoController extends Controller
{
    // Reporte de horarios semanal
    public function horarioSemanal(Request $request)
    {
        $semestreId = $request->get('semestre_id', Semestre::where('estado', true)->first()->id);
        $semestre = Semestre::find($semestreId);
        
        $horarios = Horario::with(['asignatura', 'aula', 'dia', 'hora', 'semestre'])
            ->where('semestre_id', $semestreId)
            ->where('estado', true)
            ->orderBy('dia_id')
            ->orderBy('hora_id')
            ->get();

        // Agrupar por día
        $horariosPorDia = $horarios->groupBy('dia.descripcion');

        return view('application.reporte.automatico.horario-semanal', compact('horariosPorDia', 'semestre'));
    }

    // Reporte de ausencias por docente
    public function ausenciasDocente(Request $request)
    {
        $docenteId = $request->get('docente_id');
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        if (!$docenteId) {
            return redirect()->route('reportes.index')
                ->with('error', 'Debe seleccionar un docente');
        }

        $docente = Docente::with('persona')->find($docenteId);
        
        // Obtener asignaturas del docente
        $asignaturasDocente = \App\Models\GrupoAsignatura::where('docente_id', $docenteId)
            ->pluck('asignatura_id')
            ->toArray();
        
        // Obtener asistencias relacionadas con las asignaturas del docente
        $asistencias = Asistencia::with(['horario.asignatura', 'horario.dia', 'horario.hora'])
            ->whereHas('horario', function($q) use ($asignaturasDocente) {
                $q->whereIn('asignatura_id', $asignaturasDocente);
            })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        // Calcular estadísticas
        $totalAsistencias = $asistencias->count();
        $presentes = $asistencias->where('estado', 'presente')->count();
        $ausentes = $asistencias->where('estado', 'ausente')->count();
        $justificados = $asistencias->where('estado', 'justificado')->count();
        
        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($presentes / $totalAsistencias) * 100, 2) 
            : 0;

        return view('application.reporte.automatico.ausencias-docente', compact(
            'docente',
            'asistencias',
            'totalAsistencias',
            'presentes',
            'ausentes',
            'justificados',
            'porcentajeAsistencia',
            'fechaInicio',
            'fechaFin'
        ));
    }

    // Verificar aulas disponibles
    public function aulasDisponibles(Request $request)
    {
        $diaId = $request->get('dia_id');
        $horaId = $request->get('hora_id');
        $semestreId = $request->get('semestre_id', Semestre::where('estado', true)->first()->id);

        $dias = Dia::where('estado', true)->get();
        $horas = \App\Models\Hora::all();
        $todasLasAulas = Aula::where('estado', true)->get();

        if (!$diaId || !$horaId) {
            return view('application.reporte.automatico.aulas-disponibles', compact('dias', 'horas', 'todasLasAulas'));
        }

        $dia = Dia::find($diaId);
        $hora = \App\Models\Hora::find($horaId);
        $semestre = Semestre::find($semestreId);

        // Obtener aulas ocupadas en ese horario
        $aulasOcupadas = Horario::where('dia_id', $diaId)
            ->where('hora_id', $horaId)
            ->where('semestre_id', $semestreId)
            ->where('estado', true)
            ->pluck('aula_id')
            ->toArray();

        // Obtener aulas disponibles
        $aulasDisponibles = Aula::where('estado', true)
            ->whereNotIn('id', $aulasOcupadas)
            ->get();

        // Obtener aulas ocupadas con detalles
        $aulasOcupadasDetalle = Horario::with(['aula', 'asignatura'])
            ->where('dia_id', $diaId)
            ->where('hora_id', $horaId)
            ->where('semestre_id', $semestreId)
            ->where('estado', true)
            ->get();

        return view('application.reporte.automatico.aulas-disponibles', compact(
            'dias',
            'horas',
            'dia',
            'hora',
            'semestre',
            'aulasDisponibles',
            'aulasOcupadasDetalle',
            'todasLasAulas'
        ));
    }
}

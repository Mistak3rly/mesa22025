<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\DocentesExport;
use App\Exports\MateriasExport;
use App\Exports\ReporteDinamicoExport;

use App\Models\GrupoAsignatura;
use App\Models\Semestre;
use App\Models\Docente;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Obtener datos para los filtros
        $semestres = Semestre::orderBy('id', 'desc')->get();
        $docentes = Docente::with('persona')->where('estado', true)->get();
        $grupos = \App\Models\Grupo::where('estado', true)->get();
        $asignaturas = \App\Models\Asignatura::where('estado', true)->get();

        // Inicializar variables
        $reporteData = null;
        $filtrosAplicados = [];

        // Si hay filtros aplicados
        if ($request->has('generar_reporte')) {
            // Usar grupo_asignaturas como base
            $query = GrupoAsignatura::with([
                'docente.persona',
                'asignatura',
                'grupo',
                'semestre'
            ]);

            // Aplicar filtros
            if ($request->filled('semestre_id')) {
                $query->where('semestre_id', $request->semestre_id);
                $filtrosAplicados['semestre'] = Semestre::find($request->semestre_id)->descripcion;
            }

            if ($request->filled('docente_id')) {
                $query->where('docente_id', $request->docente_id);
                $filtrosAplicados['docente'] = Docente::with('persona')->find($request->docente_id)->persona->nombre;
            }

            if ($request->filled('grupo_id')) {
                $query->where('grupo_id', $request->grupo_id);
                $filtrosAplicados['grupo'] = \App\Models\Grupo::find($request->grupo_id)->descripcion;
            }

            if ($request->filled('asignatura_id')) {
                $query->where('asignatura_id', $request->asignatura_id);
                $filtrosAplicados['asignatura'] = \App\Models\Asignatura::find($request->asignatura_id)->descripcion;
            }

            $asignaciones = $query->get();

            // Obtener horarios relacionados si hay filtro de día
            $horarios = collect();
            if ($request->filled('dia_id')) {
                $filtrosAplicados['dia'] = \App\Models\Dia::find($request->dia_id)->descripcion;
                
                // Obtener horarios de las asignaciones filtradas
                $asignaturaIds = $asignaciones->pluck('asignatura_id')->unique();
                $horarios = \App\Models\Horario::with(['asignatura', 'aula', 'dia', 'hora', 'semestre'])
                    ->whereIn('asignatura_id', $asignaturaIds)
                    ->where('dia_id', $request->dia_id)
                    ->when($request->filled('semestre_id'), function($q) use ($request) {
                        $q->where('semestre_id', $request->semestre_id);
                    })
                    ->get();
            }

            // Calcular estadísticas
            $reporteData = $this->calcularEstadisticas($asignaciones, $horarios, $request);
        }

        return view('application.reporte.index', compact(
            'semestres',
            'docentes',
            'grupos',
            'asignaturas',
            'reporteData',
            'filtrosAplicados'
        ));
    }

    private function calcularEstadisticas($asignaciones, $horarios, $request)
    {
        $estadisticas = [
            'asignaciones' => $asignaciones,
            'horarios' => $horarios,
            'total_horarios' => $asignaciones->count(),
            'horas_por_docente' => [],
            'horas_por_materia' => [],
            'asistencias' => [],
            'porcentaje_asistencia' => 0,
            'total_ausencias' => 0,
        ];

        // Calcular horas por docente basado en asignaciones
        $horasPorDocente = $asignaciones->groupBy('docente_id')->map(function ($items) {
            // Estimar 4 horas por materia por semana (2 sesiones de 2 horas)
            $totalHoras = $items->count() * 4;
            return [
                'docente' => $items->first()->docente->persona->nombre,
                'total_horas' => $totalHoras,
                'materias' => $items->count(),
            ];
        });

        $estadisticas['horas_por_docente'] = $horasPorDocente;

        // Calcular horas por materia
        $horasPorMateria = $asignaciones->groupBy('asignatura_id')->map(function ($items) {
            return [
                'materia' => $items->first()->asignatura->descripcion,
                'codigo' => $items->first()->asignatura->codigo,
                'total_horas' => $items->count() * 4,
                'grupos' => $items->pluck('grupo.descripcion')->unique()->implode(', '),
            ];
        });

        $estadisticas['horas_por_materia'] = $horasPorMateria;

        // Obtener asistencias si hay filtros específicos
        if ($request->filled('docente_id') || $request->filled('grupo_id')) {
            // Obtener IDs de asignaturas de las asignaciones filtradas
            $asignaturaIds = $asignaciones->pluck('asignatura_id')->unique();
            
            $asistencias = \App\Models\Asistencia::with(['horario.asignatura'])
                ->whereHas('horario', function ($q) use ($asignaturaIds, $request) {
                    $q->whereIn('asignatura_id', $asignaturaIds);
                    if ($request->filled('semestre_id')) {
                        $q->where('semestre_id', $request->semestre_id);
                    }
                })
                ->get();

            $totalAsistencias = $asistencias->count();
            $presentes = $asistencias->where('estado', 'presente')->count();
            $ausentes = $asistencias->where('estado', 'ausente')->count();

            $estadisticas['asistencias'] = $asistencias;
            $estadisticas['total_ausencias'] = $ausentes;
            $estadisticas['porcentaje_asistencia'] = $totalAsistencias > 0 
                ? round(($presentes / $totalAsistencias) * 100, 2) 
                : 0;
        }

        return $estadisticas;
    }

    public function exportarDinamico(Request $request)
    {
        $query = GrupoAsignatura::with([
            'docente.persona',
            'asignatura',
            'grupo',
            'semestre'
        ]);

        // Aplicar los mismos filtros
        if ($request->filled('semestre_id')) {
            $query->where('semestre_id', $request->semestre_id);
        }
        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }
        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }
        if ($request->filled('asignatura_id')) {
            $query->where('asignatura_id', $request->asignatura_id);
        }

        $asignaciones = $query->get();
        
        // Obtener horarios si hay filtro de día
        $horarios = collect();
        if ($request->filled('dia_id')) {
            $asignaturaIds = $asignaciones->pluck('asignatura_id')->unique();
            $horarios = \App\Models\Horario::with(['asignatura', 'aula', 'dia', 'hora', 'semestre'])
                ->whereIn('asignatura_id', $asignaturaIds)
                ->where('dia_id', $request->dia_id)
                ->when($request->filled('semestre_id'), function($q) use ($request) {
                    $q->where('semestre_id', $request->semestre_id);
                })
                ->get();
        }

        $reporteData = $this->calcularEstadisticas($asignaciones, $horarios, $request);

        $pdf = Pdf::loadView('application.reporte.dinamico-pdf', compact('reporteData', 'request'))
            ->setPaper('a4', 'portrait')
            ->setOption('encoding', 'UTF-8');
        
        return $pdf->download('reporte-dinamico-' . date('Y-m-d') . '.pdf');
    }

    public function exportarDinamicoExcel(Request $request)
    {
        $query = GrupoAsignatura::with([
            'docente.persona',
            'asignatura',
            'grupo',
            'semestre'
        ]);

        // Aplicar los mismos filtros
        if ($request->filled('semestre_id')) {
            $query->where('semestre_id', $request->semestre_id);
        }
        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }
        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }
        if ($request->filled('asignatura_id')) {
            $query->where('asignatura_id', $request->asignatura_id);
        }

        $asignaciones = $query->get();
        
        $horarios = collect();
        if ($request->filled('dia_id')) {
            $asignaturaIds = $asignaciones->pluck('asignatura_id')->unique();
            $horarios = \App\Models\Horario::with(['asignatura', 'aula', 'dia', 'hora', 'semestre'])
                ->whereIn('asignatura_id', $asignaturaIds)
                ->where('dia_id', $request->dia_id)
                ->when($request->filled('semestre_id'), function($q) use ($request) {
                    $q->where('semestre_id', $request->semestre_id);
                })
                ->get();
        }

        $reporteData = $this->calcularEstadisticas($asignaciones, $horarios, $request);

        // Crear archivo Excel con múltiples hojas
        return Excel::download(
            new ReporteDinamicoExport($reporteData, 'docentes'),
            'reporte-dinamico-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function docentesExcel()
    {
        return Excel::download(new DocentesExport, 'docentes.xlsx');
    }

    public function materiasExcel()
    {
        return Excel::download(new MateriasExport, 'materias.xlsx');
    }

    public function docentesPdf()
    {
        // Obtener docentes con su relación persona
        $docentes = Docente::with('persona')->get();

        // Cargar la vista Blade y pasarle los datos
        $pdf = Pdf::loadView('application.reporte.docente', compact('docentes'));

        // Descargar el archivo
        return $pdf->download('docentes.pdf');
    }

    public function materiasPdf()
    {
        $semestreActual = Semestre::orderBy('id', 'desc')->first();

        $grupoAsignaturas = GrupoAsignatura::with(['docente.persona', 'asignatura', 'grupo', 'semestre'])
            ->where('semestre_id', $semestreActual->id)
            ->get();

        $pdf = Pdf::loadView(
            'application.reporte.asignatura', 
            compact('grupoAsignaturas', 'semestreActual')
        );
        return $pdf->download('materias.pdf');
    }

}
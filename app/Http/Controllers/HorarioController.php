<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GrupoAsignatura;
use App\Models\Semestre;
use App\Models\Horario;
use App\Models\Docente;
use App\Models\Aula;
use App\Models\Hora;
use App\Models\Dia;
use App\Models\Asignatura;


class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();
        
        // Obtener todos los docentes con sus horarios del semestre actual
        $docentes = Docente::with(['persona'])
            ->where('estado', true)
            ->get()
            ->map(function($docente) use ($semestre) {
                // Contar horarios del docente en el semestre actual
                $cantidadHorarios = Horario::whereHas('docentes', function($q) use ($docente) {
                        $q->where('docente_id', $docente->id);
                    })
                    ->where('semestre_id', $semestre->id)
                    ->where('estado', true)
                    ->count();

                // Contar asignaturas únicas
                $cantidadAsignaturas = Horario::whereHas('docentes', function($q) use ($docente) {
                        $q->where('docente_id', $docente->id);
                    })
                    ->where('semestre_id', $semestre->id)
                    ->where('estado', true)
                    ->distinct('asignatura_id')
                    ->count('asignatura_id');

                return [
                    'id' => $docente->id,
                    'codigo' => $docente->codigo,
                    'nombre' => $docente->persona->nombre,
                    'correo' => $docente->correo,
                    'cantidad_horarios' => $cantidadHorarios,
                    'cantidad_asignaturas' => $cantidadAsignaturas,
                ];
            })
            ->filter(function($docente) {
                // Filtrar solo docentes que tienen al menos un horario
                return $docente['cantidad_horarios'] > 0;
            });

        return view('application.horario.index', compact('docentes', 'semestre'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'codigo' => 'required|exists:docentes,codigo'
        ]);
        
        $codigo = $request->codigo;

        $semestre = Semestre::orderBy('id', 'desc')->first();

        // Obtener los datos del docente (id, codigo, carnet, nombre)
        $docente = Docente::with(['persona:id,carnet,nombre'])
            ->where('estado', true)
            ->where( 'codigo', $codigo)
            ->select('id', 'codigo', 'persona_id')
            ->first();

        // Filtrar  asignaturas del docente solo del semestre actual
        $items = GrupoAsignatura::where('docente_id', $docente->id)
            ->where('semestre_id', $semestre->id)
            ->with(['asignatura', 'grupo'])
            ->get();

        $asignaciones = $items->map(function ($item) {
            return [
                'asignatura' => $item->asignatura,
                'grupo' => $item->grupo,
            ];
        });

        $dias = Dia::where('estado', true)->get();

        $aulas = Aula::where('estado', true)->get();

        $horas = Hora::where('estado', true)->get();

        return view(
            'application.horario.create', 
            compact(
                'semestre', 'docente', 'asignaciones', 'dias', 'aulas', 'horas'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hora_id' => 'required|exists:horas,id',
            'dia_id' => 'required|exists:dias,id',
            'dia_id.*' => 'exists:dias,id',
            'aula_id' => 'required|exists:aulas,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'semestre_id' => 'required|exists:semestres,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'observacion' => 'nullable|string'
        ]);

        $errores = [];
        $horariosCreados = 0;

        // Obtener datos para mensajes más informativos
        $aula = Aula::find($validated['aula_id']);
        $hora = Hora::find($validated['hora_id']);
        $asignatura = Asignatura::find($validated['asignatura_id']);
        $docente = $validated['docente_id'] ? Docente::with('persona')->find($validated['docente_id']) : null;

        foreach ($validated['dia_id'] as $dia_id) {
            $dia = Dia::find($dia_id);
            
            // Validar conflicto en aula
            if (Horario::conflictoAula(
                $validated['aula_id'],
                $dia_id,
                $validated['semestre_id'],
                $validated['hora_id']
            )) {
                // Obtener detalles del horario conflictivo
                $horarioConflictivo = Horario::where('aula_id', $validated['aula_id'])
                    ->where('dia_id', $dia_id)
                    ->where('semestre_id', $validated['semestre_id'])
                    ->whereHas('hora', function ($query) use ($hora) {
                        $query->where('hora_inicio', '<', $hora->hora_fin)
                            ->where('hora_fin', '>', $hora->hora_inicio);
                    })
                    ->with(['asignatura', 'hora'])
                    ->first();

                $errores[] = "❌ CONFLICTO DE AULA: El aula {$aula->numero_aula} ya está ocupada el {$dia->descripcion} de {$hora->hora_inicio->format('H:i')} a {$hora->hora_fin->format('H:i')} con la materia {$horarioConflictivo->asignatura->descripcion}.";
                continue;
            }

            // Validar conflicto docente
            if ($docente && Horario::conflictoDocente(
                $validated['docente_id'],
                $dia_id,    
                $validated['semestre_id'],
                $validated['hora_id']
            )) {
                // Obtener detalles del horario conflictivo del docente
                $horarioConflictivo = Horario::where('dia_id', $dia_id)
                    ->where('semestre_id', $validated['semestre_id'])
                    ->whereHas('hora', function ($query) use ($hora) {
                        $query->where('hora_inicio', '<', $hora->hora_fin)
                            ->where('hora_fin', '>', $hora->hora_inicio);
                    })
                    ->whereHas('docentes', function ($q) use ($validated) {
                        $q->where('docente_id', $validated['docente_id']);
                    })
                    ->with(['asignatura', 'aula', 'hora'])
                    ->first();

                $errores[] = "❌ CONFLICTO DE DOCENTE: El docente {$docente->persona->nombre} ya tiene clase el {$dia->descripcion} de {$horarioConflictivo->hora->hora_inicio->format('H:i')} a {$horarioConflictivo->hora->hora_fin->format('H:i')} en el aula {$horarioConflictivo->aula->numero_aula} con la materia {$horarioConflictivo->asignatura->descripcion}.";
                continue;
            }

            // Crear horario si no hay conflictos
            $horario = Horario::create([
                'hora_id' => $validated['hora_id'],
                'dia_id' => $dia_id,
                'aula_id' => $validated['aula_id'],
                'asignatura_id' => $validated['asignatura_id'],
                'semestre_id' => $validated['semestre_id'],
                'observacion' => $validated['observacion'] ?? null,
            ]);

            // Asignar docente
            if ($validated['docente_id']) {
                $horario->docentes()->attach($validated['docente_id']);
            }

            $horariosCreados++;
        }

        if (count($errores) > 0) {
            $mensaje = "Se detectaron conflictos de horarios:";
            return back()
                ->withErrors(['conflictos' => $errores])
                ->with('warning', $mensaje)
                ->withInput();
        }

        // uso de la bitacora
        $nombreDocente = $docente ? $docente->persona->nombre : 'Sin docente asignado';
        registrar_bitacora(
            "Se crearon {$horariosCreados} horarios para {$nombreDocente} - Materia: {$asignatura->descripcion}"
        );

        $mensajeExito = "✅ Se crearon {$horariosCreados} horarios exitosamente para {$nombreDocente} en la materia {$asignatura->descripcion}.";
        
        return redirect()->route('horarios.index')
            ->with('success', $mensajeExito);

    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        //
    }

    /**
     * Mostrar horario de un docente específico
     */
    public function verHorarioDocente($docente_id)
    {
        $docente = Docente::with('persona')->findOrFail($docente_id);
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();

        $horarios = Horario::whereHas('docentes', function($q) use ($docente_id) {
                $q->where('docente_id', $docente_id);
            })
            ->with(['asignatura', 'aula', 'dia', 'hora'])
            ->where('semestre_id', $semestre->id)
            ->where('estado', true)
            ->get()
            ->map(function($h) use ($semestre) {
                return [
                    'id'          => $h->id,
                    'title'       => $h->asignatura->descripcion . ' - Aula ' . $h->aula->numero_aula,
                    'dia_semana'  => strtolower($h->dia->descripcion),
                    'hora_inicio' => $h->hora->hora_inicio->format('H:i'),
                    'hora_fin'    => $h->hora->hora_fin->format('H:i'),
                    'semestre_inicio' => $semestre->fecha_inicio,
                    'semestre_fin'    => $semestre->fecha_fin,
                ];
            });

        return view('application.horario.ver-docente', compact('horarios', 'docente'));
    }

    /**
     * Mostrar formulario de asignación automática
     */
    public function asignacionAutomatica()
    {
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();
        $dias = Dia::where('estado', true)->get();
        $horas = Hora::where('estado', true)->orderBy('hora_inicio')->get();
        
        // Obtener estadísticas actuales
        $totalDocentes = Docente::where('estado', true)->count();
        $totalAsignaturas = Asignatura::where('estado', true)->count();
        $totalAulas = Aula::where('estado', true)->count();
        $horariosExistentes = Horario::where('semestre_id', $semestre->id)->where('estado', true)->count();
        
        // Obtener asignaciones pendientes (docentes con asignaturas pero sin horarios)
        $asignacionesPendientes = GrupoAsignatura::where('semestre_id', $semestre->id)
            ->with(['docente.persona', 'asignatura', 'grupo'])
            ->whereDoesntHave('docente.horarios', function($q) use ($semestre) {
                $q->where('semestre_id', $semestre->id)->where('estado', true);
            })
            ->get()
            ->groupBy('docente_id')
            ->map(function($asignaciones) {
                $docente = $asignaciones->first()->docente;
                return [
                    'docente' => $docente->persona->nombre,
                    'codigo' => $docente->codigo,
                    'asignaturas' => $asignaciones->count(),
                    'materias' => $asignaciones->pluck('asignatura.descripcion')->unique()->implode(', ')
                ];
            });

        return view('application.horario.asignacion-automatica', compact(
            'semestre', 'dias', 'horas', 'totalDocentes', 'totalAsignaturas', 
            'totalAulas', 'horariosExistentes', 'asignacionesPendientes'
        ));
    }

    /**
     * Procesar asignación automática de horarios
     */
    public function procesarAsignacionAutomatica(Request $request)
    {
        $validated = $request->validate([
            'semestre_id' => 'required|exists:semestres,id',
            'dias_seleccionados' => 'required|array|min:1',
            'dias_seleccionados.*' => 'exists:dias,id',
            'hora_inicio' => 'required|exists:horas,id',
            'hora_fin' => 'required|exists:horas,id',
            'limpiar_existentes' => 'boolean',
            'prioridad_docentes' => 'boolean',
            'distribuir_aulas' => 'boolean'
        ]);

        $semestre = Semestre::find($validated['semestre_id']);
        $diasSeleccionados = $validated['dias_seleccionados'];
        
        // Obtener rango de horas
        $horaInicio = Hora::find($validated['hora_inicio']);
        $horaFin = Hora::find($validated['hora_fin']);
        
        $horasDisponibles = Hora::where('hora_inicio', '>=', $horaInicio->hora_inicio)
            ->where('hora_fin', '<=', $horaFin->hora_fin)
            ->where('estado', true)
            ->orderBy('hora_inicio')
            ->get();

        if ($horasDisponibles->isEmpty()) {
            return back()->withErrors(['error' => 'No hay horas disponibles en el rango seleccionado.']);
        }

        // Limpiar horarios existentes si se solicita
        if ($validated['limpiar_existentes']) {
            Horario::where('semestre_id', $semestre->id)->delete();
        }

        // Obtener asignaciones pendientes
        $asignaciones = GrupoAsignatura::where('semestre_id', $semestre->id)
            ->with(['docente.persona', 'asignatura', 'grupo'])
            ->get();

        if ($asignaciones->isEmpty()) {
            return back()->withErrors(['error' => 'No hay asignaciones de docentes-asignaturas para procesar.']);
        }

        // Obtener recursos disponibles
        $aulas = Aula::where('estado', true)->get();
        $dias = Dia::whereIn('id', $diasSeleccionados)->where('estado', true)->get();

        // Inicializar contadores y resultados
        $horariosCreados = 0;
        $conflictos = [];
        $asignacionesExitosas = [];
        $asignacionesFallidas = [];

        // Algoritmo de asignación automática
        foreach ($asignaciones as $asignacion) {
            $horarioAsignado = false;
            
            // Intentar asignar en cada día seleccionado
            foreach ($dias as $dia) {
                if ($horarioAsignado) break;
                
                // Intentar asignar en cada hora disponible
                foreach ($horasDisponibles as $hora) {
                    if ($horarioAsignado) break;
                    
                    // Intentar asignar en cada aula disponible
                    foreach ($aulas as $aula) {
                        // Verificar conflictos
                        $conflictoAula = Horario::conflictoAula(
                            $aula->id,
                            $dia->id,
                            $semestre->id,
                            $hora->id
                        );
                        
                        $conflictoDocente = Horario::conflictoDocente(
                            $asignacion->docente_id,
                            $dia->id,
                            $semestre->id,
                            $hora->id
                        );

                        if (!$conflictoAula && !$conflictoDocente) {
                            // Crear horario
                            $horario = Horario::create([
                                'hora_id' => $hora->id,
                                'dia_id' => $dia->id,
                                'aula_id' => $aula->id,
                                'asignatura_id' => $asignacion->asignatura_id,
                                'semestre_id' => $semestre->id,
                                'observacion' => 'Asignado automáticamente'
                            ]);

                            // Asignar docente
                            $horario->docentes()->attach($asignacion->docente_id);

                            $horariosCreados++;
                            $horarioAsignado = true;
                            
                            $asignacionesExitosas[] = [
                                'docente' => $asignacion->docente->persona->nombre,
                                'asignatura' => $asignacion->asignatura->descripcion,
                                'grupo' => $asignacion->grupo->nombre,
                                'dia' => $dia->descripcion,
                                'hora' => $hora->hora_inicio->format('H:i') . '-' . $hora->hora_fin->format('H:i'),
                                'aula' => $aula->numero_aula
                            ];
                            
                            break;
                        }
                    }
                }
            }
            
            // Si no se pudo asignar
            if (!$horarioAsignado) {
                $asignacionesFallidas[] = [
                    'docente' => $asignacion->docente->persona->nombre,
                    'asignatura' => $asignacion->asignatura->descripcion,
                    'grupo' => $asignacion->grupo->nombre,
                    'motivo' => 'No hay espacios disponibles sin conflictos'
                ];
            }
        }

        // Registrar en bitácora
        registrar_bitacora(
            "Asignación automática de horarios: {$horariosCreados} horarios creados, " . 
            count($asignacionesFallidas) . " asignaciones fallidas"
        );

        // Preparar mensaje de resultado
        $mensaje = "✅ Asignación automática completada:\n";
        $mensaje .= "• {$horariosCreados} horarios creados exitosamente\n";
        
        if (count($asignacionesFallidas) > 0) {
            $mensaje .= "• " . count($asignacionesFallidas) . " asignaciones no pudieron completarse";
        }

        return back()->with([
            'success' => $mensaje,
            'asignaciones_exitosas' => $asignacionesExitosas,
            'asignaciones_fallidas' => $asignacionesFallidas,
            'total_creados' => $horariosCreados
        ]);
    }

    /**
     * Mostrar simulador de horarios
     */
    public function simulador()
    {
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();
        
        // Obtener horarios actuales
        $horariosActuales = Horario::where('semestre_id', $semestre->id)
            ->where('estado', true)
            ->with(['asignatura', 'aula', 'dia', 'hora', 'docentes.persona'])
            ->orderBy('dia_id')
            ->orderBy('hora_id')
            ->get()
            ->map(function($horario) {
                $docente = $horario->docentes->first();
                return [
                    'id' => $horario->id,
                    'asignatura' => $horario->asignatura->descripcion,
                    'aula' => $horario->aula->numero_aula,
                    'aula_id' => $horario->aula->id,
                    'dia' => $horario->dia->descripcion,
                    'dia_id' => $horario->dia->id,
                    'hora_inicio' => $horario->hora->hora_inicio->format('H:i'),
                    'hora_fin' => $horario->hora->hora_fin->format('H:i'),
                    'hora_id' => $horario->hora->id,
                    'docente' => $docente ? $docente->persona->nombre : 'Sin docente',
                    'docente_id' => $docente ? $docente->id : null,
                    'asignatura_id' => $horario->asignatura->id
                ];
            });

        // Obtener recursos para simulación
        $docentes = Docente::with('persona')->where('estado', true)->get();
        $aulas = Aula::where('estado', true)->get();
        $dias = Dia::where('estado', true)->get();
        $horas = Hora::where('estado', true)->orderBy('hora_inicio')->get();
        $asignaturas = Asignatura::where('estado', true)->get();

        // Estadísticas
        $totalHorarios = $horariosActuales->count();
        $totalDocentes = $docentes->count();
        $totalAulas = $aulas->count();

        return view('application.horario.simulador', compact(
            'semestre', 'horariosActuales', 'docentes', 'aulas', 'dias', 'horas', 
            'asignaturas', 'totalHorarios', 'totalDocentes', 'totalAulas'
        ));
    }

    /**
     * Procesar simulación de cambios
     */
    public function procesarSimulacion(Request $request)
    {
        $validated = $request->validate([
            'cambios' => 'required|array',
            'cambios.*.horario_id' => 'required|exists:horarios,id',
            'cambios.*.tipo' => 'required|in:aula,docente,dia,hora',
            'cambios.*.nuevo_valor' => 'required'
        ]);

        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();
        $conflictos = [];
        $cambiosValidos = [];
        $cambiosProcesados = 0;

        foreach ($validated['cambios'] as $cambio) {
            $horario = Horario::find($cambio['horario_id']);
            $tieneConflicto = false;
            $detalleConflicto = '';

            // Simular el cambio según el tipo
            switch ($cambio['tipo']) {
                case 'aula':
                    // Verificar conflicto de aula
                    $conflictoAula = Horario::where('aula_id', $cambio['nuevo_valor'])
                        ->where('dia_id', $horario->dia_id)
                        ->where('semestre_id', $semestre->id)
                        ->where('id', '!=', $horario->id)
                        ->whereHas('hora', function ($query) use ($horario) {
                            $query->where('hora_inicio', '<', $horario->hora->hora_fin)
                                ->where('hora_fin', '>', $horario->hora->hora_inicio);
                        })
                        ->with(['asignatura', 'hora'])
                        ->first();

                    if ($conflictoAula) {
                        $tieneConflicto = true;
                        $aula = Aula::find($cambio['nuevo_valor']);
                        $detalleConflicto = "Aula {$aula->numero_aula} ocupada con {$conflictoAula->asignatura->descripcion} de {$conflictoAula->hora->hora_inicio->format('H:i')} a {$conflictoAula->hora->hora_fin->format('H:i')}";
                    }
                    break;

                case 'docente':
                    // Verificar conflicto de docente
                    $conflictoDocente = Horario::whereHas('docentes', function($q) use ($cambio) {
                            $q->where('docente_id', $cambio['nuevo_valor']);
                        })
                        ->where('dia_id', $horario->dia_id)
                        ->where('semestre_id', $semestre->id)
                        ->where('id', '!=', $horario->id)
                        ->whereHas('hora', function ($query) use ($horario) {
                            $query->where('hora_inicio', '<', $horario->hora->hora_fin)
                                ->where('hora_fin', '>', $horario->hora->hora_inicio);
                        })
                        ->with(['asignatura', 'aula', 'hora'])
                        ->first();

                    if ($conflictoDocente) {
                        $tieneConflicto = true;
                        $docente = Docente::with('persona')->find($cambio['nuevo_valor']);
                        $detalleConflicto = "Docente {$docente->persona->nombre} ocupado con {$conflictoDocente->asignatura->descripcion} en aula {$conflictoDocente->aula->numero_aula} de {$conflictoDocente->hora->hora_inicio->format('H:i')} a {$conflictoDocente->hora->hora_fin->format('H:i')}";
                    }
                    break;

                case 'dia':
                    // Verificar conflictos al cambiar día
                    $docenteActual = $horario->docentes->first();
                    if ($docenteActual) {
                        $conflictoDocente = Horario::whereHas('docentes', function($q) use ($docenteActual) {
                                $q->where('docente_id', $docenteActual->id);
                            })
                            ->where('dia_id', $cambio['nuevo_valor'])
                            ->where('semestre_id', $semestre->id)
                            ->where('id', '!=', $horario->id)
                            ->whereHas('hora', function ($query) use ($horario) {
                                $query->where('hora_inicio', '<', $horario->hora->hora_fin)
                                    ->where('hora_fin', '>', $horario->hora->hora_inicio);
                            })
                            ->with(['asignatura', 'aula', 'hora'])
                            ->first();

                        if ($conflictoDocente) {
                            $tieneConflicto = true;
                            $dia = Dia::find($cambio['nuevo_valor']);
                            $detalleConflicto = "Docente {$docenteActual->persona->nombre} ocupado el {$dia->descripcion} con {$conflictoDocente->asignatura->descripcion} en aula {$conflictoDocente->aula->numero_aula}";
                        }
                    }

                    // Verificar conflicto de aula en el nuevo día
                    $conflictoAula = Horario::where('aula_id', $horario->aula_id)
                        ->where('dia_id', $cambio['nuevo_valor'])
                        ->where('semestre_id', $semestre->id)
                        ->where('id', '!=', $horario->id)
                        ->whereHas('hora', function ($query) use ($horario) {
                            $query->where('hora_inicio', '<', $horario->hora->hora_fin)
                                ->where('hora_fin', '>', $horario->hora->hora_inicio);
                        })
                        ->with(['asignatura', 'hora'])
                        ->first();

                    if ($conflictoAula && !$tieneConflicto) {
                        $tieneConflicto = true;
                        $dia = Dia::find($cambio['nuevo_valor']);
                        $detalleConflicto = "Aula {$horario->aula->numero_aula} ocupada el {$dia->descripcion} con {$conflictoAula->asignatura->descripcion}";
                    }
                    break;

                case 'hora':
                    // Verificar conflictos al cambiar hora
                    $nuevaHora = Hora::find($cambio['nuevo_valor']);
                    $docenteActual = $horario->docentes->first();
                    
                    if ($docenteActual) {
                        $conflictoDocente = Horario::whereHas('docentes', function($q) use ($docenteActual) {
                                $q->where('docente_id', $docenteActual->id);
                            })
                            ->where('dia_id', $horario->dia_id)
                            ->where('semestre_id', $semestre->id)
                            ->where('id', '!=', $horario->id)
                            ->whereHas('hora', function ($query) use ($nuevaHora) {
                                $query->where('hora_inicio', '<', $nuevaHora->hora_fin)
                                    ->where('hora_fin', '>', $nuevaHora->hora_inicio);
                            })
                            ->with(['asignatura', 'aula', 'hora'])
                            ->first();

                        if ($conflictoDocente) {
                            $tieneConflicto = true;
                            $detalleConflicto = "Docente {$docenteActual->persona->nombre} ocupado de {$nuevaHora->hora_inicio->format('H:i')} a {$nuevaHora->hora_fin->format('H:i')} con {$conflictoDocente->asignatura->descripcion}";
                        }
                    }

                    // Verificar conflicto de aula en la nueva hora
                    $conflictoAula = Horario::where('aula_id', $horario->aula_id)
                        ->where('dia_id', $horario->dia_id)
                        ->where('semestre_id', $semestre->id)
                        ->where('id', '!=', $horario->id)
                        ->whereHas('hora', function ($query) use ($nuevaHora) {
                            $query->where('hora_inicio', '<', $nuevaHora->hora_fin)
                                ->where('hora_fin', '>', $nuevaHora->hora_inicio);
                        })
                        ->with(['asignatura', 'hora'])
                        ->first();

                    if ($conflictoAula && !$tieneConflicto) {
                        $tieneConflicto = true;
                        $detalleConflicto = "Aula {$horario->aula->numero_aula} ocupada de {$nuevaHora->hora_inicio->format('H:i')} a {$nuevaHora->hora_fin->format('H:i')} con {$conflictoAula->asignatura->descripcion}";
                    }
                    break;
            }

            if ($tieneConflicto) {
                $conflictos[] = [
                    'horario_id' => $horario->id,
                    'asignatura' => $horario->asignatura->descripcion,
                    'tipo_cambio' => $cambio['tipo'],
                    'detalle' => $detalleConflicto
                ];
            } else {
                $cambiosValidos[] = $cambio;
                $cambiosProcesados++;
            }
        }

        // Registrar simulación en bitácora
        registrar_bitacora(
            "Simulación de horarios procesada: {$cambiosProcesados} cambios válidos, " . 
            count($conflictos) . " conflictos detectados"
        );

        return response()->json([
            'success' => true,
            'cambios_validos' => $cambiosValidos,
            'conflictos' => $conflictos,
            'total_cambios' => count($validated['cambios']),
            'cambios_procesados' => $cambiosProcesados,
            'total_conflictos' => count($conflictos)
        ]);
    }

    /**
     * Aplicar cambios de simulación
     */
    public function aplicarSimulacion(Request $request)
    {
        $validated = $request->validate([
            'cambios' => 'required|array',
            'cambios.*.horario_id' => 'required|exists:horarios,id',
            'cambios.*.tipo' => 'required|in:aula,docente,dia,hora',
            'cambios.*.nuevo_valor' => 'required'
        ]);

        $cambiosAplicados = 0;
        $errores = [];

        foreach ($validated['cambios'] as $cambio) {
            try {
                $horario = Horario::find($cambio['horario_id']);

                switch ($cambio['tipo']) {
                    case 'aula':
                        $horario->aula_id = $cambio['nuevo_valor'];
                        break;
                    case 'dia':
                        $horario->dia_id = $cambio['nuevo_valor'];
                        break;
                    case 'hora':
                        $horario->hora_id = $cambio['nuevo_valor'];
                        break;
                    case 'docente':
                        // Desasignar docente actual
                        $horario->docentes()->detach();
                        // Asignar nuevo docente
                        $horario->docentes()->attach($cambio['nuevo_valor']);
                        break;
                }

                if ($cambio['tipo'] !== 'docente') {
                    $horario->save();
                }

                $cambiosAplicados++;
            } catch (\Exception $e) {
                $errores[] = "Error al aplicar cambio en horario {$horario->asignatura->descripcion}: " . $e->getMessage();
            }
        }

        // Registrar en bitácora
        registrar_bitacora(
            "Simulación aplicada: {$cambiosAplicados} cambios aplicados exitosamente"
        );

        if (count($errores) > 0) {
            return back()->withErrors($errores)->with('warning', "Se aplicaron {$cambiosAplicados} cambios, pero hubo algunos errores.");
        }

        return back()->with('success', "✅ Simulación aplicada exitosamente: {$cambiosAplicados} cambios realizados.");
    }
}


        /* // Validar conflicto en aula
        if (Horario::conflictoAula(
            $validated['aula_id'],
            $validated['dia_id'],
            $validated['semestre_id'],
            $validated['hora_id']
        )) {
            return back()->withErrors(
                ['conflicto' => 'El aula ya está ocupada en este horario.']
            );
        }

        if ($validated['docente_id'] && Horario::conflictoDocente(
            $validated['docente_id'],
            $validated['dia_id'],
            $validated['semestre_id'],
            $validated['hora_id']
        )) {
            return back()->withErrors([
                'conflicto' => 'El docente ya tiene una clase en este horario.'
            ]);
        }

        // Crear el horario
        $horario = Horario::create([
            'hora_id' => $validated['hora_id'],
            'dia_id' => $validated['dia_id'],
            'aula_id' => $validated['aula_id'],
            'asignatura_id' => $validated['asignatura_id'],
            'semestre_id' => $validated['semestre_id'],
            'observacion' => $validated['observacion'] ?? null,
        ]);

        // Asignar docente si se envió
        if ($validated['docente_id']) {
            $horario->docentes()->attach($validated['docente_id']);
        } */
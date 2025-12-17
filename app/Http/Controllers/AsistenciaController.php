<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docente = Auth::user()->persona->docente;
        $hoy = now()->format('Y-m-d');
        $ahora = now();
        
        // Mapear días de la semana (PHP usa inglés, necesitamos español sin acentos)
        $diasSemana = [
            'Sunday' => 'domingo',
            'Monday' => 'lunes', 
            'Tuesday' => 'martes',
            'Wednesday' => 'miercoles',
            'Thursday' => 'jueves',
            'Friday' => 'viernes',
            'Saturday' => 'sabado'
        ];
        
        $diaSemana = $diasSemana[$ahora->format('l')];
        
        // Obtener las clases del día actual
        $clasesHoy = $docente->horarios()
            ->with(['asignatura', 'aula', 'hora', 'dia'])
            ->whereHas('dia', function($q) use ($diaSemana) {
                $q->where('descripcion', $diaSemana);
            })
            ->get()
            ->map(function($horario) use ($hoy, $ahora) {
                // Verificar si ya registró asistencia hoy
                $asistenciaHoy = Asistencia::where('horario_id', $horario->id)
                    ->whereDate('fecha_hora', $hoy)
                    ->first();
                
                // Verificar si la clase está en curso (con ventana de 30 minutos)
                $horaInicio = \Carbon\Carbon::parse($horario->hora->hora_inicio)->subMinutes(30);
                $horaFin = \Carbon\Carbon::parse($horario->hora->hora_fin)->addMinutes(30);
                $enCurso = $ahora->between($horaInicio, $horaFin);
                
                // Verificar si puede marcar asistencia (dentro de la ventana)
                $puedeMarcar = $enCurso && !$asistenciaHoy;
                
                return [
                    'id' => $horario->id,
                    'asignatura' => $horario->asignatura->descripcion,
                    'aula' => $horario->aula->numero_aula,
                    'hora_inicio' => $horario->hora->hora_inicio->format('H:i'),
                    'hora_fin' => $horario->hora->hora_fin->format('H:i'),
                    'asistencia_registrada' => $asistenciaHoy ? true : false,
                    'estado_asistencia' => $asistenciaHoy ? $asistenciaHoy->estado : null,
                    'observacion' => $asistenciaHoy ? $asistenciaHoy->observacion : null,
                    'fecha_registro' => $asistenciaHoy ? $asistenciaHoy->fecha_hora : null,
                    'en_curso' => $enCurso,
                    'puede_marcar' => $puedeMarcar,
                    'ventana_inicio' => $horaInicio->format('H:i'),
                    'ventana_fin' => $horaFin->format('H:i'),
                ];
            });

        return view('application.asistencia.index', compact('clasesHoy', 'hoy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $docente = Auth::user()->persona->docente;
        
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'estado'     => 'required|string|in:presente,ausente,justificado',
            'observacion' => 'required_if:estado,justificado|nullable|string|min:10|max:500'
        ]);

        // Verificar que el horario pertenece al docente
        $horario = $docente->horarios()->find($request->horario_id);
        
        if (!$horario) {
            return back()->withErrors(['error' => 'Este horario no te pertenece.']);
        }

        // Verificar si ya registró asistencia hoy
        $hoy = now()->format('Y-m-d');
        $asistenciaExistente = Asistencia::where('horario_id', $request->horario_id)
            ->whereDate('fecha_hora', $hoy)
            ->first();

        if ($asistenciaExistente) {
            return back()->withErrors(['error' => 'Ya has registrado asistencia para esta clase hoy.']);
        }

        // Validar que está dentro del horario (con margen de 30 minutos antes y después)
        $horaActual = now();
        $horaInicio = \Carbon\Carbon::parse($horario->hora->hora_inicio)->subMinutes(30);
        $horaFin = \Carbon\Carbon::parse($horario->hora->hora_fin)->addMinutes(30);

        if ($horaActual->lt($horaInicio) || $horaActual->gt($horaFin)) {
            return back()->withErrors([
                'error' => 'Solo puedes registrar asistencia dentro del horario de clase (30 minutos antes o después).'
            ]);
        }

        // Registrar asistencia
        Asistencia::create([
            'horario_id' => $request->horario_id,
            'estado'     => $request->estado,
            'fecha_hora' => now()->toDateTimeString(),
            'observacion' => $request->observacion
        ]);

        registrar_bitacora(
            "El docente {$docente->codigo} registró asistencia como '{$request->estado}' para la clase de {$horario->asignatura->descripcion}"
        );

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asistencia $asistencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}

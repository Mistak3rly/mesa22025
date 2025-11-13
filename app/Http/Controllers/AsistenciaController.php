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
        //
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
            'estado'     => 'required|string|in:presente,ausente,justificado'
        ]);

        Asistencia::create([
            'horario_id' => $request->horario_id,
            'estado'     => $request->estado,
            'fecha_hora' => now()->toDateTimeString()
        ]);

        registrar_bitacora(
            "El docente con codigo : {$docente->codigo} ha iniciado marcado asistencia"
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

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\GrupoAsignatura;
use App\Models\Asignatura;
use App\Models\Asistencia;
use App\Models\Semestre;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Grupo;
use App\Models\User;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //retorna todos los docentes que estan visibles
        $docentes = Docente::with(['persona:id,carnet,nombre,telefono']) // solo esos campos de persona
            ->where('estado', true)
            ->select('id', 'codigo', 'persona_id') // campos de docente
            ->get();

        $asignaturas = Asignatura::where('estado', true)->get();
        $semestre = Semestre::orderBy('id', 'desc')->first();
        $grupos = Grupo::where('estado', true)->get();
        
        return view(
            'application.docente.index', 
            compact('docentes', 'asignaturas', 'semestre', 'grupos')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('application.docente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'carnet'=> 'required|string|max:15|unique:personas,carnet',
            'nombre' => 'required|string|max:50',
            'sexo' => 'required|string|size:1|in:M,F',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'codigo' => 'required|string|max:10|unique:docentes,codigo',
            'correo' => 'required|string|email|max:50|unique:docentes,correo',
            'carga_horaria' => 'required|integer|min:1',
        ]);

        // Crear la persona asociada al docente
        $persona = Persona::create([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_ingreso' => $request->fecha_ingreso
        ]);

        // Crear al docente
        $docente_temp = Docente::create([
            'persona_id' => $persona->id,
            'codigo' => $request->codigo,
            'correo' => $request->correo,
            'carga_horaria' => $request->carga_horaria
        ]);

        $partes = explode(' ', trim($request->nombre));
        $nombre_usuario = $partes[0];
        
        // Crear al usuario relacionado al docente
        $user = User::create([
            'persona_id' => $persona->id,
            'name' => $nombre_usuario,
            'email' => $request->correo,
            'password' => bcrypt($request->carnet)
        ]);

        // obtener el docente creado con la relacion de persona
        $docente = Docente::where('id', $docente_temp->id)
            ->where('estado', true)
            ->with('persona')
            ->first();

        // uso de la bitacora
        registrar_bitacora(
            "Se creó un nuevo docente: {$request->codigo}"
        );

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $docente = Docente::where('id', $id)
            ->where('estado', true)
            ->with('persona')
            ->first();

        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $docente = Docente::where('estado', true)
            ->with('persona')
            ->find($id);

        return view('application.docente.edit', compact('docente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Obtener el docente de forma temporal
        $docente = Docente::with('persona')->findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'carnet' => 'required|unique:personas,carnet,' . $docente->persona->id,
            'nombre' => 'required|string|max:50',
            'sexo' => 'required|string|size:1|in:M,F',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:50',
            'fecha_ingreso' => 'required|date',
            'codigo' => 'required|string|max:10|unique:docentes,codigo,' . $docente->id,
            'correo' => 'required|string|email|max:50|unique:docentes,correo,' . $docente->id,
            'carga_horaria' => 'required|integer|min:1'
        ]);

        // Actualizar los datos de la Persona asociada al docente
        $docente->persona->update([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_ingreso' => $request->fecha_ingreso
        ]);

        // Actualizar los datos del docente
        $docente->update([
            'codigo' => $request->codigo,
            'correo' => $request->correo,
            'carga_horaria' => $request->carga_horaria
        ]);

        // uso de la bitacora
        registrar_bitacora(
            "Se actualizó al docente: {$request->codigo}"
        );

        return view('application.docente.show', compact('docente'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $docente = Docente::where('estado', true)->find($id);

        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        $docente->estado = false;
        
        $docente->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se elimino al docente: {$docente->codigo}"
        );

        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado correctamente.');
    }

    public function reactivate($id)
    {
        $docente = Docente::where('id', $id)
            ->where('estado', false)
            ->first();


        if (!$docente) {
            return redirect()->route('docentes.index')
                ->with('error', 'Docente no encontrado.');
        }

        $docente->estado = true;
        
        $docente->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se reactivó al docente con codigo: {$docente->codigo}"
        );

        return redirect()->route('docentes.show', $docente->id)
            ->with('success', 'Docente eliminado correctamente.');
    }

    public function deletedIndex()
    {
        // Obtenemos todos los docentes retirados (estado = false)
        $docentes = Docente::where('estado', false)
            ->with(['persona:id,carnet,nombre,telefono'])
            ->get(['id', 'codigo', 'persona_id']);

        // Opcional: filtrar por si hay docentes sin persona asociada (evita errores en la vista)
        $docentes = $docentes->filter(fn($docente) => $docente->persona !== null);

        return view(
            'application.docente.deletedIndex', 
            compact('docentes')
        );
    }

    public function assign(Request $request)
    {
        $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'observacion' => 'nullable|string|max:255'
        ]);
        
        // Obtener el semestre activo
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();
        $semestre_id = $semestre?->id;

        if (!$semestre_id) {
            return back()->withErrors(['semestre' => 'No se encontró un semestre activo.']);
        }

        // Verificar si el docente ya tiene esa asignatura en ese semestre
        $existe = GrupoAsignatura::where('docente_id', $request->docente_id)
            ->where('asignatura_id', $request->asignatura_id)
            ->where('grupo_id', $request->grupo_id)
            ->where('semestre_id', $semestre_id)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'mensaje' => 'El docente ya tiene asignada esta asignatura en el semestre seleccionado.'
            ]);
        }

        // Crear la asignacion de asignatura al docente
        GrupoAsignatura::create([
            'docente_id' => $request->docente_id,
            'asignatura_id' => $request->asignatura_id,
            'grupo_id' => $request->grupo_id,
            'semestre_id' => $semestre_id,
            'observacion' => $request->observacion
        ]);

        // uso de la bitacora
        registrar_bitacora(
            "El docente con ID : {$request->docente_id} fue asignado a la 
            asignatura con ID : {$request->asignatura_id}"
        );

        return redirect()->route('docentes.index')
            ->with('mensaje', 'Asignatura asignada correctamente al docente.');

    }

    public function assigned($id)
    {
        $docente = Docente::where('id', $id)
            ->where('estado', true)
            ->firstOrFail();
        
        // Obtener el semestre actual (último con estado = true)
        $semestre = Semestre::where('estado', true)->orderBy('id', 'desc')->first();

        if (!$semestre) {
            return redirect()->route('docentes.index')
                ->withErrors(['semestre' => 'No hay semestre activo.']
            );
        }

        // Filtrar asignaciones del docente solo del semestre actual
        $asignaciones = GrupoAsignatura::where('docente_id', $docente->id)
            ->where('semestre_id', $semestre->id)
            ->with(['grupo', 'semestre', 'asignatura'])
            ->get();
        
        $docente = $docente->persona->nombre;

        return view(
            'application.docente.asignados', 
            compact('docente', 'asignaciones')
        );
    }

    public function dashboard()
    {
        // Redirigir directamente a la vista de horario del docente
        return redirect()->route('docentes.mi-horario');
    }

    public function asistencia(Request $request)
    {
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'estado'     => 'required|string|in:presente,ausente,justificado'
        ]);

        Asistencia::create([
            'horario_id' => $request->horario_id,
            'estado'     => $request->estado,
            'fecha_hora' => now()->toDateTimeString()
        ]);

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    public function miHorario()
    {
        $docente = Auth::user()->persona->docente;
        $semestre = Semestre::where('estado', true)->firstOrFail();

        $horarios = $docente->horarios()
            ->with(['asignatura', 'aula', 'dia', 'hora'])
            ->where('semestre_id', $semestre->id)
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
        
        return view('application.docente.panel', compact('horarios'));
    }

}
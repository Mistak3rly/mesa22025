<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Persona;
use App\Models\Administrativo;

class AdministrativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //retorna todos los docentes que estan visibles
        $administrativos = Administrativo::with(['persona:id,carnet,nombre,telefono']) // solo esos campos de persona
            ->where('estado', true)
            ->select('id', 'codigo', 'persona_id')
            ->get();

        return view(
            'application.administrativo.index', compact('administrativos')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('application.administrativo.create');
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
            'correo' => 'required|string|email|max:50|unique:docentes,correo'
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

        // Crear al administrativo
        $administrativo_temp = Administrativo::create([
            'persona_id' => $persona->id,
            'codigo' => $request->codigo,
            'correo' => $request->correo,
        ]);

        $partes = explode(' ', trim($request->nombre));
        $nombre_usuario = $partes[0];
        
        // Crear al usuario relacionado al administrativo
        $user = User::create([
            'persona_id' => $persona->id,
            'name' => $nombre_usuario,
            'email' => $request->correo,
            'password' => bcrypt($request->carnet)
        ]);

        // obtener el docente creado con la relacion de persona
        $administrativo = Administrativo::where('estado', true)
            ->with('persona')
            ->find($administrativo_temp->id);

        // uso de la bitacora
        registrar_bitacora(
            "Se creó un nuevo administrativo : {$administrativo->codigo}"
        );

        return view(
            'application.administrativo.show', compact('administrativo')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $administrativo = Administrativo::where('estado', true)
            ->with('persona')
            ->find($id);

        if (!$administrativo) {
            return redirect()->route('administrativos.index')
                ->with('error', 'Administrativo no encontrado.');
        }

        return view(
            'application.administrativo.show', compact('administrativo')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $administrativo = Administrativo::where('estado', true)
            ->with('persona')
            ->find($id);

        return view(
            'application.administrativo.edit', compact('administrativo')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Obtener el administrativo de forma temporal
        $administrativo = Administrativo::with('persona')->findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'carnet' => 'required|unique:personas,carnet,' . $administrativo->persona->id,
            'nombre' => 'required|string|max:50',
            'sexo' => 'required|string|size:1|in:M,F',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:50',
            'fecha_ingreso' => 'required|date',
            'codigo' => 'required|string|max:10|unique:administrativos,codigo,' . $administrativo->id,
            'correo' => 'required|string|email|max:50|unique:administrativos,correo,' . $administrativo->id,
        ]);

        // Actualizar los datos de la Persona asociada al administrativo
        $administrativo->persona->update([
            'carnet' => $request->carnet,
            'nombre' => $request->nombre,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_ingreso' => $request->fecha_ingreso
        ]);

        // Actualizar los datos del administrativo
        $administrativo->update([
            'codigo' => $request->codigo,
            'correo' => $request->correo,
        ]);

        // uso de la bitacora
        registrar_bitacora(
            "Se actualizo el administrador con codigo : {$administrativo->codigo}"
        );

        return view(
            'application.administrativo.show', compact('administrativo')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $administrativo = Administrativo::where('estado', true)
            ->find($id);

        if (!$administrativo) {
            return redirect()->route('administrativos.index')
                ->with('error', 'Administrativo no encontrado.');
        }

        $administrativo->estado = false;
        
        $administrativo->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se actualizó al administrativo : {$administrativo->codigo}"
        );

        return redirect()->route('administrativos.index')
            ->with('success', 'Administrativo eliminado correctamente.');
    }

    public function reactivate($id)
    {
        $administrativo = Administrativo::where('id', $id)
            ->where('estado', false)
            ->first();

        if (!$administrativo) {
            return redirect()->route('administrativos.index')
                ->with('error', 'Administrativo no encontrado.');
        }

        $administrativo->estado = true;
        
        $administrativo->save();

        // uso de la bitacora
        registrar_bitacora(
            "Se reingreso al administrativo : {$administrativo->codigo}"
        );

        return redirect()->route('administrativos.show', $administrativo->id)
            ->with('success', 'Administrativo eliminado correctamente.');
    }

    public function deletedIndex()
    {
        // Obtenemos todos los docentes retirados (estado = false)
        $administrativos = Administrativo::where('estado', false)
            ->with(['persona:id,carnet,nombre,telefono'])
            ->get(['id', 'codigo', 'persona_id']);

        // Opcional: filtrar por si hay docentes sin persona asociada (evita errores en la vista)
        $administrativos = $administrativos->filter(fn($administrativos) => $administrativos->persona !== null);

        return view(
            'application.administrativo.deletedIndex', compact('administrativos')
        );
    }

}

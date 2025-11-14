<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\Persona;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;

class DocenteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rol de docente
        $rolDocente = Rol::firstOrCreate([
            'nombre' => 'Docente'
        ], [
            'descripcion' => 'Usuario docente con acceso a gestión de clases y asistencias'
        ]);

        // Docente 1: Juan Pérez
        $persona1 = Persona::firstOrCreate([
            'carnet' => 'DOC001'
        ], [
            'nombre' => 'Juan Carlos Pérez García',
            'sexo' => 'M',
            'telefono' => '71234567',
            'direccion' => 'Av. América #123',
            'fecha_ingreso' => now()->subYears(5)->format('Y-m-d')
        ]);

        $docente1 = Docente::firstOrCreate([
            'codigo' => 'DOC001'
        ], [
            'persona_id' => $persona1->id,
            'correo' => 'juan.perez@facultad.edu',
            'carga_horaria' => 40,
            'estado' => true
        ]);

        User::firstOrCreate([
            'email' => 'juan.perez@facultad.edu'
        ], [
            'persona_id' => $persona1->id,
            'rol_id' => $rolDocente->id,
            'name' => 'Juan Pérez',
            'password' => Hash::make('docente123'),
        ]);

        // Docente 2: María López
        $persona2 = Persona::firstOrCreate([
            'carnet' => 'DOC002'
        ], [
            'nombre' => 'María Elena López Rodríguez',
            'sexo' => 'F',
            'telefono' => '72345678',
            'direccion' => 'Calle Libertad #456',
            'fecha_ingreso' => now()->subYears(3)->format('Y-m-d')
        ]);

        $docente2 = Docente::firstOrCreate([
            'codigo' => 'DOC002'
        ], [
            'persona_id' => $persona2->id,
            'correo' => 'maria.lopez@facultad.edu',
            'carga_horaria' => 40,
            'estado' => true
        ]);

        User::firstOrCreate([
            'email' => 'maria.lopez@facultad.edu'
        ], [
            'persona_id' => $persona2->id,
            'rol_id' => $rolDocente->id,
            'name' => 'María López',
            'password' => Hash::make('docente123'),
        ]);

        $this->command->info('Usuarios docentes creados exitosamente:');
        $this->command->info('');
        $this->command->info('=== DOCENTE 1 ===');
        $this->command->info('Email: juan.perez@facultad.edu');
        $this->command->info('Contraseña: docente123');
        $this->command->info('Código: DOC001');
        $this->command->info('');
        $this->command->info('=== DOCENTE 2 ===');
        $this->command->info('Email: maria.lopez@facultad.edu');
        $this->command->info('Contraseña: docente123');
        $this->command->info('Código: DOC002');
    }
}

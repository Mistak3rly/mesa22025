<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Asignatura;
use App\Models\Grupo;
use App\Models\GrupoAsignatura;
use App\Models\Semestre;
use App\Models\User;
use App\Models\Rol;

class DatosCompletosSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear roles
        $rolAdmin = Rol::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Rol de administrador del sistema']
        );
        
        $rolDocente = Rol::firstOrCreate(
            ['nombre' => 'Docente'],
            ['descripcion' => 'Rol de docente']
        );

        // Crear usuario administrador
        $personaAdmin = Persona::firstOrCreate(
            ['carnet' => 'ADMIN001'],
            [
                'nombre' => 'Administrador del Sistema',
                'sexo' => 'M',
                'telefono' => '70000000',
                'direccion' => 'Oficina Administrativa',
                'fecha_ingreso' => '2020-01-01',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@universidad.edu'],
            [
                'persona_id' => $personaAdmin->id,
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
                'rol_id' => $rolAdmin->id,
            ]
        );

        // Obtener el semestre activo
        $semestre = Semestre::where('estado', true)->first();
        if (!$semestre) {
            $semestre = Semestre::create([
                'descripcion' => 'Semestre 2025-I',
                'fecha_inicio' => '2025-01-15',
                'fecha_fin' => '2025-06-30',
                'estado' => true
            ]);
        }

        // Datos de docentes únicos extraídos de la imagen
        $docentes = [
            [
                'nombre' => 'FLORES FLORES MARCOS OSCAR',
                'codigo' => 'DOC001',
                'correo' => 'mflores@universidad.edu',
                'carnet' => '10001',
            ],
            [
                'nombre' => 'CABELLO MERIDA JUAN RUBEN',
                'codigo' => 'DOC002',
                'correo' => 'jcabello@universidad.edu',
                'carnet' => '10002',
            ],
            [
                'nombre' => 'CORTEZ UZEDA JULIO MARTIN',
                'codigo' => 'DOC003',
                'correo' => 'jcortez@universidad.edu',
                'carnet' => '10003',
            ],
            [
                'nombre' => 'GUTIERREZ BRUNO KATIME ESTHER',
                'codigo' => 'DOC004',
                'correo' => 'egutierrez@universidad.edu',
                'carnet' => '10004',
            ],
            [
                'nombre' => 'GIANELLA PEREDO EDUARDO',
                'codigo' => 'DOC005',
                'correo' => 'egianella@universidad.edu',
                'carnet' => '10005',
            ],
            [
                'nombre' => 'MONRROY DIPP VICTOR FERNANDO',
                'codigo' => 'DOC006',
                'correo' => 'vmonrroy@universidad.edu',
                'carnet' => '10006',
            ],
            [
                'nombre' => 'CARVAJAL CORDERO MARCIO',
                'codigo' => 'DOC007',
                'correo' => 'mcarvajal@universidad.edu',
                'carnet' => '10007',
            ],
            [
                'nombre' => 'HINOJOSA SAAVEDRA JOSE SAID',
                'codigo' => 'DOC008',
                'correo' => 'jhinojosa@universidad.edu',
                'carnet' => '10008',
            ],
            [
                'nombre' => 'VEIZAGA GONZALES JOSUE OBED',
                'codigo' => 'DOC009',
                'correo' => 'jveizaga@universidad.edu',
                'carnet' => '10009',
            ],
            [
                'nombre' => 'VARGAS PEAA LEONARDO',
                'codigo' => 'DOC010',
                'correo' => 'lvargas@universidad.edu',
                'carnet' => '10010',
            ],
            [
                'nombre' => 'PEREZ FERREIRA UBALDO',
                'codigo' => 'DOC011',
                'correo' => 'uperez@universidad.edu',
                'carnet' => '10011',
            ],
            [
                'nombre' => 'AVENDAÑO GONZALES EUDAL',
                'codigo' => 'DOC012',
                'correo' => 'eavendano@universidad.edu',
                'carnet' => '10012',
            ],
            [
                'nombre' => 'CALIZAYA AJHUACHO MAGNO EDWIN',
                'codigo' => 'DOC013',
                'correo' => 'mcalizaya@universidad.edu',
                'carnet' => '10013',
            ],
            [
                'nombre' => 'TEJERINA GUERRA JULIO',
                'codigo' => 'DOC014',
                'correo' => 'jtejerina@universidad.edu',
                'carnet' => '10014',
            ],
            [
                'nombre' => 'JUSTINIANO ROCA RONALD',
                'codigo' => 'DOC015',
                'correo' => 'rjustiniano@universidad.edu',
                'carnet' => '10015',
            ],
        ];

        // Crear docentes
        $docentesCreados = [];
        foreach ($docentes as $docenteData) {
            // Verificar si ya existe
            $docenteExistente = Docente::where('codigo', $docenteData['codigo'])->first();
            if ($docenteExistente) {
                $docentesCreados[$docenteData['codigo']] = $docenteExistente;
                continue;
            }

            // Crear persona
            $persona = Persona::create([
                'carnet' => $docenteData['carnet'],
                'nombre' => $docenteData['nombre'],
                'sexo' => 'M',
                'telefono' => '7' . rand(1000000, 9999999),
                'direccion' => 'Dirección ' . $docenteData['nombre'],
                'fecha_ingreso' => '2020-01-15',
            ]);

            // Crear docente
            $docente = Docente::create([
                'persona_id' => $persona->id,
                'codigo' => $docenteData['codigo'],
                'correo' => $docenteData['correo'],
                'carga_horaria' => 40,
            ]);

            // Crear usuario
            $nombreUsuario = explode(' ', $docenteData['nombre'])[0];
            User::create([
                'persona_id' => $persona->id,
                'name' => $nombreUsuario,
                'email' => $docenteData['correo'],
                'password' => bcrypt($docenteData['carnet']),
                'rol_id' => $rolDocente->id,
            ]);

            $docentesCreados[$docenteData['codigo']] = $docente;
        }

        // Crear asignaturas
        $asignaturas = [
            ['sigla' => 'ADM100', 'nombre' => 'ADMINISTRACION'],
            ['sigla' => 'MAT103', 'nombre' => 'ALGEBRA LINEAL'],
            ['sigla' => 'RDS210', 'nombre' => 'ANALISIS DE CIRCUITOS'],
            ['sigla' => 'RDS220', 'nombre' => 'ANALISIS DE CIRCUITOS ELECTRON.'],
            ['sigla' => 'RDS410', 'nombre' => 'APLICACIONES CON MICROPROCESAD.'],
            ['sigla' => 'INF211', 'nombre' => 'ARQUITECTURA DE COMPUTADORAS'],
            ['sigla' => 'INF552', 'nombre' => 'ARQUITECTURA DEL SOFTWARE'],
            ['sigla' => 'INF462', 'nombre' => 'AUDITORIA INFORMATICA'],
            ['sigla' => 'INF312', 'nombre' => 'BASE DE DATOS I'],
            ['sigla' => 'INF322', 'nombre' => 'BASES DE DATOS II'],
            ['sigla' => 'MAT101', 'nombre' => 'CALCULO I'],
        ];

        $asignaturasCreadas = [];
        foreach ($asignaturas as $asigData) {
            $asignatura = Asignatura::firstOrCreate(
                ['sigla' => $asigData['sigla']],
                ['descripcion' => $asigData['nombre'], 'estado' => true]
            );
            $asignaturasCreadas[$asigData['sigla']] = $asignatura;
        }

        // Crear grupos
        $grupos = ['SA', 'SB', 'SC', 'SD', 'SE', 'SF', 'SG', 'SZ', 'F1', 'CI', 'I2'];
        $gruposCreados = [];
        foreach ($grupos as $grupoNombre) {
            $grupo = Grupo::firstOrCreate(
                ['descripcion' => $grupoNombre],
                ['estado' => true]
            );
            $gruposCreados[$grupoNombre] = $grupo;
        }

        // Asignaciones según la imagen
        $asignaciones = [
            // ADMINISTRACION
            ['asignatura' => 'ADM100', 'grupo' => 'SA', 'docente' => 'DOC001'], // FLORES FLORES
            ['asignatura' => 'ADM100', 'grupo' => 'SB', 'docente' => 'DOC001'], // FLORES FLORES
            ['asignatura' => 'ADM100', 'grupo' => 'SC', 'docente' => 'DOC002'], // CABELLO MERIDA
            
            // ALGEBRA LINEAL
            ['asignatura' => 'MAT103', 'grupo' => 'SA', 'docente' => 'DOC003'], // CORTEZ UZEDA
            ['asignatura' => 'MAT103', 'grupo' => 'SB', 'docente' => 'DOC003'], // CORTEZ UZEDA
            ['asignatura' => 'MAT103', 'grupo' => 'SC', 'docente' => 'DOC003'], // CORTEZ UZEDA
            ['asignatura' => 'MAT103', 'grupo' => 'SD', 'docente' => 'DOC003'], // CORTEZ UZEDA
            ['asignatura' => 'MAT103', 'grupo' => 'SE', 'docente' => 'DOC004'], // GUTIERREZ BRUNO
            ['asignatura' => 'MAT103', 'grupo' => 'SF', 'docente' => 'DOC005'], // GIANELLA PEREDO
            
            // ANALISIS DE CIRCUITOS
            ['asignatura' => 'RDS210', 'grupo' => 'SA', 'docente' => 'DOC006'], // MONRROY DIPP
            ['asignatura' => 'RDS210', 'grupo' => 'SB', 'docente' => 'DOC006'], // MONRROY DIPP
            ['asignatura' => 'RDS220', 'grupo' => 'SZ', 'docente' => 'DOC006'], // MONRROY DIPP
            
            // APLICACIONES CON MICROPROCESAD
            ['asignatura' => 'RDS410', 'grupo' => 'SA', 'docente' => 'DOC007'], // CARVAJAL CORDERO
            
            // ARQUITECTURA DE COMPUTADORAS
            ['asignatura' => 'INF211', 'grupo' => 'SA', 'docente' => 'DOC008'], // HINOJOSA SAAVEDRA
            ['asignatura' => 'INF211', 'grupo' => 'SB', 'docente' => 'DOC008'], // HINOJOSA SAAVEDRA
            
            // ARQUITECTURA DEL SOFTWARE
            ['asignatura' => 'INF552', 'grupo' => 'SA', 'docente' => 'DOC009'], // VEIZAGA GONZALES
            
            // AUDITORIA INFORMATICA
            ['asignatura' => 'INF462', 'grupo' => 'SA', 'docente' => 'DOC010'], // VARGAS PEAA
            
            // BASE DE DATOS I
            ['asignatura' => 'INF312', 'grupo' => 'SA', 'docente' => 'DOC009'], // VEIZAGA GONZALES
            ['asignatura' => 'INF312', 'grupo' => 'SC', 'docente' => 'DOC009'], // VEIZAGA GONZALES
            
            // BASES DE DATOS II
            ['asignatura' => 'INF322', 'grupo' => 'SD', 'docente' => 'DOC011'], // PEREZ FERREIRA
            ['asignatura' => 'INF322', 'grupo' => 'SB', 'docente' => 'DOC011'], // PEREZ FERREIRA
            
            // CALCULO I
            ['asignatura' => 'MAT101', 'grupo' => 'F1', 'docente' => 'DOC012'], // AVENDAÑO GONZALES
            ['asignatura' => 'MAT101', 'grupo' => 'SG', 'docente' => 'DOC013'], // CALIZAYA AJHUACHO
            ['asignatura' => 'MAT101', 'grupo' => 'CI', 'docente' => 'DOC014'], // TEJERINA GUERRA
            ['asignatura' => 'MAT101', 'grupo' => 'I2', 'docente' => 'DOC015'], // JUSTINIANO ROCA
        ];

        // Crear asignaciones
        foreach ($asignaciones as $asignacion) {
            $asignatura = $asignaturasCreadas[$asignacion['asignatura']] ?? null;
            $grupo = $gruposCreados[$asignacion['grupo']] ?? null;
            $docente = $docentesCreados[$asignacion['docente']] ?? null;

            if ($asignatura && $grupo && $docente) {
                GrupoAsignatura::firstOrCreate([
                    'docente_id' => $docente->id,
                    'grupo_id' => $grupo->id,
                    'asignatura_id' => $asignatura->id,
                    'semestre_id' => $semestre->id,
                ], [
                    'observacion' => 'Asignación automática'
                ]);
            }
        }

        $this->command->info('✅ Base de datos poblada exitosamente con los datos de la imagen');
        $this->command->info('👨‍💼 Administrador: admin@universidad.edu / admin123');
        $this->command->info('📊 Docentes creados: ' . count($docentesCreados));
        $this->command->info('📚 Asignaturas creadas: ' . count($asignaturasCreadas));
        $this->command->info('👥 Grupos creados: ' . count($gruposCreados));
        $this->command->info('🔗 Asignaciones creadas: ' . count($asignaciones));
    }
}

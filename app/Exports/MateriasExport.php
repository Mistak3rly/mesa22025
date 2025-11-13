<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

use App\Models\GrupoAsignatura;
use App\Models\Semestre;

class MateriasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Obtener el semestre actual (último ingresado)
        $semestreActual = Semestre::orderBy('id', 'desc')->first();

        // Filtrar grupo_asignaturas por ese semestre
        $grupoAsignaturas = GrupoAsignatura::with(['docente.persona', 'asignatura', 'grupo', 'semestre'])
            ->where('semestre_id', $semestreActual->id)
            ->get();

        // Mapear los datos para exportar
        return $grupoAsignaturas->map(function($ga) {
            return [
                'Docente'     => $ga->docente->persona->nombre,
                'Sigla'       => $ga->asignatura->sigla,
                'Materia'     => $ga->asignatura->descripcion,
                'Grupo'       => $ga->grupo->descripcion,
                'Semestre'    => $ga->semestre->descripcion,
                'Observacion' => $ga->observacion
            ];
        });
    }
    

    public function headings(): array
    {
        return ['Docente', 'Sigla', 'Materia', 'Grupo', 'Semestre', 'Observacion'];
    }
}

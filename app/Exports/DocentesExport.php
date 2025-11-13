<?php

// app/Exports/DocentesExport.php
namespace App\Exports;

use App\Models\Docente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocentesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Docente::with('persona')
            ->get()
            ->map(function($docente) {
                return [
                    'ID'      => $docente->id,
                    'Código'  => $docente->codigo,
                    'Nombre'  => $docente->persona->nombre,
                    'correo'  => $docente->correo,
                    'Carga Horaria'  => $docente->carga_horaria
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Código', 'Nombre', 'Correo', 'Carga Horaria'];
    }
}

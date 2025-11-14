<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteDinamicoExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $reporteData;
    protected $tipo;

    public function __construct($reporteData, $tipo = 'docentes')
    {
        $this->reporteData = $reporteData;
        $this->tipo = $tipo;
    }

    public function collection()
    {
        if ($this->tipo === 'docentes') {
            return collect($this->reporteData['horas_por_docente'])->map(function ($item) {
                return [
                    'docente' => $item['docente'],
                    'total_horas' => $item['total_horas'],
                    'materias' => $item['materias'],
                ];
            });
        } else {
            return collect($this->reporteData['horas_por_materia'])->map(function ($item) {
                return [
                    'codigo' => $item['codigo'],
                    'materia' => $item['materia'],
                    'total_horas' => $item['total_horas'],
                    'grupos' => $item['grupos'],
                ];
            });
        }
    }

    public function headings(): array
    {
        if ($this->tipo === 'docentes') {
            return [
                'Docente',
                'Total Horas',
                'Materias',
            ];
        } else {
            return [
                'Código',
                'Materia',
                'Total Horas',
                'Grupos',
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return $this->tipo === 'docentes' ? 'Horas por Docente' : 'Horas por Materia';
    }
}

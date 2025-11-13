<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\DocentesExport;
use App\Exports\MateriasExport;

use App\Models\GrupoAsignatura;
use App\Models\Semestre;
use App\Models\Docente;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        return view('application.reporte.index');
    }
    public function docentesExcel()
    {
        return Excel::download(new DocentesExport, 'docentes.xlsx');
    }

    public function materiasExcel()
    {
        return Excel::download(new MateriasExport, 'materias.xlsx');
    }

    public function docentesPdf()
    {
        // Obtener docentes con su relación persona
        $docentes = Docente::with('persona')->get();

        // Cargar la vista Blade y pasarle los datos
        $pdf = Pdf::loadView('application.reporte.docente', compact('docentes'));

        // Descargar el archivo
        return $pdf->download('docentes.pdf');
    }

    public function materiasPdf()
    {
        $semestreActual = Semestre::orderBy('id', 'desc')->first();

        $grupoAsignaturas = GrupoAsignatura::with(['docente.persona', 'asignatura', 'grupo', 'semestre'])
            ->where('semestre_id', $semestreActual->id)
            ->get();

        $pdf = Pdf::loadView(
            'application.reporte.asignatura', 
            compact('grupoAsignaturas', 'semestreActual')
        );
        return $pdf->download('materias.pdf');
    }

}
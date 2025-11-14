<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HoraController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\RolController;


Route::get('/', function () {
    return view('auth.account-type-selector');
})->name('home');

// Selector de tipo de cuenta
Route::get('/account-selector', function () {
    return view('auth.account-type-selector');
})->name('account.selector');

// Rutas de login por tipo de cuenta
Route::get('/login/estudiante', function () {
    return view('auth.login-estudiante');
})->middleware(\App\Http\Middleware\PreventBackHistory::class)->name('login.estudiante');

Route::get('/login/docente', function () {
    return view('auth.login-docente');
})->middleware(\App\Http\Middleware\PreventBackHistory::class)->name('login.docente');

Route::get('/login/administrativo', function () {
    return view('auth.login-administrativo');
})->middleware(\App\Http\Middleware\PreventBackHistory::class)->name('login.administrativo');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard Administrador
Route::view('dashboard-administrador', 'dashboard-administrador')
    ->middleware('auth')
    ->name('administrador.dashboard');

Route::middleware(['auth'])->group( function () {

    // Ruta para la bitacora
    Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');
    
    Route::resource('users', UserController::class);

    //Rutas para el modulo docente
    Route::get('dashboard-docente', [DocenteController::class, 'dashboard'])->name('docente.dashboard');
    Route::get('docentes/deletedIndex', [DocenteController::class, 'deletedIndex'])->name('docentes.deleted-index');
    Route::patch('docentes/reactivate/{id}', [DocenteController::class, 'reactivate'])->name('docentes.reactivar');
    Route::post('docentes/assign', [DocenteController::class, 'assign'])->name('docentes.asignar-materia');
    Route::get('docentes/assigned/{id}', [DocenteController::class, 'assigned'])->name('docentes.materias-asignadas');
    Route::resource('docentes', DocenteController::class);

    //Rutas para el modulo administrativo
    Route::get('administrativos/deletedIndex', [AdministrativoController::class, 'deletedIndex'])->name('administrativos.deleted-index');
    Route::patch('administrativos/reactivate/{id}', [AdministrativoController::class, 'reactivate'])->name('administrativos.reactivar');
    Route::resource('administrativos', AdministrativoController::class);

    //Rutas para el modulo rol
    Route::get('roles/deletedIndex', [RolController::class, 'deletedIndex'])->name('roles.deleted-index');
    Route::patch('roles/reactivate/{id}', [RolController::class, 'reactivate'])->name('roles.reactivar');
    Route::resource('roles', RolController::class);

    //Rutas para el modulo dia
    Route::get('dias/deletedIndex', [DiaController::class, 'deletedIndex'])->name('dias.deleted-index');
    Route::patch('dias/reactivate/{id}', [DiaController::class, 'reactivate'])->name('dias.reactivar');
    Route::resource('dias', DiaController::class);

    //Rutas para modulo horas
    //Route::get('horas/deletedIndex', [HoraController::class, 'deletedIndex'])->name('horas.deleted-index');
    //Route::patch('horas/reactivate/{id}', [HoraController::class, 'reactivate'])->name('horas.reactivar');
    Route::resource('horas', HoraController::class);

    //Rutas para modulo grupo
    Route::get('grupos/deletedIndex', [GrupoController::class, 'deletedIndex'])->name('grupos.deleted-index');
    Route::patch('grupos/reactivate/{id}', [GrupoController::class, 'reactivate'])->name('grupos.reactivar');
    Route::resource('grupos', GrupoController::class);

    //Rutas para modulo aula
    Route::get('aulas/deletedIndex', [AulaController::class, 'deletedIndex'])->name('aulas.deleted-index');
    Route::patch('aulas/reactivate/{id}', [AulaController::class, 'reactivate'])->name('aulas.reactivar');
    Route::resource('aulas', AulaController::class);

    //Rutas para modulo asignaturas
    Route::get('asignaturas/deletedIndex', [AsignaturaController::class, 'deletedIndex'])->name('asignaturas.deleted-index');
    Route::patch('asignaturas/reactivate/{id}', [AsignaturaController::class, 'reactivate'])->name('asignaturas.reactivar');
    Route::resource('asignaturas', AsignaturaController::class);

    //Rutas para modulo semestres
    Route::get('semestres/deletedIndex', [SemestreController::class, 'deletedIndex'])->name('semestres.deleted-index');
    Route::resource('semestres', SemestreController::class);

    //Rutas para modulo horarios
    Route::get('horarios/deletedIndex', [HorarioController::class, 'deletedIndex'])->name('horarios.deleted-index');
    Route::patch('horarios/reactivate/{id}', [HorarioController::class, 'reactivate'])->name('horarios.reactivar');
    Route::resource('horarios', HorarioController::class);

    //Rutas para modulo asistencias
    Route::resource('asistencias', AsistenciaController::class);

    // Rutas para la seccion reportes
    // Reportes de docentes
    Route::get('/reportes/docentes/excel', [ReporteController::class, 'docentesExcel'])->name('reportes.docentes.excel');
    Route::get('/reportes/docentes/pdf', [ReporteController::class, 'docentesPdf'])->name('reportes.docentes.pdf');

    // Reportes de materias
    Route::get('/reportes/materias/excel', [ReporteController::class, 'materiasExcel'])->name('reportes.materias.excel');
    Route::get('/reportes/materias/pdf', [ReporteController::class, 'materiasPdf'])->name('reportes.materias.pdf');

    // Reportes dinámicos (ahora en index)
    Route::get('/reportes/exportar-dinamico', [ReporteController::class, 'exportarDinamico'])->name('reportes.exportar-dinamico');
    Route::get('/reportes/exportar-dinamico-excel', [ReporteController::class, 'exportarDinamicoExcel'])->name('reportes.exportar-dinamico-excel');
    
    // Reportes automáticos
    Route::get('/reportes/automatico/horario-semanal', [\App\Http\Controllers\ReporteAutomaticoController::class, 'horarioSemanal'])->name('reportes.automatico.horario-semanal');
    Route::get('/reportes/automatico/ausencias-docente', [\App\Http\Controllers\ReporteAutomaticoController::class, 'ausenciasDocente'])->name('reportes.automatico.ausencias-docente');
    Route::get('/reportes/automatico/aulas-disponibles', [\App\Http\Controllers\ReporteAutomaticoController::class, 'aulasDisponibles'])->name('reportes.automatico.aulas-disponibles');
    
    Route::resource('reportes', ReporteController::class);

});

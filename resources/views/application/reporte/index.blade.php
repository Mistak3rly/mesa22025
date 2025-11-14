<x-layouts.app :title="__('Seccion Reportes')">
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Panel de Reportes</h2>

        <!-- Reportes Básicos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Reporte de Docentes -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Docentes</h3>
                <div class="flex gap-4">
                    <a href="{{ route('reportes.docentes.excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Exportar a Excel
                    </a>
                    <a href="{{ route('reportes.docentes.pdf') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Exportar a PDF
                    </a>
                </div>
            </div>

            <!-- Reporte de Materias -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Materias</h3>
                <div class="flex gap-4">
                    <a href="{{ route('reportes.materias.excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Exportar a Excel
                    </a>
                    <a href="{{ route('reportes.materias.pdf') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Exportar a PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Control de Reportes -->
        <div class="mb-10">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Control de Reportes</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Horario Semanal -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800">Horario Semanal</h4>
                            <p class="text-sm text-gray-600">Vista completa de la semana</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('reportes.automatico.horario-semanal') }}">
                        <select name="semestre_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3">
                            @foreach(\App\Models\Semestre::orderBy('id', 'desc')->get() as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->descripcion }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Ver Horario Semanal
                        </button>
                    </form>
                </div>

                <!-- Ausencias por Docente -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800">Ausencias por Docente</h4>
                            <p class="text-sm text-gray-600">Control de asistencias</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('reportes.automatico.ausencias-docente') }}">
                        <select name="docente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3" required>
                            <option value="">Seleccionar docente</option>
                            @foreach(\App\Models\Docente::with('persona')->where('estado', true)->get() as $docente)
                                <option value="{{ $docente->id }}">{{ $docente->persona->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <input type="date" name="fecha_inicio" value="{{ date('Y-m-01') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                            <input type="date" name="fecha_fin" value="{{ date('Y-m-t') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Ver Ausencias
                        </button>
                    </form>
                </div>

                <!-- Aulas Disponibles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800">Aulas Disponibles</h4>
                            <p class="text-sm text-gray-600">Verificar disponibilidad</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('reportes.automatico.aulas-disponibles') }}">
                        <select name="dia_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3" required>
                            <option value="">Seleccionar día</option>
                            @foreach(\App\Models\Dia::where('estado', true)->get() as $dia)
                                <option value="{{ $dia->id }}">{{ $dia->descripcion }}</option>
                            @endforeach
                        </select>
                        <select name="hora_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3" required>
                            <option value="">Seleccionar hora</option>
                            @foreach(\App\Models\Hora::all() as $hora)
                                <option value="{{ $hora->id }}">{{ $hora->hora_inicio->format('H:i') }} - {{ $hora->hora_fin->format('H:i') }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Verificar Disponibilidad
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Exportación de Reportes Dinámicos -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border-2 border-blue-200 p-6 mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Exportación de Reportes Dinámicos</h3>
            <p class="text-gray-600 mb-6">Genera reportes personalizados con filtros avanzados</p>
            
            <!-- Formulario de Filtros -->
            <form method="GET" action="{{ route('reportes.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Semestre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semestre</label>
                        <select name="semestre_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los semestres</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id }}" {{ request('semestre_id') == $semestre->id ? 'selected' : '' }}>
                                    {{ $semestre->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Docente -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                        <select name="docente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los docentes</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->persona->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grupo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
                        <select name="grupo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los grupos</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                    Grupo {{ $grupo->descripcion }} - {{ $grupo->turno }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Asignatura -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Asignatura</label>
                        <select name="asignatura_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Todas las asignaturas</option>
                            @foreach($asignaturas as $asignatura)
                                <option value="{{ $asignatura->id }}" {{ request('asignatura_id') == $asignatura->id ? 'selected' : '' }}>
                                    {{ $asignatura->codigo }} - {{ $asignatura->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Día de la semana -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Día de la Semana</label>
                        <select name="dia_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los días</option>
                            @foreach(\App\Models\Dia::where('estado', true)->get() as $dia)
                                <option value="{{ $dia->id }}" {{ request('dia_id') == $dia->id ? 'selected' : '' }}>
                                    {{ $dia->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" name="generar_reporte" value="1" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Generar Reporte
                    </button>
                    <a href="{{ route('reportes.index') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>

        @if(isset($reporteData))
            <!-- Filtros Aplicados -->
            @if(count($filtrosAplicados) > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">Filtros Aplicados:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($filtrosAplicados as $key => $value)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ ucfirst($key) }}: {{ $value }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Dashboard de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Horarios -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Horarios</p>
                            <p class="text-3xl font-bold">{{ $reporteData['total_horarios'] }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Porcentaje Asistencia -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Asistencia</p>
                            <p class="text-3xl font-bold">{{ $reporteData['porcentaje_asistencia'] }}%</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Ausencias -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm">Ausencias</p>
                            <p class="text-3xl font-bold">{{ $reporteData['total_ausencias'] }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horas por Docente -->
            @if(count($reporteData['horas_por_docente']) > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Horas por Docente</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Horas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materias</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reporteData['horas_por_docente'] as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $data['docente'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                {{ $data['total_horas'] }} horas
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data['materias'] }} materia(s)
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Horas por Materia -->
            @if(count($reporteData['horas_por_materia']) > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Horas por Materia</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Horas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reporteData['horas_por_materia'] as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $data['codigo'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $data['materia'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">
                                                {{ $data['total_horas'] }} horas
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $data['grupos'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Botones de Exportar -->
            <div class="flex justify-end gap-3">
                <!-- Exportar a Excel -->
                <form method="GET" action="{{ route('reportes.exportar-dinamico-excel') }}">
                    @foreach(request()->all() as $key => $value)
                        @if($key !== 'generar_reporte')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar a Excel
                    </button>
                </form>

                <!-- Exportar a PDF -->
                <form method="GET" action="{{ route('reportes.exportar-dinamico') }}">
                    @foreach(request()->all() as $key => $value)
                        @if($key !== 'generar_reporte')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar a PDF
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.app>

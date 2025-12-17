<x-layouts.app :title="__('Asignación Automática de Horarios')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <div class="mb-4 sm:mb-0">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-2 text-gray-900">Asignación Automática de Horarios</h2>
                    <p class="text-gray-900 text-sm sm:text-base">
                        <span class="font-semibold">Semestre:</span> {{ $semestre->descripcion }}<br>
                        <span class="font-semibold">Período:</span> {{ \Carbon\Carbon::parse($semestre->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($semestre->fecha_fin)->format('d/m/Y') }}
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="bg-blue-100 rounded-lg p-3 sm:p-4">
                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de resultado -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold">{{ session('success') }}</p>
                    @if(session('total_creados'))
                        <p class="text-sm mt-1">Total de horarios creados: {{ session('total_creados') }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <p class="font-semibold">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
            <!-- Panel de estadísticas -->
            <div class="xl:col-span-1 order-2 xl:order-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estadísticas del Sistema</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Docentes activos</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $totalDocentes }}</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Asignaturas</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $totalAsignaturas }}</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Aulas disponibles</span>
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $totalAulas }}</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Horarios existentes</span>
                            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $horariosExistentes }}</span>
                        </div>
                    </div>
                </div>

                <!-- Asignaciones pendientes -->
                @if($asignacionesPendientes->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Asignaciones Pendientes</h3>
                    
                    <div class="space-y-3 max-h-48 sm:max-h-64 overflow-y-auto">
                        @foreach($asignacionesPendientes as $pendiente)
                        <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-700">
                            <p class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $pendiente['docente'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Código: {{ $pendiente['codigo'] }}</p>
                            <p class="text-xs text-orange-700 dark:text-orange-300 mt-1">{{ $pendiente['asignaturas'] }} asignatura(s)</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Formulario de configuración -->
            <div class="xl:col-span-2 order-1 xl:order-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Configuración de Asignación</h3>

                    <form action="{{ route('horarios.procesar-asignacion-automatica') }}" method="POST" id="form-asignacion">
                        @csrf
                        <input type="hidden" name="semestre_id" value="{{ $semestre->id }}">

                        <!-- Selección de días -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Días de la semana para asignar
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-2 sm:gap-3">
                                @foreach($dias as $dia)
                                <label class="flex items-center p-2 sm:p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-500 transition">
                                    <input type="checkbox" name="dias_seleccionados[]" value="{{ $dia->id }}" 
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array(strtolower($dia->descripcion), ['lunes', 'martes', 'miercoles', 'jueves', 'viernes']) ? 'checked' : '' }}>
                                    <span class="ml-2 text-xs sm:text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $dia->descripcion }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rango de horas -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                            <div>
                                <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hora de inicio
                                </label>
                                <select name="hora_inicio" id="hora_inicio" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Seleccionar hora de inicio</option>
                                    @foreach($horas as $hora)
                                    <option value="{{ $hora->id }}" {{ $hora->hora_inicio->format('H:i') == '07:00' ? 'selected' : '' }}>
                                        {{ $hora->hora_inicio->format('H:i') }} - {{ $hora->hora_fin->format('H:i') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    🕕 Hora de fin
                                </label>
                                <select name="hora_fin" id="hora_fin" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Seleccionar hora de fin</option>
                                    @foreach($horas as $hora)
                                    <option value="{{ $hora->id }}" {{ $hora->hora_fin->format('H:i') == '18:00' ? 'selected' : '' }}>
                                        {{ $hora->hora_inicio->format('H:i') }} - {{ $hora->hora_fin->format('H:i') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Opciones avanzadas -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Opciones Avanzadas</h4>
                            
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <input type="checkbox" name="limpiar_existentes" value="1" 
                                           class="w-5 h-5 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="block font-semibold text-gray-900 dark:text-white">Limpiar horarios existentes</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">Eliminar todos los horarios del semestre antes de generar nuevos</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-green-50 dark:hover:bg-green-900/20">
                                    <input type="checkbox" name="prioridad_docentes" value="1" checked
                                           class="w-5 h-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="block font-semibold text-gray-900 dark:text-white">Priorizar disponibilidad de docentes</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">Evitar conflictos de horarios para docentes</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                    <input type="checkbox" name="distribuir_aulas" value="1" checked
                                           class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="block font-semibold text-gray-900 dark:text-white">Distribución inteligente de aulas</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">Optimizar el uso de aulas disponibles</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button type="button" onclick="confirmarAsignacion()" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 sm:px-6 rounded-lg transition flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="hidden sm:inline">Generar Horarios Automáticamente</span>
                                <span class="sm:hidden">Generar Horarios</span>
                            </button>
                            
                            <a href="{{ route('horarios.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 sm:px-6 rounded-lg transition flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resultados de asignaciones exitosas -->
        @if(session('asignaciones_exitosas') && count(session('asignaciones_exitosas')) > 0)
        <div class="mt-4 sm:mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4">Asignaciones Exitosas</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Día</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aula</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach(session('asignaciones_exitosas') as $asignacion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $asignacion['docente'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $asignacion['asignatura'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $asignacion['grupo'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 capitalize">{{ $asignacion['dia'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $asignacion['hora'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $asignacion['aula'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Resultados de asignaciones fallidas -->
        @if(session('asignaciones_fallidas') && count(session('asignaciones_fallidas')) > 0)
        <div class="mt-4 sm:mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Asignaciones Fallidas</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach(session('asignaciones_fallidas') as $fallida)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $fallida['docente'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $fallida['asignatura'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $fallida['grupo'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">{{ $fallida['motivo'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <script>
        function confirmarAsignacion() {
            // Validar que se hayan seleccionado días
            const diasSeleccionados = document.querySelectorAll('input[name="dias_seleccionados[]"]:checked');
            if (diasSeleccionados.length === 0) {
                alert('Por favor, selecciona al menos un día de la semana.');
                return;
            }

            // Validar rango de horas
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = document.getElementById('hora_fin').value;
            
            if (!horaInicio || !horaFin) {
                alert('Por favor, selecciona el rango de horas.');
                return;
            }

            // Confirmar acción
            const limpiarExistentes = document.querySelector('input[name="limpiar_existentes"]').checked;
            let mensaje = '¿Estás seguro de que deseas generar los horarios automáticamente?\n\n';
            mensaje += `Días seleccionados: ${diasSeleccionados.length}\n`;
            
            if (limpiarExistentes) {
                mensaje += '\n⚠️ ATENCIÓN: Se eliminarán todos los horarios existentes del semestre.';
            }

            if (confirm(mensaje)) {
                // Mostrar indicador de carga
                const boton = event.target;
                const textoOriginal = boton.innerHTML;
                boton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Procesando...';
                boton.disabled = true;

                // Enviar formulario
                document.getElementById('form-asignacion').submit();
            }
        }

        // Validación en tiempo real del rango de horas
        document.getElementById('hora_inicio').addEventListener('change', function() {
            const horaFin = document.getElementById('hora_fin');
            const horaInicioSeleccionada = this.selectedIndex;
            
            // Deshabilitar opciones de hora fin que sean anteriores a la hora inicio
            Array.from(horaFin.options).forEach((option, index) => {
                if (index > 0 && index <= horaInicioSeleccionada) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
            
            // Si la hora fin seleccionada es anterior a la hora inicio, resetearla
            if (horaFin.selectedIndex > 0 && horaFin.selectedIndex <= horaInicioSeleccionada) {
                horaFin.selectedIndex = 0;
            }
        });
    </script>
</x-layouts.app>
<x-layouts.app :title="__('Registrar Asistencia')">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-md p-6 mb-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Registro de Asistencia</h2>
                    <p class="text-green-100">
                        <span class="font-semibold">Docente:</span> {{ Auth::user()->persona->nombre }}<br>
                        <span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($hoy)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}<br>
                        <span class="font-semibold">Hora actual:</span> <span id="hora-actual" class="font-mono bg-white/20 px-2 py-1 rounded">--:--:--</span>
                    </p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-lg p-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito y error -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="font-semibold">{{ session('success') }}</p>
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

        <!-- Clases del día -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Mis Clases de Hoy</h3>

            @if($clasesHoy->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No tienes clases programadas para hoy</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Disfruta tu día libre</p>
                </div>
            @else
                <!-- Separar clases en curso de las demás -->
                @php
                    $clasesEnCurso = $clasesHoy->where('en_curso', true);
                    $clasesRestantes = $clasesHoy->where('en_curso', false);
                @endphp

                <!-- Clases EN CURSO (prioritarias) -->
                @if($clasesEnCurso->count() > 0)
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="bg-red-500 w-3 h-3 rounded-full animate-pulse mr-2"></div>
                            <h4 class="text-lg font-bold text-red-600 dark:text-red-400">🔴 CLASES EN CURSO</h4>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($clasesEnCurso as $clase)
                            <div class="border-2 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-lg p-5 shadow-lg relative animate-pulse">
                                <!-- Badge EN CURSO -->
                                <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold animate-bounce">
                                    EN CURSO
                                </div>
                                
                                <!-- Header de la tarjeta -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $clase['asignatura'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Aula {{ $clase['aula'] }}
                                        </p>
                                    </div>
                                    @if($clase['asistencia_registrada'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Registrada
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Horario con ventana -->
                                <div class="bg-white dark:bg-gray-700 rounded-lg p-3 mb-4">
                                    <div class="flex items-center text-red-700 dark:text-red-300 mb-2">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-bold">{{ $clase['hora_inicio'] }} - {{ $clase['hora_fin'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        Ventana de asistencia: {{ $clase['ventana_inicio'] }} - {{ $clase['ventana_fin'] }}
                                    </p>
                                </div>

                                @if($clase['puede_marcar'])
                                    <!-- Formulario de asistencia PRIORITARIO -->
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                                            ⚡ ¡Marca tu asistencia AHORA!
                                        </p>
                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            <button onclick="marcarAsistencia({{ $clase['id'] }}, 'presente')" 
                                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition">
                                                Presente
                                            </button>
                                            <button onclick="marcarAsistencia({{ $clase['id'] }}, 'ausente')" 
                                                class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition">
                                                Ausente
                                            </button>
                                            <button onclick="abrirModalJustificacion({{ $clase['id'] }}, '{{ $clase['asignatura'] }}', '{{ $clase['hora_inicio'] }} - {{ $clase['hora_fin'] }}')" 
                                                class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition">
                                                Justificado
                                            </button>
                                        </div>
                                    </div>
                                @elseif($clase['asistencia_registrada'])
                                    <!-- Asistencia ya registrada -->
                                    <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3 border border-green-200 dark:border-green-700">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Estado registrado:</p>
                                        <p class="font-bold text-green-700 dark:text-green-300 capitalize">
                                            {{ $clase['estado_asistencia'] }}
                                        </p>
                                        @if($clase['estado_asistencia'] === 'justificado' && !empty($clase['observacion']))
                                            <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded">
                                                <p class="text-xs text-yellow-800 dark:text-yellow-200 font-semibold">Justificación:</p>
                                                <p class="text-xs text-yellow-700 dark:text-yellow-300">{{ $clase['observacion'] }}</p>
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            Registrado: {{ \Carbon\Carbon::parse($clase['fecha_registro'])->format('H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Otras clases del día -->
                @if($clasesRestantes->count() > 0)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Otras clases de hoy</h4>
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($clasesRestantes as $clase)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 hover:shadow-lg transition {{ $clase['asistencia_registrada'] ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700' : 'bg-white dark:bg-gray-800' }}">
                                <!-- Header de la tarjeta -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $clase['asignatura'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Aula {{ $clase['aula'] }}
                                        </p>
                                    </div>
                                    @if($clase['asistencia_registrada'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Registrada
                                        </span>
                                    @endif
                                </div>

                                <!-- Horario -->
                                <div class="flex items-center text-gray-700 dark:text-gray-300 mb-4">
                                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-semibold">{{ $clase['hora_inicio'] }} - {{ $clase['hora_fin'] }}</span>
                                </div>

                                @if($clase['asistencia_registrada'])
                                    <!-- Asistencia ya registrada -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-green-200 dark:border-green-700">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Estado:</p>
                                        <p class="font-semibold text-green-700 dark:text-green-400 capitalize">
                                            {{ $clase['estado_asistencia'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            Registrado: {{ \Carbon\Carbon::parse($clase['fecha_registro'])->format('H:i') }}
                                        </p>
                                    </div>
                                @else
                                    <!-- Botón para registrar asistencia -->
                                    <button onclick="abrirModal({{ $clase['id'] }}, '{{ $clase['asignatura'] }}', '{{ $clase['hora_inicio'] }} - {{ $clase['hora_fin'] }}')" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        Registrar Asistencia
                                    </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Modal de registro de asistencia -->
    <div id="modal-asistencia" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Registrar Asistencia
                </h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <form action="{{ route('asistencias.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="horario_id" id="modal-horario-id">

                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Clase:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-asignatura"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-500" id="modal-horario"></p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Selecciona tu estado:
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-500 transition">
                            <input type="radio" name="estado" value="presente" required class="w-5 h-5 text-green-600 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="block font-semibold text-gray-900 dark:text-white">Presente</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Estoy en clase</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-500 transition">
                            <input type="radio" name="estado" value="ausente" class="w-5 h-5 text-red-600 focus:ring-red-500">
                            <div class="ml-3">
                                <span class="block font-semibold text-gray-900 dark:text-white">Ausente</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">No puedo asistir</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-yellow-50 dark:hover:bg-yellow-900/20 hover:border-yellow-500 transition">
                            <input type="radio" name="estado" value="justificado" class="w-5 h-5 text-yellow-600 focus:ring-yellow-500">
                            <div class="ml-3">
                                <span class="block font-semibold text-gray-900 dark:text-white">Justificado</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Ausencia justificada</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="cerrarModal()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de justificación -->
    <div id="modal-justificacion" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Justificar Ausencia
                </h3>
                <button onclick="cerrarModalJustificacion()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <form action="{{ route('asistencias.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="horario_id" id="modal-justificacion-horario-id">
                <input type="hidden" name="estado" value="justificado">

                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Clase:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-justificacion-asignatura"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-500" id="modal-justificacion-horario"></p>
                </div>

                <div class="mb-6">
                    <label for="observacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Motivo de la justificación <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="observacion" 
                        id="observacion" 
                        rows="4" 
                        required
                        placeholder="Explica el motivo de tu ausencia (enfermedad, emergencia familiar, etc.)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white resize-none"
                    ></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Mínimo 10 caracteres. Sé específico sobre el motivo.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="cerrarModalJustificacion()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Justificar Ausencia
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(horarioId, asignatura, horario) {
            document.getElementById('modal-horario-id').value = horarioId;
            document.getElementById('modal-asignatura').textContent = asignatura;
            document.getElementById('modal-horario').textContent = horario;
            document.getElementById('modal-asistencia').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-asistencia').classList.add('hidden');
            // Limpiar selección
            document.querySelectorAll('input[name="estado"]').forEach(input => {
                input.checked = false;
            });
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modal-asistencia').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Funciones para justificación
        function abrirModalJustificacion(horarioId, asignatura, horario) {
            document.getElementById('modal-justificacion-horario-id').value = horarioId;
            document.getElementById('modal-justificacion-asignatura').textContent = asignatura;
            document.getElementById('modal-justificacion-horario').textContent = horario;
            document.getElementById('modal-justificacion').classList.remove('hidden');
            document.getElementById('observacion').focus();
        }

        function cerrarModalJustificacion() {
            document.getElementById('modal-justificacion').classList.add('hidden');
            document.getElementById('observacion').value = '';
        }

        // Función para marcar asistencia directa (presente/ausente)
        function marcarAsistencia(horarioId, estado) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("asistencias.store") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const horarioInput = document.createElement('input');
            horarioInput.type = 'hidden';
            horarioInput.name = 'horario_id';
            horarioInput.value = horarioId;
            
            const estadoInput = document.createElement('input');
            estadoInput.type = 'hidden';
            estadoInput.name = 'estado';
            estadoInput.value = estado;
            
            form.appendChild(csrfToken);
            form.appendChild(horarioInput);
            form.appendChild(estadoInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        // Cerrar modal de justificación al hacer clic fuera
        document.getElementById('modal-justificacion').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalJustificacion();
            }
        });

        // Validación del formulario de justificación
        document.querySelector('#modal-justificacion form').addEventListener('submit', function(e) {
            const observacion = document.getElementById('observacion').value.trim();
            if (observacion.length < 10) {
                e.preventDefault();
                alert('Por favor, proporciona un motivo más detallado (mínimo 10 caracteres).');
                document.getElementById('observacion').focus();
            }
        });

        // Actualizar página cada minuto para mostrar clases en curso en tiempo real
        setInterval(function() {
            // Solo recargar si no hay modales abiertos
            const modal = document.getElementById('modal-asistencia');
            if (!modal || modal.classList.contains('hidden')) {
                window.location.reload();
            }
        }, 60000); // 60 segundos

        // Mostrar hora actual en tiempo real
        function actualizarHora() {
            const ahora = new Date();
            const horaActual = ahora.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            
            // Buscar si hay un elemento para mostrar la hora
            const elementoHora = document.getElementById('hora-actual');
            if (elementoHora) {
                elementoHora.textContent = horaActual;
            }
        }

        // Actualizar hora cada segundo
        setInterval(actualizarHora, 1000);
        actualizarHora(); // Ejecutar inmediatamente
    </script>
</x-layouts.app>
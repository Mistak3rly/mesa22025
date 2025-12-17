<x-layouts.app :title="__('Crear horario')">
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">

        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Crear Horario y Asignar Docente</h2>

        <!-- Alertas de conflictos -->
        @if($errors->has('conflictos'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center mb-3">
                    <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">⚠️ Conflictos de Horarios Detectados</h3>
                </div>
                <div class="space-y-2">
                    @foreach($errors->get('conflictos') as $conflictos)
                        @foreach($conflictos as $conflicto)
                            <p class="text-sm text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-800/30 p-3 rounded-md border border-red-200 dark:border-red-700">
                                {{ $conflicto }}
                            </p>
                        @endforeach
                    @endforeach
                </div>
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-md">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>💡 Sugerencia:</strong> Revisa los horarios existentes y selecciona una hora o aula diferente para evitar conflictos.
                    </p>
                </div>
            </div>
        @endif

        <!-- Mensaje de advertencia general -->
        @if(session('warning'))
            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-yellow-800 dark:text-yellow-200 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('horarios.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Docente -->
            <div>
                <label for="docente_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Docente</label>
                <input type="text" value="{{ $docente->persona->nombre }}" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-gray-100 dark:bg-gray-700">
                <input type="hidden" name="docente_id" value="{{ $docente->id }}">
                @error('docente_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seccion Hora inicio y hora fin -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hora_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora inicio</label>
                    <!-- se debe enviar el id de la hora que en el controlador se agregara a la tabla horarios como hora_id -->
                    <select name="hora_id" id="hora_inicio" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        @foreach($horas as $hora)
                            <option value="{{ $hora->id }}" data-hora-fin="{{ $hora->hora_fin->format('H:i') }}">{{ $hora->hora_inicio->format('H:i') }}</option>
                        @endforeach
                    </select>
                    @error('hora_inicio')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="hora_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora fin</label>
                    <input type="time" id="hora_fin" readonly
                        class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('hora_fin')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Día y Aula -->
            <div class="space-y-6">
                <!-- Días disponibles -->
                <div>
                    <label for="dia_id" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Selecciona los días</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($dias as $dia)
                            <label class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-blue-50 dark:hover:bg-blue-900 transition">
                                <input type="checkbox" name="dia_id[]" value="{{ $dia->id }}"
                                    class="form-checkbox text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400"
                                    {{ in_array($dia->id, old('dia_id', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $dia->descripcion }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('dia_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aula debajo -->
                <div>
                    <label for="aula_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Aula</label>
                    <select name="aula_id" id="aula_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}">{{ "{$aula->numero_aula} → {$aula->tipo_aula}" }}</option>
                        @endforeach
                    </select>
                    @error('aula_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Asignatura  y Semestre -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="asignatura_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignatura</label>
                    <input type="hidden" name="grupo_id" id="grupo_id">
                    <select name="asignatura_id" id="asignatura_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        @foreach($asignaciones as $item)
                            <option value="{{ $item['asignatura']->id }}" data-grupo="{{ $item['grupo']->id }}"> {{ " {$item['asignatura']->descripcion} —— {$item['grupo']->descripcion} " }}</option>
                        @endforeach
                    </select>
                    @error('asignatura_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Semestre -->
                <div>
                    <label for="semestre_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semestre</label>
                    <input type="hidden" name="semestre_id" value="{{ $semestre->id }}">
                    <input type="text" value="{{ $semestre->descripcion }}" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-gray-100 dark:bg-gray-700">
                    @error('semestre_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>            

            <!-- Observación -->
            <div>
                <label for="observacion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Observación</label>
                <textarea name="observacion" id="observacion" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                @error('observacion')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón -->
            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Guardar
                </button>
            </div>
        </form>
        <form action="{{ route('docentes.index') }}" method="GET">
            @csrf
            <button type="submit"class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Volver a Docentes
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {



            const asignaturaSelect = document.getElementById('asignatura_id');
            const grupoInput = document.getElementById('grupo_id');

            const selectInicio = document.getElementById('hora_inicio');
            const inputFin = document.getElementById('hora_fin');

            function updateGrupoId() {
                const selectedOption = asignaturaSelect.options[asignaturaSelect.selectedIndex];
                const grupoId = selectedOption.getAttribute('data-grupo');
                grupoInput.value = grupoId;
            }

            selectInicio.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const horaFin = selectedOption.getAttribute('data-hora-fin');
                inputFin.value = horaFin || '';
            });

            // Inicializar al cargar
            updateGrupoId();

            // Actualizar al cambiar
            asignaturaSelect.addEventListener('change', updateGrupoId);

            // Inicializar si ya hay una opción seleccionada
            selectInicio.dispatchEvent(new Event('change'));

            // Agregar validación visual de conflictos
            const form = document.querySelector('form');
            const submitButton = form.querySelector('button[type="submit"]');
            
            function mostrarAdvertencia(mensaje) {
                // Remover advertencias previas
                const advertenciaPrevia = document.querySelector('.advertencia-conflicto');
                if (advertenciaPrevia) {
                    advertenciaPrevia.remove();
                }
                
                // Crear nueva advertencia
                const advertencia = document.createElement('div');
                advertencia.className = 'advertencia-conflicto mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg';
                advertencia.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                        </svg>
                        <p class="text-yellow-800 dark:text-yellow-200 text-sm">${mensaje}</p>
                    </div>
                `;
                
                // Insertar antes del formulario
                form.parentNode.insertBefore(advertencia, form);
            }
            
            function validarSeleccion() {
                const diasSeleccionados = document.querySelectorAll('input[name="dia_id[]"]:checked');
                const horaSeleccionada = selectInicio.value;
                const aulaSeleccionada = document.getElementById('aula_id').value;
                
                if (diasSeleccionados.length > 0 && horaSeleccionada && aulaSeleccionada) {
                    mostrarAdvertencia('⚠️ Asegúrate de que no haya conflictos de horarios. El sistema validará automáticamente al guardar.');
                }
            }
            
            // Agregar listeners para validación en tiempo real
            document.querySelectorAll('input[name="dia_id[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', validarSeleccion);
            });
            
            selectInicio.addEventListener('change', validarSeleccion);
            document.getElementById('aula_id').addEventListener('change', validarSeleccion);
            
        });
    </script>
</x-layouts.app>
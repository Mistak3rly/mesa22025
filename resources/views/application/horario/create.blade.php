<x-layouts.app :title="__('Crear horario')">
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">

        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Crear Horario y Asignar Docente</h2>

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

            let varaible = @json($horas);
            console.log(varaible);
            
        });
    </script>
</x-layouts.app>
<x-layouts.app :title="__('Simulador de Horarios')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <div class="mb-4 sm:mb-0">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-2 text-gray-900">Simulador de Horarios</h2>
                    <p class="text-gray-900 text-sm sm:text-base">
                        <span class="font-semibold">Semestre:</span> {{ $semestre->descripcion }}<br>
                        <span class="font-semibold">Simula cambios sin afectar los horarios oficiales</span>
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="bg-purple-100 rounded-lg p-3 sm:p-4">
                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
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

        @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="font-semibold">{{ session('warning') }}</p>
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

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 sm:gap-6">
            <!-- Panel de estadísticas -->
            <div class="xl:col-span-1 order-2 xl:order-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estado Actual</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Horarios</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $totalHorarios }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Docentes</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $totalDocentes }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aulas</span>
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $totalAulas }}</span>
                        </div>
                    </div>
                </div>

                <!-- Panel de cambios pendientes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cambios Simulados</h3>
                    
                    <div id="cambios-pendientes" class="space-y-3 max-h-64 overflow-y-auto">
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                            No hay cambios simulados
                        </p>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button id="btn-simular" onclick="procesarSimulacion()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed" 
                                disabled>
                            Simular Cambios
                        </button>
                        
                        <button id="btn-aplicar" onclick="aplicarCambios()" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed" 
                                disabled>
                            Aplicar Cambios
                        </button>
                        
                        <button onclick="limpiarCambios()" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Limpiar Todo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de horarios -->
            <div class="xl:col-span-3 order-1 xl:order-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 sm:mb-0">
                            Horarios Actuales
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Original
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Modificado
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Conflicto
                            </span>
                        </div>
                    </div>

                    @if($horariosActuales->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No hay horarios para simular</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Crea algunos horarios primero</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Docente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Día</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aula</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($horariosActuales as $horario)
                                    <tr id="horario-{{ $horario['id'] }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $horario['asignatura'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span id="docente-{{ $horario['id'] }}">{{ $horario['docente'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span id="dia-{{ $horario['id'] }}" class="capitalize">{{ $horario['dia'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span id="hora-{{ $horario['id'] }}">{{ $horario['hora_inicio'] }} - {{ $horario['hora_fin'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span id="aula-{{ $horario['id'] }}">{{ $horario['aula'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="abrirModalEdicion({{ json_encode($horario) }})" 
                                                    class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                                Simular
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resultados de simulación -->
        <div id="resultados-simulacion" class="hidden mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resultados de Simulación</h3>
            <div id="contenido-resultados"></div>
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="modal-edicion" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Simular Cambio
                </h3>
                <button onclick="cerrarModalEdicion()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Asignatura:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-asignatura"></p>
                </div>

                <div class="mb-6">
                    <label for="tipo-cambio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de cambio:
                    </label>
                    <select id="tipo-cambio" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccionar tipo</option>
                        <option value="docente">Cambiar Docente</option>
                        <option value="dia">Cambiar Día</option>
                        <option value="hora">Cambiar Hora</option>
                        <option value="aula">Cambiar Aula</option>
                    </select>
                </div>

                <div id="selector-nuevo-valor" class="mb-6 hidden">
                    <label for="nuevo-valor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nuevo valor:
                    </label>
                    <select id="nuevo-valor" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button onclick="cerrarModalEdicion()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancelar
                    </button>
                    <button onclick="agregarCambio()" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Agregar Cambio
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let horarioActual = null;
        let cambiosPendientes = [];
        let resultadosSimulacion = null;

        // Datos para los selectores
        const docentes = @json($docentes);
        const aulas = @json($aulas);
        const dias = @json($dias);
        const horas = @json($horas);

        function abrirModalEdicion(horario) {
            horarioActual = horario;
            document.getElementById('modal-asignatura').textContent = horario.asignatura;
            document.getElementById('tipo-cambio').value = '';
            document.getElementById('selector-nuevo-valor').classList.add('hidden');
            document.getElementById('modal-edicion').classList.remove('hidden');
        }

        function cerrarModalEdicion() {
            document.getElementById('modal-edicion').classList.add('hidden');
            horarioActual = null;
        }

        // Manejar cambio de tipo
        document.getElementById('tipo-cambio').addEventListener('change', function() {
            const tipo = this.value;
            const selectorDiv = document.getElementById('selector-nuevo-valor');
            const nuevoValorSelect = document.getElementById('nuevo-valor');
            
            if (tipo) {
                selectorDiv.classList.remove('hidden');
                nuevoValorSelect.innerHTML = '<option value="">Seleccionar...</option>';
                
                let opciones = [];
                switch (tipo) {
                    case 'docente':
                        opciones = docentes.map(d => ({
                            value: d.id,
                            text: d.persona.nombre,
                            selected: d.id == horarioActual.docente_id
                        }));
                        break;
                    case 'aula':
                        opciones = aulas.map(a => ({
                            value: a.id,
                            text: `Aula ${a.numero_aula}`,
                            selected: a.id == horarioActual.aula_id
                        }));
                        break;
                    case 'dia':
                        opciones = dias.map(d => ({
                            value: d.id,
                            text: d.descripcion.charAt(0).toUpperCase() + d.descripcion.slice(1),
                            selected: d.id == horarioActual.dia_id
                        }));
                        break;
                    case 'hora':
                        opciones = horas.map(h => ({
                            value: h.id,
                            text: `${h.hora_inicio.substring(0,5)} - ${h.hora_fin.substring(0,5)}`,
                            selected: h.id == horarioActual.hora_id
                        }));
                        break;
                }
                
                opciones.forEach(opcion => {
                    const option = document.createElement('option');
                    option.value = opcion.value;
                    option.textContent = opcion.text;
                    if (opcion.selected) {
                        option.textContent += ' (Actual)';
                        option.disabled = true;
                    }
                    nuevoValorSelect.appendChild(option);
                });
            } else {
                selectorDiv.classList.add('hidden');
            }
        });

        function agregarCambio() {
            const tipo = document.getElementById('tipo-cambio').value;
            const nuevoValor = document.getElementById('nuevo-valor').value;
            
            if (!tipo || !nuevoValor) {
                alert('Por favor, selecciona el tipo de cambio y el nuevo valor.');
                return;
            }

            // Verificar si ya existe un cambio para este horario y tipo
            const indiceExistente = cambiosPendientes.findIndex(c => 
                c.horario_id === horarioActual.id && c.tipo === tipo
            );

            const cambio = {
                horario_id: horarioActual.id,
                tipo: tipo,
                nuevo_valor: nuevoValor,
                asignatura: horarioActual.asignatura,
                valor_actual: obtenerValorActual(tipo),
                nuevo_texto: obtenerTextoNuevoValor(tipo, nuevoValor)
            };

            if (indiceExistente >= 0) {
                cambiosPendientes[indiceExistente] = cambio;
            } else {
                cambiosPendientes.push(cambio);
            }

            actualizarListaCambios();
            cerrarModalEdicion();
        }

        function obtenerValorActual(tipo) {
            switch (tipo) {
                case 'docente': return horarioActual.docente;
                case 'aula': return horarioActual.aula;
                case 'dia': return horarioActual.dia;
                case 'hora': return `${horarioActual.hora_inicio} - ${horarioActual.hora_fin}`;
                default: return '';
            }
        }

        function obtenerTextoNuevoValor(tipo, valor) {
            switch (tipo) {
                case 'docente':
                    const docente = docentes.find(d => d.id == valor);
                    return docente ? docente.persona.nombre : '';
                case 'aula':
                    const aula = aulas.find(a => a.id == valor);
                    return aula ? `Aula ${aula.numero_aula}` : '';
                case 'dia':
                    const dia = dias.find(d => d.id == valor);
                    return dia ? dia.descripcion.charAt(0).toUpperCase() + dia.descripcion.slice(1) : '';
                case 'hora':
                    const hora = horas.find(h => h.id == valor);
                    return hora ? `${hora.hora_inicio.substring(0,5)} - ${hora.hora_fin.substring(0,5)}` : '';
                default: return '';
            }
        }

        function actualizarListaCambios() {
            const container = document.getElementById('cambios-pendientes');
            
            if (cambiosPendientes.length === 0) {
                container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No hay cambios simulados</p>';
                document.getElementById('btn-simular').disabled = true;
                document.getElementById('btn-aplicar').disabled = true;
            } else {
                container.innerHTML = cambiosPendientes.map((cambio, index) => `
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-900 dark:text-white">${cambio.asignatura}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    ${cambio.tipo.charAt(0).toUpperCase() + cambio.tipo.slice(1)}: 
                                    ${cambio.valor_actual} → ${cambio.nuevo_texto}
                                </p>
                            </div>
                            <button onclick="eliminarCambio(${index})" class="text-red-500 hover:text-red-700 ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('btn-simular').disabled = false;
                document.getElementById('btn-aplicar').disabled = true;
            }
        }

        function eliminarCambio(index) {
            cambiosPendientes.splice(index, 1);
            actualizarListaCambios();
        }

        function limpiarCambios() {
            cambiosPendientes = [];
            resultadosSimulacion = null;
            actualizarListaCambios();
            document.getElementById('resultados-simulacion').classList.add('hidden');
            
            // Limpiar estilos de la tabla
            document.querySelectorAll('[id^="horario-"]').forEach(row => {
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition';
            });
        }

        async function procesarSimulacion() {
            if (cambiosPendientes.length === 0) return;

            const boton = document.getElementById('btn-simular');
            const textoOriginal = boton.innerHTML;
            boton.innerHTML = 'Simulando...';
            boton.disabled = true;

            try {
                const response = await fetch('{{ route("horarios.procesar-simulacion") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cambios: cambiosPendientes
                    })
                });

                const data = await response.json();
                resultadosSimulacion = data;
                mostrarResultadosSimulacion(data);
                
                if (data.conflictos.length === 0) {
                    document.getElementById('btn-aplicar').disabled = false;
                }

            } catch (error) {
                alert('Error al procesar la simulación: ' + error.message);
            } finally {
                boton.innerHTML = textoOriginal;
                boton.disabled = false;
            }
        }

        function mostrarResultadosSimulacion(data) {
            const container = document.getElementById('contenido-resultados');
            const resultadosDiv = document.getElementById('resultados-simulacion');
            
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Cambios</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">${data.total_cambios}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400 font-medium">Cambios Válidos</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">${data.cambios_procesados}</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">Conflictos</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300">${data.total_conflictos}</p>
                    </div>
                </div>
            `;

            if (data.conflictos.length > 0) {
                html += `
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-3">Conflictos Detectados</h4>
                        <div class="space-y-2">
                            ${data.conflictos.map(conflicto => `
                                <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                                    <p class="font-semibold text-sm text-red-900 dark:text-red-100">${conflicto.asignatura}</p>
                                    <p class="text-xs text-red-700 dark:text-red-300">${conflicto.detalle}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;

                // Marcar filas con conflictos
                data.conflictos.forEach(conflicto => {
                    const fila = document.getElementById(`horario-${conflicto.horario_id}`);
                    if (fila) {
                        fila.className = 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700';
                    }
                });
            }

            if (data.cambios_validos.length > 0) {
                html += `
                    <div>
                        <h4 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-3">Cambios Válidos</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Los siguientes cambios se pueden aplicar sin conflictos:
                        </p>
                        <div class="space-y-2">
                            ${data.cambios_validos.map(cambio => {
                                const cambioInfo = cambiosPendientes.find(c => 
                                    c.horario_id === cambio.horario_id && c.tipo === cambio.tipo
                                );
                                return `
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                                        <p class="font-semibold text-sm text-green-900 dark:text-green-100">${cambioInfo.asignatura}</p>
                                        <p class="text-xs text-green-700 dark:text-green-300">
                                            ${cambioInfo.tipo.charAt(0).toUpperCase() + cambioInfo.tipo.slice(1)}: 
                                            ${cambioInfo.valor_actual} → ${cambioInfo.nuevo_texto}
                                        </p>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;

                // Marcar filas válidas
                data.cambios_validos.forEach(cambio => {
                    const fila = document.getElementById(`horario-${cambio.horario_id}`);
                    if (fila) {
                        fila.className = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700';
                    }
                });
            }

            container.innerHTML = html;
            resultadosDiv.classList.remove('hidden');
        }

        async function aplicarCambios() {
            if (!resultadosSimulacion || resultadosSimulacion.cambios_validos.length === 0) return;

            if (!confirm('¿Estás seguro de que deseas aplicar estos cambios? Esta acción modificará los horarios oficiales.')) {
                return;
            }

            const boton = document.getElementById('btn-aplicar');
            const textoOriginal = boton.innerHTML;
            boton.innerHTML = 'Aplicando...';
            boton.disabled = true;

            try {
                const response = await fetch('{{ route("horarios.aplicar-simulacion") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cambios: resultadosSimulacion.cambios_validos
                    })
                });

                if (response.ok) {
                    // Recargar la página para mostrar los cambios aplicados
                    window.location.reload();
                } else {
                    throw new Error('Error al aplicar los cambios');
                }

            } catch (error) {
                alert('Error al aplicar los cambios: ' + error.message);
                boton.innerHTML = textoOriginal;
                boton.disabled = false;
            }
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modal-edicion').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalEdicion();
            }
        });
    </script>
</x-layouts.app>
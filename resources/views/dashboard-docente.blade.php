<x-layouts.app :title="__('Dashboard-Docente')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <!-- Header Bienvenida -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100">
                        Rol: {{ Auth::user()->rol->nombre ?? 'Docente' }} <br>
                        Código: {{ Auth::user()->persona->docente->codigo ?? 'N/A' }} <br>
                        Email: {{ Auth::user()->email }}
                    </p>
                    <p class="text-blue-100 text-sm mt-2">
                        Último acceso: {{ Auth::user()->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-full p-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas rápidas -->
        <div class="grid gap-6 md:grid-cols-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Asignaturas asignadas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\GrupoAsignatura::where('docente_id', Auth::user()->persona->docente->id)->count() }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Grupos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\GrupoAsignatura::where('docente_id', Auth::user()->persona->docente->id)->distinct('grupo_id')->count('grupo_id') }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aulas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\Horario::whereHas('docentes', fn($q) => $q->where('docente_id', Auth::user()->persona->docente->id))->distinct('aula_id')->count('aula_id') }}
                </p>
            </div>
            <a href="{{ route('docentes.mi-horario') }}" class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-md p-6 hover:shadow-lg transition flex items-center justify-center">
                <div class="text-center text-white">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-semibold">Ver Mi Horario</p>
                </div>
            </a>
        </div>

        <!-- Calendario de horarios -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Calendario de Clases</h2>
            <div id="calendar" class="w-full h-[500px]"></div>
        </div>

        <!-- Modal de asistencia -->
        <div id="modal-asistencia" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Registrar Asistencia
                        </h3>
                        <button type="button" onclick="cerrarModalAsistencia()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 
                            rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-asistencia">
                            ✕
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Clase seleccionada: <span id="asistencia-titulo" class="font-medium"></span>
                        </p>
                        <form id="form-asistencia" action="{{ route('asistencias.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="horario_id" id="asistencia-horario-id">

                            <div class="flex gap-4">
                                <button type="submit" name="estado" value="presente"
                                    class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none 
                                    focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Presente
                                </button>
                                <button type="submit" name="estado" value="ausente"
                                    class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none 
                                    focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Ausente
                                </button>
                                <button type="submit" name="estado" value="justificado"
                                    class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none 
                                    focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Justificado
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
            // Horarios base enviados desde el controlador
            let horarios = @json($horarios);    //no tacar
            console.log(horarios);

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    let eventos = [];

                    horarios.forEach(h => {
                        // Mapeo de días de semana a índice JS (0=domingo, 1=lunes, ..., 6=sábado)
                        const dias = {
                            'lunes':0, 'martes':1,'miércoles':2, 'jueves':3, 'viernes':4, 'sábado':5,'domingo':6
                        };
                        let indiceDia = dias[h.dia_semana.toLowerCase()];

                        // Rango del semestre
                        let inicio = new Date(h.semestre_inicio);
                        let fin    = new Date(h.semestre_fin);

                        // Iterar día por día dentro del semestre
                        let fecha = new Date(inicio);
                        while (fecha <= fin) {
                            if (fecha.getDay() === indiceDia) {
                                let fechaStr = fecha.toISOString().split('T')[0];
                                eventos.push({
                                    id: h.id,
                                    title: h.title,
                                    start: fechaStr + 'T' + h.hora_inicio,
                                    end: fechaStr + 'T' + h.hora_fin
                                });
                            }
                            fecha.setDate(fecha.getDate() + 1);
                        }
                    });

                    successCallback(eventos);
                },
                eventClick: function(info) {
                    // Aquí puedes abrir un modal Flowbite para marcar asistencia
                    abrirModalAsistencia(info.event);
                }
            });

            calendar.render();

        });

        document.addEventListener('DOMContentLoaded', function() {

        });

        function abrirModalAsistencia(event) {
            // Pasar datos al modal
            document.getElementById('asistencia-titulo').textContent = event.title;
            document.getElementById('asistencia-horario-id').value = event.id;

            const modal = document.getElementById('modal-asistencia');
            modal.classList.remove('hidden');
        }

        function cerrarModalAsistencia() {
            document.getElementById('modal-asistencia').classList.add('hidden');
        }
    </script>
</x-layouts.app>
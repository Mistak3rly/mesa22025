<x-layouts.app :title="__('Horario del Docente')">
    <div class="max-w-7xl mx-auto">
        <!-- Header con información del docente -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Horario del Docente</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        <span class="font-semibold">Nombre:</span> {{ $docente->persona->nombre }}<br>
                        <span class="font-semibold">Código:</span> {{ $docente->codigo }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="cambiarVista('semanal')" id="btn-semanal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Vista Semanal
                    </button>
                    <button onclick="cambiarVista('vertical')" id="btn-vertical" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Vista por Día
                    </button>
                    <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Vista Semanal (Tabla Horizontal) -->
        <div id="vista-semanal" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-semibold">
                                Hora
                            </th>
                            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia)
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-sm font-semibold">
                                    {{ $dia }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="horario-tbody">
                        <!-- Se llenará dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista Vertical (Por Día) -->
        <div id="vista-vertical" class="hidden space-y-6">
            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $index => $dia)
                @php
                    $diasSinAcento = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                    $diaSinAcento = $diasSinAcento[$index];
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-600 text-white px-6 py-4 font-semibold text-lg">
                        {{ $dia }}
                    </div>
                    <div class="p-6 space-y-3" id="dia-{{ $diaSinAcento }}">
                        <!-- Se llenará dinámicamente -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        const horarios = @json($horarios ?? []);
        
        function cambiarVista(tipo) {
            const semanal = document.getElementById('vista-semanal');
            const vertical = document.getElementById('vista-vertical');
            const btnSemanal = document.getElementById('btn-semanal');
            const btnVertical = document.getElementById('btn-vertical');

            if (tipo === 'semanal') {
                semanal.classList.remove('hidden');
                vertical.classList.add('hidden');
                btnSemanal.classList.remove('bg-gray-200', 'text-gray-700');
                btnSemanal.classList.add('bg-blue-600', 'text-white');
                btnVertical.classList.remove('bg-blue-600', 'text-white');
                btnVertical.classList.add('bg-gray-200', 'text-gray-700');
            } else {
                semanal.classList.add('hidden');
                vertical.classList.remove('hidden');
                btnVertical.classList.remove('bg-gray-200', 'text-gray-700');
                btnVertical.classList.add('bg-blue-600', 'text-white');
                btnSemanal.classList.remove('bg-blue-600', 'text-white');
                btnSemanal.classList.add('bg-gray-200', 'text-gray-700');
            }
        }

        function generarHorarioSemanal() {
            const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            const horas = {};
            
            // Agrupar horarios por hora y día
            horarios.forEach(h => {
                const hora = h.hora_inicio + ' - ' + h.hora_fin;
                if (!horas[hora]) {
                    horas[hora] = {};
                    dias.forEach(d => horas[hora][d] = null);
                }
                horas[hora][h.dia_semana] = h;
            });

            const tbody = document.getElementById('horario-tbody');
            tbody.innerHTML = '';

            Object.keys(horas).sort().forEach(hora => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
                tr.innerHTML = `
                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap bg-gray-50 dark:bg-gray-700">
                        ${hora}
                    </td>
                `;

                dias.forEach(dia => {
                    const horario = horas[hora][dia];
                    const td = document.createElement('td');
                    td.className = 'border border-gray-300 dark:border-gray-600 px-2 py-3 text-center';
                    
                    if (horario) {
                        const asignatura = horario.title.split(' - ')[0];
                        const aula = horario.title.split(' - ')[1];
                        td.innerHTML = `
                            <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3 text-sm hover:shadow-md transition">
                                <div class="font-semibold text-blue-900 dark:text-blue-100">${asignatura}</div>
                                <div class="text-xs text-blue-700 dark:text-blue-300 mt-1">${aula}</div>
                            </div>
                        `;
                    } else {
                        td.innerHTML = '<span class="text-gray-400 text-xs">-</span>';
                    }
                    
                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });
        }

        function generarHorarioVertical() {
            const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            
            dias.forEach(dia => {
                const container = document.getElementById(`dia-${dia}`);
                const horariosDelDia = horarios.filter(h => h.dia_semana === dia);
                
                if (horariosDelDia.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-sm italic">Sin clases programadas</p>';
                } else {
                    container.innerHTML = horariosDelDia.map(h => {
                        const asignatura = h.title.split(' - ')[0];
                        const aula = h.title.split(' - ')[1];
                        return `
                            <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-600 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="font-semibold text-lg text-gray-900 dark:text-white">${asignatura}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">${aula}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                            ${h.hora_inicio}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            ${h.hora_fin}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            });
        }

        // Inicializar vistas
        document.addEventListener('DOMContentLoaded', function() {
            generarHorarioSemanal();
            generarHorarioVertical();
        });
    </script>

    <style>
        @media print {
            button, .no-print {
                display: none !important;
            }
            .bg-blue-600 {
                background-color: #2563eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bg-blue-100 {
                background-color: #dbeafe !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</x-layouts.app>

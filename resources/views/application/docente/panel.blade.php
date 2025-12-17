<x-layouts.app :title="__('Mi Horario')">
    <div class="max-w-7xl mx-auto">
        <!-- Header con información del docente -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md p-6 mb-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Mi Horario Semanal</h2>
                    <p class="text-blue-100">
                        <span class="font-semibold">Docente:</span> {{ Auth::user()->persona->nombre }}<br>
                        <span class="font-semibold">Código:</span> {{ Auth::user()->persona->docente->codigo }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-lg p-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Clases</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-clases">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Aulas Diferentes</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-aulas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Horas Semanales</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-horas">0</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('asistencias.index') }}" class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-md p-4 hover:shadow-lg transition flex items-center justify-center group">
                <div class="text-center text-white">
                    <svg class="w-10 h-10 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <p class="font-semibold text-sm">Registrar Asistencia</p>
                </div>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Horario de Clases</h3>
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

            <!-- Vista Semanal (Tabla Horizontal) -->
            <div id="vista-semanal" class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Hora
                            </th>
                            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia)
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">
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

            <!-- Vista Vertical (Por Día) -->
            <div id="vista-vertical" class="hidden space-y-6">
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $index => $dia)
                    @php
                        $diasSinAcento = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                        $diaSinAcento = $diasSinAcento[$index];
                    @endphp
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                        <div class="bg-blue-600 text-white px-4 py-3 font-semibold">
                            {{ $dia }}
                        </div>
                        <div class="p-4 space-y-2" id="dia-{{ $diaSinAcento }}">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                @endforeach
            </div>
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

        function estaEnCurso(horario) {
            const ahora = new Date();
            const diaActual = ahora.getDay(); // 0=domingo, 1=lunes, etc.
            const horaActual = ahora.getHours() * 60 + ahora.getMinutes(); // minutos desde medianoche
            
            // Mapear días
            const diasSemana = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
            const diaHorario = horario.dia_semana.toLowerCase();
            
            // Normalizar nombres de días (sin acentos)
            const diaHorarioNormalizado = diaHorario
                .replace('miércoles', 'miercoles')
                .replace('sábado', 'sabado');
            
            const diasNormalizados = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            const diaHorarioIndex = diasNormalizados.indexOf(diaHorarioNormalizado);
            
            if (diaActual !== diaHorarioIndex) return false;
            
            // Convertir horas del horario a minutos
            const [horaIni, minIni] = horario.hora_inicio.split(':').map(Number);
            const [horaFin, minFin] = horario.hora_fin.split(':').map(Number);
            const inicioMinutos = horaIni * 60 + minIni;
            const finMinutos = horaFin * 60 + minFin;
            
            // Verificar si está en el rango (con 30 minutos de margen antes y después)
            return horaActual >= (inicioMinutos - 30) && horaActual <= (finMinutos + 30);
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
                tr.innerHTML = `
                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        ${hora}
                    </td>
                `;

                dias.forEach(dia => {
                    const horario = horas[hora][dia];
                    const td = document.createElement('td');
                    td.className = 'border border-gray-300 dark:border-gray-600 px-2 py-3 text-center';
                    
                    if (horario) {
                        const enCurso = estaEnCurso(horario);
                        const claseBase = enCurso ? 'bg-green-100 dark:bg-green-900 border-2 border-green-500' : 'bg-blue-100 dark:bg-blue-900';
                        const textoBase = enCurso ? 'text-green-900 dark:text-green-100' : 'text-blue-900 dark:text-blue-100';
                        const textoSecundario = enCurso ? 'text-green-700 dark:text-green-300' : 'text-blue-700 dark:text-blue-300';
                        
                        td.innerHTML = `
                            <div class="${claseBase} rounded-lg p-2 text-sm relative">
                                ${enCurso ? '<div class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold animate-pulse">EN CURSO</div>' : ''}
                                <div class="font-semibold ${textoBase}">${horario.title.split(' - ')[0]}</div>
                                <div class="text-xs ${textoSecundario} mt-1">${horario.title.split(' - ')[1]}</div>
                                ${enCurso ? '<div class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">Clase Activa</div>' : ''}
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
                    container.innerHTML = '<p class="text-gray-500 text-sm">Sin clases programadas</p>';
                } else {
                    container.innerHTML = horariosDelDia.map(h => {
                        const enCurso = estaEnCurso(h);
                        const claseBase = enCurso ? 'bg-green-50 dark:bg-green-900 border-l-4 border-green-500' : 'bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-600';
                        const textoHora = enCurso ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400';
                        
                        return `
                            <div class="${claseBase} rounded p-3 relative">
                                ${enCurso ? '<div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold animate-pulse">EN CURSO</div>' : ''}
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">${h.title.split(' - ')[0]}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">${h.title.split(' - ')[1]}</div>
                                        ${enCurso ? '<div class="text-sm text-green-600 dark:text-green-400 font-semibold mt-2">Clase Activa - Puedes marcar asistencia</div>' : ''}
                                    </div>
                                    <div class="text-sm font-medium ${textoHora}">
                                        ${h.hora_inicio} - ${h.hora_fin}
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            });
        }

        function calcularEstadisticas() {
            // Total de clases
            document.getElementById('total-clases').textContent = horarios.length;
            
            // Aulas diferentes
            const aulasUnicas = new Set(horarios.map(h => h.title.split(' - ')[1]));
            document.getElementById('total-aulas').textContent = aulasUnicas.size;
            
            // Calcular horas semanales
            let totalMinutos = 0;
            horarios.forEach(h => {
                const [horaIni, minIni] = h.hora_inicio.split(':').map(Number);
                const [horaFin, minFin] = h.hora_fin.split(':').map(Number);
                const minutos = (horaFin * 60 + minFin) - (horaIni * 60 + minIni);
                totalMinutos += minutos;
            });
            const totalHoras = Math.floor(totalMinutos / 60);
            const minutosRestantes = totalMinutos % 60;
            document.getElementById('total-horas').textContent = `${totalHoras}h ${minutosRestantes}m`;
        }

        // Función para actualizar el horario en tiempo real
        function actualizarHorario() {
            generarHorarioSemanal();
            generarHorarioVertical();
        }

        // Inicializar vistas
        document.addEventListener('DOMContentLoaded', function() {
            calcularEstadisticas();
            actualizarHorario();
            
            // Actualizar cada minuto para mostrar clases en curso
            setInterval(actualizarHorario, 60000); // 60 segundos
        });
    </script>

    <style>
        @media print {
            button {
                display: none !important;
            }
            .bg-blue-600 {
                background-color: #2563eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</x-layouts.app>
<x-layouts.app :title="__('Aulas Disponibles')">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Verificación de Aulas Disponibles</h1>
                @if(isset($dia) && isset($hora))
                    <p class="text-gray-600">{{ $dia->descripcion }} - {{ $hora->hora_inicio->format('H:i') }} a {{ $hora->hora_fin->format('H:i') }}</p>
                @endif
            </div>
            <a href="{{ route('reportes.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Volver
            </a>
        </div>

        @if(isset($aulasDisponibles))
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Aulas Disponibles</p>
                            <p class="text-3xl font-bold">{{ $aulasDisponibles->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm">Aulas Ocupadas</p>
                            <p class="text-3xl font-bold">{{ $aulasOcupadasDetalle->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Aulas</p>
                            <p class="text-3xl font-bold">{{ $todasLasAulas->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aulas Disponibles -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    Aulas Disponibles
                </h2>
                
                @if($aulasDisponibles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($aulasDisponibles as $aula)
                            <div class="border-2 border-green-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-2xl font-bold text-gray-800">{{ $aula->numero_aula }}</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Disponible</span>
                                </div>
                                <p class="text-sm text-gray-600">Capacidad: {{ $aula->capacidad }} personas</p>
                                <p class="text-sm text-gray-600">Tipo: {{ $aula->tipo }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No hay aulas disponibles en este horario</p>
                @endif
            </div>

            <!-- Aulas Ocupadas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    Aulas Ocupadas
                </h2>
                
                @if($aulasOcupadasDetalle->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignatura</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($aulasOcupadasDetalle as $horario)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Aula {{ $horario->aula->numero_aula }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $horario->asignatura->descripcion }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $horario->aula->capacidad }} personas
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full">
                                                {{ $horario->aula->tipo }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Todas las aulas están disponibles</p>
                @endif
            </div>
        @endif
    </div>
</x-layouts.app>

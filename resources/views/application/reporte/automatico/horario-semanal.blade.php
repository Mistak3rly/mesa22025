<x-layouts.app :title="__('Horario Semanal')">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Horario Semanal</h1>
                <p class="text-gray-600">Semestre: {{ $semestre->descripcion }}</p>
            </div>
            <a href="{{ route('reportes.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Volver
            </a>
        </div>

        @foreach($horariosPorDia as $dia => $horarios)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">{{ $dia }}</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignatura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($horarios as $horario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $horario->hora->hora_inicio->format('H:i') }} - {{ $horario->hora->hora_fin->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $horario->asignatura->descripcion }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            Aula {{ $horario->aula->numero_aula }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.app>

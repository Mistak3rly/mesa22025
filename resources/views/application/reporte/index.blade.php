<x-layouts.app :title="__('Seccion Reportes')">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="container mx-auto py-6">
            <h2 class="text-2xl font-bold mb-6">📊 Panel de Reportes</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Reporte de Docentes -->
                <div class="p-4 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Docentes</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('reportes.docentes.excel') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Exportar a Excel
                        </a>
                        <a href="{{ route('reportes.docentes.pdf') }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Exportar a PDF
                        </a>
                    </div>
                </div>

                <!-- Reporte de Materias -->
                <div class="p-4 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Materias</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('reportes.materias.excel') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Exportar a Excel
                        </a>
                        <a href="{{ route('reportes.materias.pdf') }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Exportar a PDF
                        </a>
                    </div>
                </div>

            </div>

            <!-- 🔹 Sección futura para reportes dinámicos -->
            <div class="mt-10 p-6 bg-gray-100 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Reportes Personalizados</h3>
                <p class="text-gray-600 mb-4">Próximamente podrás seleccionar los datos que quieres exportar.</p>
                <form method="GET" action="">
                    <div class="flex gap-4">
                        <select name="tipo" class="border rounded px-3 py-2">
                            <option value="docentes">Docentes</option>
                            <option value="estudiantes">Estudiantes</option>
                            <option value="materias">Materias</option>
                        </select>
                        <select name="formato" class="border rounded px-3 py-2">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Exportar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
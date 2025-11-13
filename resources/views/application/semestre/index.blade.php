<x-layouts.app :title="__('Lista de Semestres')">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 space-y-4">

        <!-- Contenedor de botones -->
        <div class="flex flex-wrap gap-4 mb-4">
            <!-- Botón para añadir asignatura -->
            <button id="open-add-modal-button" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Añadir Semestre
            </button>
            <!-- Botón para mostrar asignaturas eliminadas -->
            <form action="" method="GET">
                @csrf
                <button type="submit"class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Semestres Eliminados
                </button>
            </form>
        </div>
        <!-- Modal añadir asignatura -->
        <div id="add-subject-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Ingrese el nuevo Semestre.
                        </h3>
                        <button id="close-add-modal-button" type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 max-h-[70vh] overflow-y-auto">
                        <form class="space-y-4" action="{{ route('semestres.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sigla del Semestre</label>
                                <input type="text" name="descripcion" id="descripcion" placeholder="SEM-001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha final</label>
                                <input type="date" name="fecha_fin" id="fecha_fin"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Añadir Asignatura</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->

        <!-- Tabla Principal -->
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Lista de Total de Semestres
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                    Historial de los semestres creados.
                </p>
            </caption>
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Semestre</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($semestres as $semestre)
                <tr id="row-{{ $semestre->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ $semestre->id }}</td>

                    <!-- Campo editable -->
                    <td class="px-6 py-4">
                        <span class="descripcion-text">{{ $semestre->descripcion }}</span>
                        <form method="POST" action="{{ route('semestres.update', $semestre->id) }}" class="form-editar hidden">
                            @csrf
                            @method('PUT')
                            <input type="text" name="descripcion" value="{{ $semestre->descripcion }}"
                                class="descripcion-input w-full border rounded px-2 py-1 text-gray-900 dark:bg-gray-600 dark:text-white">

                            
                        </form>
                    </td>

                    <!-- Botones -->
                    <td class="px-6 py-4 text-right">
                        <button type="button" class="editar-btn text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                            Editar
                        </button>
                        <button type="submit" class="guardar-btn hidden text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700">
                            Guardar
                        </button>
                        <button type="button" class="cancelar-btn hidden text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700">
                            Cancelar
                        </button>

                        <!-- Formulario de eliminación -->
                        <form method="POST" action="{{ route('semestres.destroy', $semestre->id) }}" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta asignatura?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <!-- Script -->
    <script>
        const openAddModalButton = document.getElementById('open-add-modal-button');
        const addModal = document.getElementById('add-subject-modal');
        const closeAddModalButton = document.getElementById('close-add-modal-button');

        openAddModalButton.addEventListener('click', () => {
            addModal.classList.remove('hidden');
            addModal.classList.add('flex');
        });

        closeAddModalButton.addEventListener('click', () => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.editar-btn').forEach(boton => {
                boton.addEventListener('click', function() {
                    const fila = this.closest('tr');
                    fila.querySelector('.descripcion-text').classList.add('hidden');
                    fila.querySelector('.form-editar').classList.remove('hidden');
                    fila.querySelector('.editar-btn').classList.add('hidden');
                    fila.querySelector('.guardar-btn').classList.remove('hidden');
                    fila.querySelector('.cancelar-btn').classList.remove('hidden');
                });
            });

            document.querySelectorAll('.cancelar-btn').forEach(boton => {
                boton.addEventListener('click', function() {
                    const fila = this.closest('tr');
                    fila.querySelector('.form-editar').classList.add('hidden');
                    fila.querySelector('.descripcion-text').classList.remove('hidden');
                    fila.querySelector('.editar-btn').classList.remove('hidden');
                    fila.querySelector('.guardar-btn').classList.add('hidden');
                    fila.querySelector('.cancelar-btn').classList.add('hidden');
                });
            });

            document.querySelectorAll('.guardar-btn').forEach(boton => {
                boton.addEventListener('click', function() {
                    const fila = this.closest('tr');
                    fila.querySelector('.form-editar').submit();
                });
            });
        });

    </script>
</x-layouts.app>
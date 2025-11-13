<x-layouts.app :title="__('Lista de Horarios')">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <!-- Contenedor de botones -->
        <div class="flex flex-wrap gap-4 mb-4">
            <!-- Modal toggle -->
            <button id="open-modal" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Nuevo Horario
            </button>

            <!-- Botón para mostrar los administrativos eliminados -->
            <form action="" method="GET">
                @csrf
                <button type="submit"class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Horarios Eliminados
                </button>
            </form>
        </div>

        <!-- Main modal -->
        <div id="add-teacher-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Código del docente para buscar horarios dispopnibles.
                        </h3>
                        <button id="modal-close-button" type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 max-h-[70vh] overflow-y-auto">
                        <form class="space-y-4" action="{{ route('horarios.create') }}" method="GET">
                            @csrf
                            <div> 
                                <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Docente</label>
                                <input type="text" name="codigo" id="codigo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="12345" required />
                            </div>

                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Buscar Horarios Docente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->

        <!-- Tabla Principal -->
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Lista de horarios totales
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                    Todos los docentes que actualmente estan dando alguna clase en el semestre.
                </p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Dia</th>
                    <th scope="col" class="px-6 py-3">Aula</th>
                    <th scope="col" class="px-6 py-3">Asignatura</th>
                    <th scope="col" class="px-6 py-3">Hora</th>
                    <th scope="col" class="px-6 py-3">Docente</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($horarios as $horario)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Script -->
    <script>
        const openModalButton = document.getElementById('open-modal');
        const modal = document.getElementById('add-teacher-modal');
        const closeModalButton = document.getElementById('modal-close-button');

        openModalButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    </script>
</x-layouts.app>

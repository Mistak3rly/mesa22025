<x-layouts.app :title="__('Lista de Administrativos')">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <!-- Contenedor de botones -->
        <div class="flex flex-wrap gap-4 mb-4">
            <!-- Modal toggle -->
            <button id="open-modal" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Añadir Administrativo
            </button>

            <!-- Botón para mostrar los administrativos eliminados -->
            <form action="{{ route('administrativos.deleted-index') }}" method="GET">
                @csrf
                <button type="submit"class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Administrativos Eliminados
                </button>
            </form>
        </div>

        <!-- Main modal -->
        <div id="add-administrativo-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Ingrese los datos del nuevo administrativo
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
                        <form class="space-y-4" action="{{ route('administrativos.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="carnet" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Carnet</label>
                                <input type="text" name="carnet" id="carnet" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre y Apellido</label>
                                <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="sexo" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Género</label>
                                <select name="sexo" id="sexo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="M">M - Masculino</option>
                                    <option value="F">F - Femenino</option>
                                </select>

                                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefono</label>
                                <input type="number" name="telefono" id="telefono" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890" required />
                                
                                <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Direccion</label>
                                <input type="text" name="direccion" id="direccion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="fecha_ingreso" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                                
                                <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo</label>
                                <input type="text" name="codigo" id="codigo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="123456" required />
                                
                                <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
                                <input type="email" name="correo" id="correo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required />
                            </div>

                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Añadir Administrativo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->

        <!-- Tabla Principal -->
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Lista de Administrativos Activos
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                    Todos los administrativos activos.
                </p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Codigo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Carnet
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Telefono
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($administrativos as $administrativo)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">
                        {{ $administrativo->codigo ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $administrativo->persona->carnet ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $administrativo->persona->nombre ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $administrativo->persona->telefono ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('administrativos.show', $administrativo->id) }}" method="GET">
                            @csrf
                            <button type="submit"class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Ver
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
        const openModalButton = document.getElementById('open-modal');
        const modal = document.getElementById('add-administrativo-modal');
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

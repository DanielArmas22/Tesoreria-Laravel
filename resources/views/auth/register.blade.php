<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        {{-- @isset($role)
            <p>rol: {{ $role }}</p>
        @endisset --}}
        <form method="POST"
            action="
        @if (isset($role) && $role == 'padre') {{ route('register.padre') }}
        @else
        @if (isset($role) && $role == 'admin') 
        {{ route('registrate') }} 
        @else
            {{ route('register') }} @endif
        @endif
        ">
            @csrf
            <div>
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo Electronico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="email" />
            </div>
            @if (isset($role) && $role == 'admin')
                <div class="mt-4">
                    <x-label for="rol" value="{{ __('rol') }}" />
                    <x-input id="rol" class="block mt-1 w-full" type="text" name="rol" :value="old('rol')"
                        required autocomplete="rol" />
                </div>
            @endif
            @if (isset($role) && $role == 'padre')
                <div id="error-message" style="color: red;"></div>
                <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
                <div class="mt-4">
                    <x-label for="idEstudiante" value="{{ __('Codigo del Estudiante') }}" />
                    <x-input id="idEstudiante" class="block mt-1 w-full" type="number" name="idEstudiante"
                        :value="old('idEstudiante')" required autocomplete="idEstudiante" step="1" />
                </div>
                <button class=" mt-4 px-6 py-2 rounded-xl border-2 border-gray-500" id="btnEstudiante">buscar</button>
                <div id="success-message" style="color: green;"></div>
                <div id="estudiantes-list">
                </div>
            @endif

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    href="{{ route('login') }}">
                    {{ __('Ya tienes una cuenta?') }}
                </a>
                <x-button class="ms-4">
                    {{ __('Registrarme') }}
                </x-button>
            </div>
        </form>
        <script>
            btnEstudiante.addEventListener('click', function(event) {
                event.preventDefault();
                var idEstudiante = document.getElementById('idEstudiante').value;
                if (idEstudiante == '') {
                    document.getElementById('error-message').innerText = 'Ingrese un codigo de estudiante';
                    return;
                }
                var csrfToken = document.getElementById('csrf_token').value;
                fetch(window.location.origin + '/addEstudiante/' + idEstudiante, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            id: idEstudiante
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Maneja la respuesta del servidor
                        if (data.error) {
                            console.log('Error:', 'da');
                            document.getElementById('error-message').innerText = data.error;
                        } else {
                            console.log('Success:', data.message);
                            document.getElementById('error-message').innerText = '';
                            document.getElementById('success-message').innerText = '';
                            document.getElementById('success-message').innerText = data.message;
                            // Actualizar la tabla de estudiantes
                            actualizarListaEstudiantes(data.estudiantes);

                            // Actualiza la interfaz de usuario si es necesario
                        }
                    })
                    .catch(error => {
                        console.log('Error:', 'da2');
                        document.getElementById('error-message').innerText = error;
                    });
            });

            function actualizarListaEstudiantes(estudiantes) {
                var estudiantesList = document.getElementById('estudiantes-list');
                // Limpiar la lista existente
                estudiantesList.innerHTML = '';

                // Iterar sobre los estudiantes y agregarlos como inputs
                estudiantes.forEach(function(estudiante, index) {
                    // Crear un contenedor para los inputs del estudiante
                    var estudianteDiv = document.createElement('div');
                    estudianteDiv.classList.add('estudiante-item');

                    // Input oculto para el ID del estudiante
                    var inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'idEstudiantes[]';
                    inputId.value = estudiante.id;

                    // Mostrar el nombre del estudiante
                    var labelNombre = document.createElement('label');
                    labelNombre.textContent = 'Nombre: ' + estudiante.nombre;

                    // Mostrar el DNI del estudiante
                    var labelDNI = document.createElement('label');
                    labelDNI.textContent = 'DNI: ' + estudiante.dni;

                    // Agregar los elementos al contenedor
                    estudianteDiv.appendChild(inputId);
                    estudianteDiv.appendChild(labelNombre);
                    estudianteDiv.appendChild(document.createElement('br'));
                    estudianteDiv.appendChild(labelDNI);
                    estudianteDiv.appendChild(document.createElement('br'));

                    // Agregar el contenedor del estudiante a la lista
                    estudiantesList.appendChild(estudianteDiv);
                });
            }
        </script>
    </x-authentication-card>
</x-guest-layout>

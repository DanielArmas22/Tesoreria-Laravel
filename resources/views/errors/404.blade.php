<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes colorCycle {
            0% {
                background-color: rgba(200, 255, 200, 0.5);
            }

            25% {
                background-color: rgba(180, 240, 180, 0.5);
            }

            50% {
                background-color: rgba(160, 230, 160, 0.5);
            }

            75% {
                background-color: rgba(200, 255, 200, 0.5);
            }

            100% {
                background-color: rgba(180, 240, 180, 0.5);
            }
        }

        .psychedelic-bg {
            animation: colorCycle 10s infinite;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center psychedelic-bg">
    <style>
        #background {
            filter: brightness(120%) contrast(97%) hue-rotate(74deg) invert(3%) saturate(298%);
        }
    </style>
    <img id="background" class="absolute -left-20 top-0 max-w-[877px]"
        src="https://laravel.com/assets/img/welcome/background.svg" />
    <div class="text-center">
        <h1 class="text-9xl font-extrabold text-gray-800 drop-shadow-lg animate-pulse">
            404
        </h1>
        <p class="text-2xl font-semibold text-gray-800 mt-4">
            ¡Oops! No encontramos esta página...
        </p>
        <p class="text-lg text-gray-800 mt-2 animate-bounce flex gap-1">
            Parece que te perdiste en el espacio Sideral jeje.<img src="/img/profileTesoreria.jpg"
                alt="tesoreria-icon"class="h-6 w-auto lg:h-8">
        </p>
        <a href="{{ url('/') }}"
            class="mt-6 inline-block bg-gradient-to-r from-green-600 via-green-500 to-green-700 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-lg shadow-lg font-bold uppercase tracking-wider transition-all duration-300 transform hover:scale-105">
            Volver al inicio
        </a>
    </div>

</body>

</html>

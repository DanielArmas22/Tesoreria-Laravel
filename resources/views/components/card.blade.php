@props(['titulo', 'descripcion', 'ruta', 'accion', 'imagen'])
<article class="w-48">
    <div class="block rounded-lg bg-white shadow-secondary-1 dark:bg-surface-dark">
        <a href="#!">
            <img class="rounded-t-lg" src="{{ $imagen }}" alt="" />
        </a>
        <div class="p-6 text-surface dark:text-white text-center">
            <h5 class="mb-2 text-xl font-bold leading-tight">{{ $titulo }}</h5>
            <p class="mb-4 text-sm">
                {{ $descripcion }}
            </p>
            <a href="{{ route($ruta) }}">
                <button type="button"
                    class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
                    data-twe-ripple-init data-twe-ripple-color="light">
                    {{ $accion }}
                </button>
            </a>
        </div>
    </div>
</article>

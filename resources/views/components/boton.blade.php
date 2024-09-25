@props(['label' => '', 'ruta' => '', 'datos' => '', 'color' => 'primary'])
@php
    $buttonClass =
        'rounded flex items-center justify-center px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out  focus:outline-none focus:ring-0  motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong text-center ';
    $styles = [
        'primary' =>
            'bg-primary shadow-primary-3 hover:shadow-primary-2 hover:bg-primary-accent-300 focus:bg-primary-accent-300 active:bg-primary-600 focus:shadow-primary-2 active:shadow-primary-2',
        'secondary' =>
            'bg-secondary shadow-secondary-3 hover:shadow-secondary-2 hover:bg-secondary-accent-300 focus:bg-secondary-accent-300 active:bg-secondary-600 focus:shadow-secondary-2 active:shadow-secondary-2',
        'success' =>
            'bg-success shadow-success-3 hover:shadow-success-2 hover:bg-success-accent-300 focus:bg-success-accent-300 active:bg-success-600 focus:shadow-success-2 active:shadow-success-2',
        'danger' =>
            'bg-danger shadow-danger-3 hover:shadow-danger-2 hover:bg-danger-accent-300 focus:bg-danger-accent-300 active:bg-danger-600 focus:shadow-danger-2 active:shadow-danger-2',
        'warning' =>
            'bg-warning shadow-warning-3 hover:shadow-warning-2 hover:bg-warning-accent-300 focus:bg-warning-accent-300 active:bg-warning-600 focus:shadow-warning-2 active:shadow-warning-2',
        'dark' =>
            'bg-neutral-800 shadow-dark-3 hover:bg-neutral-700 hover:shadow-dark-2 focus:bg-neutral-700 focus:shadow-dark-2 active:bg-neutral-900 active:shadow-dark-2',
    ];
@endphp
<a class="{{ $buttonClass . ' ' . $styles[$color] }}"
    href="{{ !empty($datos) ? route($ruta, $datos) : route($ruta) }}">{{ $label }}</a>

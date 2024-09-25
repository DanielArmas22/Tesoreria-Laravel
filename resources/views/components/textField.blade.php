@props([
    'label' => '',
    'placeholder' => '',
    'name' => '',
    'message' => '',
    'valor' => '',
    'readonly' => 'false',
    'id' => '',
    'type' => 'text',
])
<div class="relative w-full" data-twe-input-wrapper-init>
    <input type="{{ $type }}"
        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-white dark:placeholder:text-neutral-300 dark:autofill:shadow-autofill dark:peer-focus:text-primary [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
        id="{{ $id }}" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ $valor }}"
        {{ $readonly == 'true' ? 'readonly' : '' }} />
    <label for="exampleFormControlInputText"
        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-400 dark:peer-focus:text-primary">
        <p>{{ $label }} </p>
    </label>
    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-6 absolute right-0 left-full">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
    </svg> --}}
</div>
@error($name)
    <span class="text-red-700 text-xs" role="">
        <i class="fa-solid fa-exclamation"></i>
        <strong>{{ $message }}</strong>
    </span>
@enderror

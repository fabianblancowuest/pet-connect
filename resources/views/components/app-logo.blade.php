@props([
    'sidebar' => false,
])

@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="PetConnect" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
            <x-app-logo-icon class="size-4" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="PetConnect" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
            <x-app-logo-icon class="size-4" />
        </x-slot>
    </flux:brand>
@endif

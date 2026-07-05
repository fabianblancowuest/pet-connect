<div>
    <div class="mb-6 flex items-center justify-between">
        <flux:heading size="xl" level="1">{{ __('Mis mascotas') }}</flux:heading>
        <div class="flex gap-2">
            <flux:select wire:model.live="statusFilter" class="w-36">
                <option value="">{{ __('Todas') }}</option>
                <option value="available">{{ __('Disponibles') }}</option>
                <option value="adopted">{{ __('Adoptadas') }}</option>
            </flux:select>
            <flux:button :href="route('rescuer.pets.create')" variant="primary" icon="plus" wire:navigate>
                {{ __('Nueva mascota') }}
            </flux:button>
        </div>
    </div>

    @if ($pets->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <flux:icon name="paw-print" class="mb-4 size-12 text-neutral-300 dark:text-neutral-600" />
            <flux:heading class="mb-2 text-lg">{{ __('No tenés mascotas registradas') }}</flux:heading>
            <flux:text class="mb-4">{{ __('Agregá la primera mascota de tu refugio.') }}</flux:text>
            <flux:button :href="route('rescuer.pets.create')" variant="primary" wire:navigate>
                {{ __('Agregar mascota') }}
            </flux:button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($pets as $pet)
                <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white transition hover:shadow-lg dark:border-neutral-700 dark:bg-zinc-800">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100 dark:bg-zinc-700">
                        @if ($pet->primaryImage)
                            <img src="{{ $pet->primaryImage->image_path }}" alt="{{ $pet->name }}" class="size-full object-cover transition group-hover:scale-105" />
                        @else
                            <div class="flex size-full items-center justify-center text-neutral-400">
                                <flux:icon name="image" class="size-12" />
                            </div>
                        @endif
                        <div class="absolute right-2 top-2">
                            <flux:badge size="sm" color="{{ $pet->status === 'available' ? 'emerald' : 'neutral' }}">
                                {{ $pet->status === 'available' ? __('Disponible') : __('Adoptada') }}
                            </flux:badge>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="mb-2">
                            <flux:heading class="text-base">{{ $pet->name }}</flux:heading>
                            <flux:text class="text-sm">
                                {{ $pet->species->name }} &middot; {{ $pet->breed?->name ?? __('Sin raza') }}
                            </flux:text>
                        </div>

                        <div class="flex items-center justify-between">
                            <flux:text class="text-xs text-neutral-400">
                                @if ($pet->age_years)
                                    {{ $pet->age_years }} {{ trans_choice('año|años', $pet->age_years) }}
                                @endif
                                @if ($pet->age_months)
                                    {{ $pet->age_months }} {{ trans_choice('mes|meses', $pet->age_months) }}
                                @endif
                            </flux:text>
                            <div class="flex gap-1">
                                <flux:button
                                    :href="route('rescuer.pets.edit', $pet)"
                                    variant="ghost"
                                    size="xs"
                                    icon="pencil"
                                    wire:navigate
                                />
                                <flux:button
                                    wire:click="deletePet({{ $pet->id }})"
                                    wire:confirm="{{ __('¿Eliminar esta mascota?') }}"
                                    variant="ghost"
                                    size="xs"
                                    icon="trash"
                                    class="text-red-500 hover:text-red-700"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $pets->links() }}
        </div>
    @endif
</div>

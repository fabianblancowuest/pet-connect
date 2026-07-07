<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl" level="1">{{ __('Todas las mascotas') }}</flux:heading>
        <div class="flex gap-2">
            <flux:input wire:model.live.debounce.300ms="search" :placeholder="__('Buscar...')" icon="magnifying-glass" class="w-44" />
            <flux:select wire:model.live="organizationFilter" class="w-44">
                <option value="">{{ __('Todos los refugios') }}</option>
                @foreach ($organizations as $org)
                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="statusFilter" class="w-36">
                <option value="">{{ __('Todas') }}</option>
                <option value="available">{{ __('Disponibles') }}</option>
                <option value="adopted">{{ __('Adoptadas') }}</option>
            </flux:select>
            <flux:button :href="route('admin.pets.create')" variant="primary" icon="plus" wire:navigate>
                {{ __('Nueva mascota') }}
            </flux:button>
        </div>
    </div>

    @if ($pets->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <flux:icon name="paw-print" class="mb-4 size-12 text-neutral-300 dark:text-neutral-600" />
            <flux:heading class="mb-2 text-lg">{{ __('No hay mascotas registradas') }}</flux:heading>
            <flux:text class="mb-4">{{ __('Agregá la primera mascota desde acá.') }}</flux:text>
            <flux:button :href="route('admin.pets.create')" variant="primary" wire:navigate>
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
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $pet->status === 'available' ? 'bg-emerald-600 text-white' : 'bg-zinc-700 text-white dark:bg-zinc-600' }}">
                                {{ $pet->status === 'available' ? __('Disponible') : __('Adoptada') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="mb-2">
                            <flux:heading class="text-base">{{ $pet->name }}</flux:heading>
                            <flux:text class="text-sm">
                                {{ $pet->species->name }} &middot; {{ $pet->breed?->name ?? __('Sin raza') }}
                            </flux:text>
                            @if ($pet->organization)
                                <flux:text class="text-xs text-neutral-400 mt-1">
                                    {{ $pet->organization->name }}
                                </flux:text>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <flux:text class="text-xs text-neutral-400">
                                @if ($pet->age_years)
                                    {{ $pet->age_years }} {{ trans_choice('año|años', $pet->age_years) }}
                                @endif
                                @if ($pet->age_months)
                                    {{ $pet->age_months }} {{ trans_choice('mes|es', $pet->age_months) }}
                                @endif
                            </flux:text>
                            <div class="flex gap-1">
                                <flux:button
                                    x-data
                                    x-on:click="$wire.showPreview({{ $pet->id }}).then(() => $dispatch('modal-show', { name: 'pet-preview' }))"
                                    variant="ghost"
                                    size="xs"
                                    icon="eye"
                                    :title="__('Vista previa')"
                                />
                                <flux:button
                                    :href="route('admin.pets.edit', $pet)"
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

    <flux:modal name="pet-preview" class="max-w-4xl">
        @if ($previewPet)
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="space-y-4"
                    x-data="{ selected: '{{ $previewPet->primaryImage?->image_path ?? '' }}' }">
                    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-neutral-100 dark:bg-zinc-700">
                        <img x-show="selected" :src="selected" alt="{{ $previewPet->name }}"
                            class="size-full object-cover" />
                        <div x-show="!selected"
                            class="flex size-full items-center justify-center text-neutral-400">
                            <flux:icon name="image" class="size-16" />
                        </div>
                    </div>

                    @if ($previewPet->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            @foreach ($previewPet->images as $image)
                                <div @click="selected = '{{ $image->image_path }}'"
                                    class="aspect-square size-20 shrink-0 cursor-pointer overflow-hidden rounded-lg bg-neutral-100 transition hover:opacity-80 dark:bg-zinc-700"
                                    :class="{ 'ring-2 ring-blue-500': selected === '{{ $image->image_path }}' }">
                                    <img src="{{ $image->image_path }}" alt="" class="size-full object-cover" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-6">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <flux:heading size="xl" level="2">{{ $previewPet->name }}</flux:heading>
                            <flux:text class="mt-1">
                                {{ $previewPet->species->name }} &middot; {{ $previewPet->breed?->name ?? __('Sin raza') }}
                            </flux:text>
                        </div>

                        <flux:badge size="sm"
                            color="{{ $previewPet->size === 'small' ? 'emerald' : ($previewPet->size === 'medium' ? 'amber' : 'blue') }}">
                            {{ __(ucfirst($previewPet->size === 'small' ? 'pequeño' : ($previewPet->size === 'medium' ? 'mediano' : 'grande'))) }}
                        </flux:badge>
                    </div>

                    <div class="mb-6 flex flex-wrap gap-4 text-sm text-neutral-500 dark:text-neutral-400">
                        @if ($previewPet->age_years !== null)
                            <span class="flex items-center gap-1">
                                <flux:icon name="calendar" class="size-4" />
                                {{ $previewPet->age_years }} {{ trans_choice('año|años', $previewPet->age_years) }}
                            </span>
                        @endif
                        @if ($previewPet->age_months !== null)
                            <span class="flex items-center gap-1">
                                <flux:icon name="calendar" class="size-4" />
                                {{ $previewPet->age_months }} {{ trans_choice('mes|meses', $previewPet->age_months) }}
                            </span>
                        @endif
                        @if ($previewPet->color)
                            <span class="flex items-center gap-1">
                                <flux:icon name="palette" class="size-4" />
                                {{ $previewPet->color }}
                            </span>
                        @endif
                        @if ($previewPet->organization)
                            <span class="flex items-center gap-1">
                                <flux:icon name="building" class="size-4" />
                                {{ $previewPet->organization->name }}
                            </span>
                        @endif
                    </div>

                    <flux:separator class="mb-6" />

                    <div class="mb-6">
                        <flux:heading level="2" size="lg" class="mb-3">{{ __('Descripción') }}</flux:heading>
                        <p class="text-neutral-600 dark:text-neutral-300 leading-relaxed">
                            {{ $previewPet->description }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <flux:heading level="2" size="lg" class="mb-3">{{ __('Características') }}</flux:heading>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon name="{{ $previewPet->is_vaccinated ? 'circle-check' : 'circle-x' }}"
                                    class="size-5 {{ $previewPet->is_vaccinated ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                                <span>{{ __('Vacunado') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon name="{{ $previewPet->is_neutered ? 'circle-check' : 'circle-x' }}"
                                    class="size-5 {{ $previewPet->is_neutered ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                                <span>{{ __('Esterilizado/Castrado') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon name="{{ $previewPet->is_house_trained ? 'circle-check' : 'circle-x' }}"
                                    class="size-5 {{ $previewPet->is_house_trained ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                                <span>{{ __('Educado en casa') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon
                                    name="{{ $previewPet->good_with_kids ? 'circle-check' : ($previewPet->good_with_kids === null ? 'circle-minus' : 'circle-x') }}"
                                    class="size-5 {{ $previewPet->good_with_kids ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                                <span>{{ __('Se lleva con niños') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon
                                    name="{{ $previewPet->good_with_pets ? 'circle-check' : ($previewPet->good_with_pets === null ? 'circle-minus' : 'circle-x') }}"
                                    class="size-5 {{ $previewPet->good_with_pets ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                                <span>{{ __('Se lleva con otras mascotas') }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($previewPet->status === 'available')
                        <flux:badge color="emerald" size="lg" class="w-full justify-center py-2">
                            {{ __('Disponible para adopción') }}
                        </flux:badge>
                    @else
                        <flux:badge color="neutral" size="lg" class="w-full justify-center py-2">
                            {{ __('Mascota adoptada') }}
                        </flux:badge>
                    @endif
                </div>
            </div>
        @endif
    </flux:modal>
</div>

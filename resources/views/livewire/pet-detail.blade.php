<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <flux:button href="{{ route('pets.catalog') }}" variant="ghost" icon="arrow-left" wire:navigate>
            {{ __('Volver al catálogo') }}
        </flux:button>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="space-y-4"
            x-data="{ selected: '{{ $pet->primaryImage?->image_path ?? '' }}' }">
            <div class="aspect-[4/3] overflow-hidden rounded-xl bg-neutral-100 dark:bg-zinc-700">
                <img x-show="selected" :src="selected" alt="{{ $pet->name }}"
                    class="size-full object-cover" />
                <div x-show="!selected"
                    class="flex size-full items-center justify-center text-neutral-400">
                    <flux:icon name="image" class="size-16" />
                </div>
            </div>

            @if ($pet->images->count() > 1)
                <div class="flex gap-2 overflow-x-auto pb-2">
                    @foreach ($pet->images as $image)
                        <div @click="selected = '{{ $image->image_path }}'"
                            class="aspect-square size-20 shrink-0 cursor-pointer overflow-hidden rounded-lg bg-neutral-100 transition hover:opacity-80 dark:bg-zinc-700"
                            :class="{ 'ring-2 ring-blue-500': selected === '{{ $image->image_path }}' }">
                            <img src="{{ $image->image_path }}" alt="" class="size-full object-cover" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="mb-4 flex items-start justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ $pet->name }}</flux:heading>
                    <flux:text class="mt-1">
                        {{ $pet->species->name }} &middot; {{ $pet->breed?->name ?? __('Sin raza') }}
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    @auth
                        <flux:button wire:click="toggleFavorite" variant="ghost"
                            wire:loading.attr="disabled"
                            class="{{ $pet->favorites->isNotEmpty() ? 'text-red-500' : '' }}">
                            <flux:icon name="heart"
                                class="size-5 {{ $pet->favorites->isNotEmpty() ? 'fill-current' : '' }}" />
                        </flux:button>
                    @endauth

                    <flux:badge size="sm"
                        color="{{ $pet->size === 'small' ? 'emerald' : ($pet->size === 'medium' ? 'amber' : 'blue') }}">
                        {{ __(ucfirst($pet->size === 'small' ? 'pequeño' : ($pet->size === 'medium' ? 'mediano' : 'grande'))) }}
                    </flux:badge>
                </div>
            </div>

            <div class="mb-6 flex flex-wrap gap-4 text-sm text-neutral-500 dark:text-neutral-400">
                @if ($pet->age_years !== null)
                    <span class="flex items-center gap-1">
                        <flux:icon name="calendar" class="size-4" />
                        {{ $pet->age_years }} {{ trans_choice('año|años', $pet->age_years) }}
                    </span>
                @endif
                @if ($pet->age_months !== null)
                    <span class="flex items-center gap-1">
                        <flux:icon name="calendar" class="size-4" />
                        {{ $pet->age_months }} {{ trans_choice('mes|meses', $pet->age_months) }}
                    </span>
                @endif
                @if ($pet->color)
                    <span class="flex items-center gap-1">
                        <flux:icon name="palette" class="size-4" />
                        {{ $pet->color }}
                    </span>
                @endif
                @if ($pet->organization)
                    <span class="flex items-center gap-1">
                        <flux:icon name="building" class="size-4" />
                        {{ $pet->organization->name }}
                    </span>
                @endif
            </div>

            <flux:separator class="mb-6" />

            <div class="mb-6">
                <flux:heading level="2" size="lg" class="mb-3">{{ __('Descripción') }}</flux:heading>
                <p class="text-neutral-600 dark:text-neutral-300 leading-relaxed">
                    {{ $pet->description }}
                </p>
            </div>

            <div class="mb-6">
                <flux:heading level="2" size="lg" class="mb-3">{{ __('Características') }}</flux:heading>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2 text-sm">
                        <flux:icon name="{{ $pet->is_vaccinated ? 'circle-check' : 'circle-x' }}"
                            class="size-5 {{ $pet->is_vaccinated ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                        <span>{{ __('Vacunado') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <flux:icon name="{{ $pet->is_neutered ? 'circle-check' : 'circle-x' }}"
                            class="size-5 {{ $pet->is_neutered ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                        <span>{{ __('Esterilizado/Castrado') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <flux:icon name="{{ $pet->is_house_trained ? 'circle-check' : 'circle-x' }}"
                            class="size-5 {{ $pet->is_house_trained ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                        <span>{{ __('Educado en casa') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <flux:icon
                            name="{{ $pet->good_with_kids ? 'circle-check' : ($pet->good_with_kids === null ? 'circle-minus' : 'circle-x') }}"
                            class="size-5 {{ $pet->good_with_kids ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                        <span>{{ __('Se lleva con niños') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <flux:icon
                            name="{{ $pet->good_with_pets ? 'circle-check' : ($pet->good_with_pets === null ? 'circle-minus' : 'circle-x') }}"
                            class="size-5 {{ $pet->good_with_pets ? 'text-green-500' : 'text-neutral-300 dark:text-neutral-600' }}" />
                        <span>{{ __('Se lleva con otras mascotas') }}</span>
                    </div>
                </div>
            </div>

            @if ($pet->organization)
                <flux:separator class="mb-6" />

                <div class="mb-6">
                    <flux:heading level="2" size="lg" class="mb-3">{{ __('Organización') }}</flux:heading>
                    <div
                        class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-zinc-800/50">
                        <flux:heading class="text-base">{{ $pet->organization->name }}</flux:heading>
                        @if ($pet->organization->city || $pet->organization->province)
                            <flux:text class="flex items-center gap-1 mt-1">
                                <flux:icon name="map-pin" class="size-4" />
                                {{ $pet->organization->city }}{{ $pet->organization->city && $pet->organization->province ? ', ' : '' }}{{ $pet->organization->province }}
                            </flux:text>
                        @endif
                        @if ($pet->organization->phone)
                            <flux:text class="flex items-center gap-1 mt-1">
                                <flux:icon name="phone" class="size-4" />
                                {{ $pet->organization->phone }}
                            </flux:text>
                        @endif
                    </div>
                </div>
            @endif

            @if ($pet->status === 'available')
                @auth
                    @if (auth()->user()->role !== 'admin')
                        <flux:button variant="primary" class="w-full" x-data=""
                            x-on:click.prevent="
                                if ({{ $userRequestCount }} > 0) {
                                    $dispatch('modal-show', { name: 'confirm-requests' });
                                } else {
                                    $dispatch('modal-show', { name: 'adoption-form' });
                                }
                            ">
                            {{ __('Solicitar adopción') }}
                        </flux:button>
                    @endif
                @else
                    <flux:button variant="primary" class="w-full" href="{{ route('login') }}" wire:navigate>
                        {{ __('Inicia sesión para adoptar') }}
                    </flux:button>
                @endauth
            @else
                <flux:badge color="neutral" size="lg" class="w-full justify-center py-2">
                    @auth('web')
                        @if (in_array(auth()->user()->role, ['rescuer', 'admin']) && $pet->adoptedBy)
                            {{ __('Adoptada por :name', ['name' => $pet->adoptedBy->name]) }}
                        @else
                            {{ __('Mascota adoptada') }}
                        @endif
                    @else
                        {{ __('Mascota adoptada') }}
                    @endauth
                </flux:badge>
            @endif

            <flux:modal name="confirm-requests" class="max-w-md">
                <div class="space-y-4">
                    <flux:heading size="lg">{{ __('Solicitudes activas') }}</flux:heading>
                    <flux:text>
                        {{ trans_choice('Ya solicitaste la adopción de :count mascota. ¿Deseas continuar?|Ya solicitaste la adopción de :count mascotas. ¿Deseas continuar?', $userRequestCount, ['count' => $userRequestCount]) }}
                    </flux:text>
                    <div class="flex justify-end gap-3">
                        <flux:modal.close>
                            <flux:button variant="ghost">{{ __('Cancelar') }}</flux:button>
                        </flux:modal.close>
                        <flux:modal.close>
                            <flux:button variant="primary" x-data=""
                                x-on:click="setTimeout(() => $dispatch('modal-show', { name: 'adoption-form' }), 150)">
                                {{ __('Continuar') }}
                            </flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>
        </div>
    </div>

    @auth
        <livewire:adoption.create-request :pet="$pet" :key="'adopt-' . $pet->id" />
    @endauth
</div>

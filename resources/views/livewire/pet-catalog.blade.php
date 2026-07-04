<div>
    <flux:heading size="xl" level="1" class="mb-6">{{ __('Mascotas en adopción') }}</flux:heading>

    <div class="mb-6 space-y-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <flux:input wire:model.live.debounce="search" placeholder="{{ __('Buscar mascotas...') }}" icon="magnifying-glass" class="max-w-sm" />

            <div class="flex flex-wrap gap-2">
                <flux:select wire:model.live="species" class="w-40">
                    <option value="">{{ __('Todas las especies') }}</option>
                    @foreach ($this->speciesList as $spec)
                        <option value="{{ $spec->slug }}">{{ $spec->name }} ({{ $spec->pets_count }})</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="size" class="w-36">
                    <option value="">{{ __('Todos los tamaños') }}</option>
                    <option value="small">{{ __('Pequeño') }}</option>
                    <option value="medium">{{ __('Mediano') }}</option>
                    <option value="large">{{ __('Grande') }}</option>
                </flux:select>

                <flux:select wire:model.live="sort" class="w-36">
                    <option value="latest">{{ __('Más recientes') }}</option>
                    <option value="oldest">{{ __('Más antiguos') }}</option>
                    <option value="name">{{ __('Nombre A-Z') }}</option>
                </flux:select>

                @if ($search || $species || $size || $status !== 'available')
                    <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                        {{ __('Limpiar filtros') }}
                    </flux:button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($this->pets as $pet)
            <div
                class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white transition hover:shadow-lg dark:border-neutral-700 dark:bg-zinc-800 cursor-pointer"
                wire:click="redirectToDetail({{ $pet->id }})"
                wire:key="pet-{{ $pet->id }}"
            >
                <div class="aspect-[4/3] overflow-hidden bg-neutral-100 dark:bg-zinc-700">
                    @if ($pet->primaryImage)
                        <img src="{{ $pet->primaryImage->image_path }}" alt="{{ $pet->name }}" class="size-full object-cover transition group-hover:scale-105" />
                    @else
                        <div class="flex size-full items-center justify-center text-neutral-400">
                            <flux:icon name="photo" class="size-12" />
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <div class="mb-2 flex items-start justify-between">
                        <div>
                            <flux:heading class="text-lg font-semibold">{{ $pet->name }}</flux:heading>
                            <flux:text class="text-sm">
                                {{ $pet->species->name }} · {{ $pet->breed?->name ?? __('Sin raza') }}
                            </flux:text>
                        </div>
                        <flux:badge size="sm" color="{{ $pet->size === 'small' ? 'emerald' : ($pet->size === 'medium' ? 'amber' : 'blue') }}" class="shrink-0">
                            {{ __(ucfirst($pet->size)) }}
                        </flux:badge>
                    </div>

                    <div class="mb-3 flex flex-wrap gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                        @if ($pet->age_years !== null)
                            <span>{{ $pet->age_years }} {{ trans_choice('año|años', $pet->age_years) }}</span>
                        @endif
                        @if ($pet->age_months !== null)
                            <span>{{ $pet->age_months }} {{ trans_choice('mes|meses', $pet->age_months) }}</span>
                        @endif
                        @if ($pet->color)
                            <span>{{ $pet->color }}</span>
                        @endif
                    </div>

                    <p class="mb-4 line-clamp-2 text-sm text-neutral-600 dark:text-neutral-300">
                        {{ $pet->description }}
                    </p>

                    <div class="flex items-center justify-between">
                        <flux:text class="text-xs text-neutral-400">
                            {{ $pet->organization->name }}
                        </flux:text>
                        <flux:button variant="primary" size="xs" :href="route('pets.detail', $pet)" wire:navigate>
                            {{ __('Ver detalle') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <flux:icon name="heart" class="mb-4 size-12 text-neutral-300 dark:text-neutral-600" />
                <flux:heading class="mb-2 text-lg">{{ __('No se encontraron mascotas') }}</flux:heading>
                <flux:text class="mb-4">{{ __('Intentá ajustar los filtros o probá con otra búsqueda.') }}</flux:text>
                <flux:button wire:click="clearFilters" variant="primary">{{ __('Limpiar filtros') }}</flux:button>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $this->pets->links() }}
    </div>
</div>

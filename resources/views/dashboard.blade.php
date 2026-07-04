<x-layouts::app :title="__('Dashboard')">
    @php
        $user = auth()->user();
        $stats = [];
        if (in_array($user->role, ['rescuer', 'admin'])) {
            $orgIds = $user->organizations()->pluck('id');
            $stats['availablePets'] = \App\Models\Pet::whereIn('organization_id', $orgIds)->where('status', 'available')->count();
            $stats['adoptedPets'] = \App\Models\Pet::whereIn('organization_id', $orgIds)->where('status', 'adopted')->count();
            $stats['pendingRequests'] = \App\Models\AdoptionRequest::whereIn('organization_id', $orgIds)->where('status', 'pending')->count();
        }
        $stats['myRequests'] = \App\Models\AdoptionRequest::where('user_id', $user->id)->count();
        $stats['myFavorites'] = \App\Models\Favorite::where('user_id', $user->id)->count();
        $recentPets = \App\Models\Pet::with(['species', 'primaryImage'])->where('status', 'available')->latest()->take(6)->get();
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-8 rounded-xl">
        <div>
            <flux:heading size="xl" level="1">{{ __('Bienvenido, :name', ['name' => $user->name]) }}</flux:heading>
            <flux:text class="mt-1">{{ __('Resumen de tu actividad en PetConnect.') }}</flux:text>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @if (isset($stats['pendingRequests']))
                <div class="stat-card">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                        <flux:icon name="mail" class="size-5" />
                    </div>
                    <flux:heading class="text-2xl font-bold">{{ $stats['pendingRequests'] }}</flux:heading>
                    <flux:text class="text-sm">{{ __('Solicitudes pendientes') }}</flux:text>
                </div>
            @endif

            @if (isset($stats['availablePets']))
                <div class="stat-card">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <flux:icon name="paw-print" class="size-5" />
                    </div>
                    <flux:heading class="text-2xl font-bold">{{ $stats['availablePets'] }}</flux:heading>
                    <flux:text class="text-sm">{{ __('Mascotas disponibles') }}</flux:text>
                </div>
            @endif

            @if (isset($stats['adoptedPets']))
                <div class="stat-card">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <flux:icon name="heart" class="size-5" />
                    </div>
                    <flux:heading class="text-2xl font-bold">{{ $stats['adoptedPets'] }}</flux:heading>
                    <flux:text class="text-sm">{{ __('Adoptadas') }}</flux:text>
                </div>
            @endif

            <div class="stat-card">
                <div class="mb-3 flex size-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400">
                    <flux:icon name="list" class="size-5" />
                </div>
                <flux:heading class="text-2xl font-bold">{{ $stats['myRequests'] }}</flux:heading>
                <flux:text class="text-sm">{{ __('Mis solicitudes') }}</flux:text>
            </div>

            <div class="stat-card">
                <div class="mb-3 flex size-10 items-center justify-center rounded-xl bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400">
                    <flux:icon name="star" class="size-5" />
                </div>
                <flux:heading class="text-2xl font-bold">{{ $stats['myFavorites'] }}</flux:heading>
                <flux:text class="text-sm">{{ __('Favoritos') }}</flux:text>
            </div>
        </div>

        @if ($recentPets->isNotEmpty())
            <div>
                <flux:heading level="2" size="lg" class="mb-4">{{ __('Mascotas disponibles') }}</flux:heading>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    @foreach ($recentPets as $pet)
                        <a href="{{ route('pets.detail', $pet) }}" wire:navigate class="card-hover overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                                @if ($pet->primaryImage)
                                    <img src="{{ $pet->primaryImage->image_path }}" alt="{{ $pet->name }}" class="size-full object-cover transition duration-300 group-hover:scale-105" />
                                @else
                                    <div class="flex size-full items-center justify-center text-zinc-400">
                                        <flux:icon name="image" class="size-8" />
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <flux:heading class="text-sm font-semibold">{{ $pet->name }}</flux:heading>
                                <flux:text class="text-xs text-zinc-500">{{ $pet->species->name }}</flux:text>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if (isset($stats['pendingRequests']) && $stats['pendingRequests'] > 0)
            <div class="flex justify-end">
                <flux:button :href="route('rescuer.requests')" variant="primary" wire:navigate>
                    {{ __('Ver solicitudes pendientes') }}
                </flux:button>
            </div>
        @endif
    </div>
</x-layouts::app>

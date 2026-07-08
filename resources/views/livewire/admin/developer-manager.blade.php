<div>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl" level="1">{{ __('Desarrolladores') }}</flux:heading>
        <flux:button :href="route('admin.developers.create')" wire:navigate variant="primary" class="w-full sm:w-auto">
            {{ __('Nuevo desarrollador') }}
        </flux:button>
    </div>

    <!-- Desktop table -->
    <div class="hidden sm:block overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Nombre') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Rol') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Imagen') }}</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($developers as $developer)
                    <tr class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">{{ $developer->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $developer->role }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            @if ($developer->image)
                                <img src="{{ $developer->image }}" alt="{{ $developer->name }}" class="size-10 rounded-full object-cover" />
                            @else
                                <span class="text-xs text-zinc-400">{{ __('Sin imagen') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <flux:button :href="route('admin.developers.edit', $developer)" wire:navigate variant="ghost" size="sm">
                                {{ __('Editar') }}
                            </flux:button>
                            <flux:button wire:click="delete({{ $developer->id }})" wire:confirm="{{ __('¿Eliminar este desarrollador?') }}" variant="danger" size="sm">
                                {{ __('Eliminar') }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('No hay desarrolladores registrados.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile cards -->
    <div class="sm:hidden space-y-3">
        @forelse ($developers as $developer)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center gap-3">
                    @if ($developer->image)
                        <img src="{{ $developer->image }}" alt="{{ $developer->name }}" class="size-10 rounded-full object-cover" />
                    @else
                        <span class="flex size-10 items-center justify-center rounded-full bg-zinc-100 text-xs text-zinc-400 dark:bg-zinc-800">{{ __('Sin img') }}</span>
                    @endif
                    <div class="flex-1 min-w-0">
                        <flux:heading class="text-sm font-medium">{{ $developer->name }}</flux:heading>
                        <flux:text class="text-xs">{{ $developer->role }}</flux:text>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <flux:button :href="route('admin.developers.edit', $developer)" wire:navigate variant="ghost" size="xs">
                        {{ __('Editar') }}
                    </flux:button>
                    <flux:button wire:click="delete({{ $developer->id }})" wire:confirm="{{ __('¿Eliminar este desarrollador?') }}" variant="danger" size="xs">
                        {{ __('Eliminar') }}
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('No hay desarrolladores registrados.') }}
            </div>
        @endforelse
    </div>
</div>

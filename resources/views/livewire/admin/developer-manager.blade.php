<div>
    <div class="mb-8 flex items-center justify-between">
        <flux:heading size="xl" level="1">{{ __('Desarrolladores') }}</flux:heading>
        <flux:button :href="route('admin.developers.create')" wire:navigate variant="primary">
            {{ __('Nuevo desarrollador') }}
        </flux:button>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
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
</div>

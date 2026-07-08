<div>
    <div class="mb-8 flex items-center justify-between">
        <flux:heading size="xl" level="1">{{ __('Refugios') }}</flux:heading>
        <flux:button :href="route('admin.organizations.create')" wire:navigate variant="primary">
            {{ __('Nuevo refugio') }}
        </flux:button>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Nombre') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Usuario') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Ciudad') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Estado') }}</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($organizations as $organization)
                    <tr class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">{{ $organization->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $organization->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $organization->city }}, {{ $organization->province }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{
                                    $organization->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($organization->status === 'inactive' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400')
                                }}">
                                    {{ $organization->status === 'active' ? __('Activo') : ($organization->status === 'inactive' ? __('Inactivo') : __('Pendiente')) }}
                                </span>
                                @if ($organization->status !== 'pending')
                                    <button wire:click="toggleStatus({{ $organization->id }})" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                                        @if ($organization->status === 'active')
                                            <flux:icon name="x-mark" class="h-4 w-4" />
                                        @else
                                            <flux:icon name="check" class="h-4 w-4" />
                                        @endif
                                    </button>
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <flux:button :href="route('admin.organizations.edit', $organization)" wire:navigate variant="ghost" size="sm">
                                {{ __('Editar') }}
                            </flux:button>
                            <flux:button wire:click="delete({{ $organization->id }})" wire:confirm="{{ __('¿Eliminar este refugio?') }}" variant="danger" size="sm">
                                {{ __('Eliminar') }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('No hay refugios registrados.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

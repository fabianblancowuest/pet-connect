<div>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl" level="1">{{ __('Usuarios') }}</flux:heading>
        <flux:button :href="route('admin.users.create')" wire:navigate variant="primary" class="w-full sm:w-auto">
            {{ __('Nuevo usuario') }}
        </flux:button>
    </div>

    <div class="mb-6 flex flex-col gap-2 sm:flex-row">
        <flux:input wire:model.live.debounce.300ms="search" :placeholder="__('Buscar por nombre o email...')" icon="magnifying-glass" class="w-full sm:w-64" />
        <flux:select wire:model.live="roleFilter" class="w-full sm:w-40">
            <option value="">{{ __('Todos los roles') }}</option>
            <option value="adopter">{{ __('Adoptante') }}</option>
            <option value="rescuer">{{ __('Rescatista') }}</option>
            <option value="admin">{{ __('Admin') }}</option>
        </flux:select>
    </div>

    <!-- Desktop table -->
    <div class="hidden sm:block overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Nombre') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Email') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Rol') }}</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Registrado') }}</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($users as $u)
                    <tr class="bg-white dark:bg-zinc-900 {{ $u->id === auth()->id() ? 'bg-emerald-50 dark:bg-emerald-900/10' : '' }}">
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $u->name }}
                            @if ($u->id === auth()->id())
                                <span class="ml-2 text-xs text-emerald-600 dark:text-emerald-400">({{ __('vos') }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $u->email }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                {{ $u->role === 'rescuer' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                {{ $u->role === 'adopter' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}">
                                {{ $u->role === 'admin' ? __('Admin') : ($u->role === 'rescuer' ? __('Rescatista') : __('Adoptante')) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $u->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button :href="route('admin.users.edit', $u)" wire:navigate variant="ghost" size="sm">
                                {{ __('Editar') }}
                            </flux:button>
                            @if ($u->id !== auth()->id())
                                <flux:button wire:click="delete({{ $u->id }})" wire:confirm="{{ __('¿Eliminar este usuario?') }}" variant="danger" size="sm">
                                    {{ __('Eliminar') }}
                                </flux:button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('No se encontraron usuarios.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile cards -->
    <div class="sm:hidden space-y-3">
        @forelse ($users as $u)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900 {{ $u->id === auth()->id() ? 'border-emerald-200 dark:border-emerald-800' : '' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <flux:heading class="text-sm font-medium">{{ $u->name }}</flux:heading>
                            @if ($u->id === auth()->id())
                                <span class="text-xs text-emerald-600 dark:text-emerald-400">({{ __('vos') }})</span>
                            @endif
                        </div>
                        <flux:text class="text-xs">{{ $u->email }}</flux:text>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                        {{ $u->role === 'rescuer' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                        {{ $u->role === 'adopter' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}">
                        {{ $u->role === 'admin' ? __('Admin') : ($u->role === 'rescuer' ? __('Rescatista') : __('Adoptante')) }}
                    </span>
                </div>
                <flux:text class="mt-1 text-xs">{{ $u->created_at->format('d/m/Y') }}</flux:text>
                <div class="mt-3 flex gap-2">
                    <flux:button :href="route('admin.users.edit', $u)" wire:navigate variant="ghost" size="xs">
                        {{ __('Editar') }}
                    </flux:button>
                    @if ($u->id !== auth()->id())
                        <flux:button wire:click="delete({{ $u->id }})" wire:confirm="{{ __('¿Eliminar este usuario?') }}" variant="danger" size="xs">
                            {{ __('Eliminar') }}
                        </flux:button>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('No se encontraron usuarios.') }}
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>

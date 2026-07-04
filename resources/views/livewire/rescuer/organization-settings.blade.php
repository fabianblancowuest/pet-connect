<div>
    <flux:heading size="xl" level="1" class="mb-8">
        {{ $editing ? __('Mi refugio') : __('Crear refugio') }}
    </flux:heading>

    @if (!$editing)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
            <div class="flex items-start gap-3">
                <flux:icon name="info" class="mt-0.5 size-5 text-amber-600 dark:text-amber-400" />
                <div>
                    <flux:heading class="text-sm font-semibold text-amber-800 dark:text-amber-300">{{ __('Necesitás un refugio') }}</flux:heading>
                    <flux:text class="text-sm text-amber-700 dark:text-amber-400">{{ __('Creá un refugio para poder registrar mascotas y recibir solicitudes de adopción.') }}</flux:text>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:fieldset>
            <flux:field>
                <flux:label>{{ __('Nombre del refugio') }}</flux:label>
                <flux:input wire:model="name" :placeholder="__('Ej: Huellitas Refugio')" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Descripción') }}</flux:label>
                <flux:textarea wire:model="description" :placeholder="__('Contanos sobre la misión e historia del refugio...')" rows="4" />
                <flux:error name="description" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Teléfono') }}</flux:label>
                    <flux:input wire:model="phone" :placeholder="__('+54 11 1234-5678')" required />
                    <flux:error name="phone" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Sitio web') }}</flux:label>
                    <flux:input wire:model="website" :placeholder="__('https://ejemplo.com')" type="url" />
                    <flux:error name="website" />
                </flux:field>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Dirección') }}</flux:label>
                    <flux:input wire:model="address" :placeholder="__('Calle y número')" required />
                    <flux:error name="address" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Ciudad') }}</flux:label>
                    <flux:input wire:model="city" :placeholder="__('Ej: Buenos Aires')" required />
                    <flux:error name="city" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('Provincia') }}</flux:label>
                <flux:input wire:model="province" :placeholder="__('Ej: CABA')" required />
                <flux:error name="province" />
            </flux:field>
        </flux:fieldset>

        <div class="flex justify-end gap-3">
            <flux:button :href="route('dashboard')" variant="ghost" wire:navigate>
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="primary" type="submit">
                {{ $editing ? __('Guardar cambios') : __('Crear refugio') }}
            </flux:button>
        </div>
    </form>
</div>

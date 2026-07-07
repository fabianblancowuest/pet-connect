<div>
    <flux:heading size="xl" level="1" class="mb-8">
        {{ $editing ? __('Editar refugio') : __('Nuevo refugio') }}
    </flux:heading>

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:fieldset>
            @if (!$editing)
                <flux:field>
                    <flux:label>{{ __('Usuario propietario') }}</flux:label>
                    <flux:select wire:model="user_id" required>
                        <option value="">{{ __('Seleccionar usuario') }}</option>
                        @foreach ($this->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="user_id" />
                </flux:field>
            @endif

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

        <flux:fieldset>
            <flux:legend>{{ __('Logo') }}</flux:legend>
            <flux:field>
                <flux:label>{{ __('Logo del refugio') }}</flux:label>
                @if ($editing && $organization?->logo)
                    <div class="mb-2">
                        <div class="relative inline-block">
                            <img src="{{ $organization->logo }}" alt="{{ $name }}" class="h-24 w-auto rounded-lg object-contain" />
                            <button
                                type="button"
                                wire:click="removeLogo"
                                wire:confirm="{{ __('¿Eliminar el logo?') }}"
                                class="absolute -right-2 -top-2 flex size-5 items-center justify-center rounded-full bg-red-500 text-xs text-white hover:bg-red-600"
                            >
                                &times;
                            </button>
                        </div>
                    </div>
                @endif
                <flux:input wire:model="logo" type="file" accept="image/*" />
                <flux:error name="logo" />
                @if ($logo && !$organization?->logo)
                    <div class="mt-2">
                        <img src="{{ $logo->temporaryUrl() }}" alt="" class="h-24 w-auto rounded-lg object-contain" />
                    </div>
                @endif
            </flux:field>
        </flux:fieldset>

        <div class="flex justify-end gap-3">
            <flux:button :href="route('admin.organizations.index')" variant="ghost" wire:navigate>
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                {{ $editing ? __('Guardar cambios') : __('Crear refugio') }}
            </flux:button>
        </div>
    </form>
</div>

<div>
    <div class="mb-6">
        <flux:button :href="route('admin.users.index')" variant="ghost" icon="arrow-left" wire:navigate>
            {{ __('Volver a usuarios') }}
        </flux:button>
    </div>

    <flux:heading size="xl" level="1" class="mb-8">
        {{ $editing ? __('Editar usuario') : __('Nuevo usuario') }}
    </flux:heading>

    <form wire:submit="save" class="max-w-lg space-y-6">
        <flux:fieldset>
            <flux:field>
                <flux:label>{{ __('Nombre') }}</flux:label>
                <flux:input wire:model="name" :placeholder="__('Nombre completo')" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Email') }}</flux:label>
                <flux:input wire:model="email" type="email" :placeholder="__('correo@ejemplo.com')" required />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Rol') }}</flux:label>
                <flux:select wire:model="role" required>
                    <option value="adopter">{{ __('Adoptante') }}</option>
                    <option value="rescuer">{{ __('Rescatista') }}</option>
                    <option value="admin">{{ __('Admin') }}</option>
                </flux:select>
                <flux:error name="role" />
            </flux:field>

            @if ($editing)
                <flux:field>
                    <flux:label>{{ __('Nueva contraseña (opcional)') }}</flux:label>
                    <flux:input wire:model="password" type="password" :placeholder="__('Dejar vacío para mantener la actual')" autocomplete="new-password" />
                    <flux:error name="password" />
                </flux:field>
            @else
                <flux:field>
                    <flux:label>{{ __('Contraseña') }}</flux:label>
                    <flux:input wire:model="password" type="password" required autocomplete="new-password" />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Confirmar contraseña') }}</flux:label>
                    <flux:input wire:model="password_confirmation" type="password" required autocomplete="new-password" />
                    <flux:error name="password_confirmation" />
                </flux:field>
            @endif
        </flux:fieldset>

        <div class="flex justify-end gap-3">
            <flux:button :href="route('admin.users.index')" variant="ghost" wire:navigate>
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                {{ $editing ? __('Guardar cambios') : __('Crear usuario') }}
            </flux:button>
        </div>
    </form>
</div>

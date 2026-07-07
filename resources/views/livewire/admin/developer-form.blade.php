<div>
    <flux:heading size="xl" level="1" class="mb-8">
        {{ $editing ? __('Editar desarrollador') : __('Nuevo desarrollador') }}
    </flux:heading>

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:fieldset>
            <flux:field>
                <flux:label>{{ __('Nombre') }}</flux:label>
                <flux:input wire:model="name" :placeholder="__('Nombre completo')" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Rol') }}</flux:label>
                <flux:input wire:model="role" :placeholder="__('Ej: Backend Developer, DBA, Frontend Developer')" />
                <flux:error name="role" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Descripción') }}</flux:label>
                <flux:textarea wire:model="description" :placeholder="__('Descripción del rol y contribuciones...')" rows="4" />
                <flux:error name="description" />
            </flux:field>
        </flux:fieldset>

        <flux:fieldset>
            <flux:legend>{{ __('Imagen') }}</flux:legend>
            <flux:field>
                <flux:label>{{ __('Foto del desarrollador') }}</flux:label>
                @if ($editing && $developer?->image)
                    <div class="mb-2">
                        <img src="{{ $developer->image }}" alt="{{ $name }}" class="size-24 rounded-full object-cover" />
                    </div>
                @endif
                <flux:input wire:model="image" type="file" accept="image/*" />
                <flux:error name="image" />
                @if ($image && !$developer?->image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" alt="" class="size-24 rounded-full object-cover" />
                    </div>
                @endif
            </flux:field>
        </flux:fieldset>

        <div class="flex justify-end gap-3">
            <flux:button :href="route('admin.developers.index')" variant="ghost" wire:navigate>
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                {{ $editing ? __('Guardar cambios') : __('Crear desarrollador') }}
            </flux:button>
        </div>
    </form>
</div>

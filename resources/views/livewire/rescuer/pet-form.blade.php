<div>
    <div class="mb-6">
        <flux:button :href="route('rescuer.pets.index')" variant="ghost" icon="arrow-left" wire:navigate>
            {{ __('Volver a mis mascotas') }}
        </flux:button>
    </div>

    <flux:heading size="xl" level="1" class="mb-8">
        {{ $editing ? __('Editar mascota') : __('Nueva mascota') }}
    </flux:heading>

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:fieldset>
            <flux:field>
                <flux:label>{{ __('Nombre') }}</flux:label>
                <flux:input wire:model="name" :placeholder="__('Nombre de la mascota')" required />
                <flux:error name="name" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Especie') }}</flux:label>
                    <flux:select wire:model.live="species_id" required>
                        <option value="">{{ __('Seleccionar...') }}</option>
                        @foreach ($this->speciesList as $species)
                            <option value="{{ $species->id }}">{{ $species->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="species_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Raza') }}</flux:label>
                    <flux:select wire:model="breed_id">
                        <option value="">{{ __('Sin raza') }}</option>
                        @foreach ($this->breeds as $breed)
                            <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="breed_id" />
                </flux:field>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Edad (años)') }}</flux:label>
                    <flux:input wire:model="age_years" type="number" min="0" max="50" />
                    <flux:error name="age_years" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Edad (meses)') }}</flux:label>
                    <flux:input wire:model="age_months" type="number" min="0" max="11" />
                    <flux:error name="age_months" />
                </flux:field>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Tamaño') }}</flux:label>
                    <flux:select wire:model="size" required>
                        <option value="small">{{ __('Pequeño') }}</option>
                        <option value="medium">{{ __('Mediano') }}</option>
                        <option value="large">{{ __('Grande') }}</option>
                    </flux:select>
                    <flux:error name="size" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Color') }}</flux:label>
                    <flux:input wire:model="color" :placeholder="__('Ej: Marrón, Negro...')" />
                    <flux:error name="color" />
                </flux:field>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Estado') }}</flux:label>
                    <flux:select wire:model="status" required>
                        <option value="available">{{ __('Disponible') }}</option>
                        <option value="adopted">{{ __('Adoptada') }}</option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('Descripción') }}</flux:label>
                <flux:textarea wire:model="description" :placeholder="__('Contanos sobre la personalidad, historia y necesidades de la mascota...')" rows="5" required />
                <flux:error name="description" />
            </flux:field>
        </flux:fieldset>

        <flux:fieldset>
            <flux:legend>{{ __('Características') }}</flux:legend>

            <div class="grid grid-cols-2 gap-4">
                <flux:checkbox wire:model="is_vaccinated" :label="__('Está vacunado')" />
                <flux:checkbox wire:model="is_neutered" :label="__('Está esterilizado/castrado')" />
                <flux:checkbox wire:model="is_house_trained" :label="__('Está educado en casa')" />
            </div>

            <flux:separator class="my-4" />

            <flux:legend>{{ __('Comportamiento') }}</flux:legend>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Se lleva con niños') }}</flux:label>
                    <flux:select wire:model="good_with_kids">
                        <option value="">{{ __('No especificado') }}</option>
                        <option value="1">{{ __('Sí') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Se lleva con otras mascotas') }}</flux:label>
                    <flux:select wire:model="good_with_pets">
                        <option value="">{{ __('No especificado') }}</option>
                        <option value="1">{{ __('Sí') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </flux:select>
                </flux:field>
            </div>
        </flux:fieldset>

        <flux:fieldset>
            <flux:legend>{{ __('Fotos') }}</flux:legend>
            <flux:field>
                <flux:label>{{ __('Agregar imágenes') }}</flux:label>
                <flux:input wire:model="images" type="file" multiple accept="image/*" />
                <flux:error name="images.*" />
                @if ($images)
                    <div class="mt-2 flex gap-2">
                        @foreach ($images as $image)
                            <div class="aspect-square size-20 overflow-hidden rounded-lg bg-neutral-100 dark:bg-zinc-700">
                                <img src="{{ $image->temporaryUrl() }}" alt="" class="size-full object-cover" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </flux:field>
        </flux:fieldset>

        <div class="flex justify-end gap-3">
            <flux:button :href="route('rescuer.pets.index')" variant="ghost" wire:navigate>
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="primary" type="submit">
                {{ $editing ? __('Actualizar mascota') : __('Crear mascota') }}
            </flux:button>
        </div>
    </form>
</div>

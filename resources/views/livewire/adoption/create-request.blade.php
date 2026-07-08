<div>
    <flux:modal name="adoption-form" class="md:w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Solicitar adopción') }}</flux:heading>
                <flux:text>{{ __('Completá el formulario para adoptar a') }} <strong>{{ $pet->name }}</strong></flux:text>
            </div>

            <form wire:submit="submit" class="space-y-4">
                @if ($hasPreviousRequests)
                    <flux:badge color="blue" size="lg" class="w-full justify-center py-2">
                        {{ __('Tus datos personales ya están cargados de solicitudes anteriores.') }}
                    </flux:badge>
                @endif

                @if (!$hasPreviousRequests)
                    <flux:fieldset>
                        <flux:legend>{{ __('Datos personales') }}</flux:legend>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>{{ __('Fecha de nacimiento') }}</flux:label>
                                <flux:input wire:model="birth_date" type="date" required />
                                <flux:error name="birth_date" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Teléfono de contacto') }}</flux:label>
                                <flux:input wire:model="phone" :placeholder="__('+54 11 1234-5678')" required />
                                <flux:error name="phone" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>{{ __('Calle y número') }}</flux:label>
                            <flux:input wire:model="address" :placeholder="__('Ej: Av. 25 de Mayo 123')" required />
                            <flux:error name="address" />
                        </flux:field>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>{{ __('Localidad') }}</flux:label>
                                <flux:select wire:model="locality" required>
                                    <option value="">{{ __('Seleccionar localidad...') }}</option>
                                    @foreach ($localities as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="locality" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('Provincia') }}</flux:label>
                                <flux:input value="Formosa" disabled />
                            </flux:field>
                        </div>
                    </flux:fieldset>

                    <flux:fieldset>
                        <flux:legend>{{ __('Tu hogar') }}</flux:legend>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>{{ __('Tipo de vivienda') }}</flux:label>
                                <flux:select wire:model="housing_type" required>
                                    <option value="">{{ __('Seleccionar...') }}</option>
                                    <option value="house">{{ __('Casa') }}</option>
                                    <option value="apartment">{{ __('Departamento') }}</option>
                                </flux:select>
                                <flux:error name="housing_type" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('¿Tiene espacio al aire libre?') }}</flux:label>
                                <flux:select wire:model="has_outdoor_space" required>
                                    <option value="">{{ __('Seleccionar...') }}</option>
                                    <option value="1">{{ __('Sí') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </flux:select>
                                <flux:error name="has_outdoor_space" />
                            </flux:field>
                        </div>
                    </flux:fieldset>
                @else
                    <flux:fieldset>
                        <flux:legend>{{ __('Tu hogar') }}</flux:legend>

                        <div class="grid grid-cols-1 gap-4">
                            <flux:field>
                                <flux:label>{{ __('¿Tiene espacio al aire libre?') }}</flux:label>
                                <flux:select wire:model="has_outdoor_space" required>
                                    <option value="">{{ __('Seleccionar...') }}</option>
                                    <option value="1">{{ __('Sí') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </flux:select>
                                <flux:error name="has_outdoor_space" />
                            </flux:field>
                        </div>
                    </flux:fieldset>
                @endif

                <flux:fieldset>
                    <flux:legend>{{ __('Otras mascotas') }}</flux:legend>

                    <flux:field>
                        <flux:label>{{ __('¿Qué otras mascotas tenés?') }}</flux:label>
                        <flux:text class="mb-2 text-xs text-neutral-400">{{ __('Dejá vacío si no tenés otras mascotas.') }}</flux:text>
                        <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-4">
                            <flux:checkbox wire:model="other_pets_types" value="dog" :label="__('Perro')" />
                            <flux:checkbox wire:model="other_pets_types" value="cat" :label="__('Gato')" />
                            <flux:checkbox wire:model="other_pets_types" value="rodent" :label="__('Roedor')" />
                            <flux:checkbox wire:model="other_pets_types" value="bird" :label="__('Ave')" />
                            <flux:checkbox wire:model="other_pets_types" value="other" :label="__('Otro')" />
                        </div>
                        <flux:error name="other_pets_types" />
                    </flux:field>
                </flux:fieldset>

                <flux:fieldset>
                    <flux:legend>{{ __('Experiencia') }}</flux:legend>

                    <flux:field>
                        <flux:label>{{ __('¿Tuviste mascotas antes?') }}</flux:label>
                        <flux:select wire:model="previous_experience" required>
                            <option value="">{{ __('Seleccionar...') }}</option>
                            <option value="1">{{ __('Sí, tengo experiencia') }}</option>
                            <option value="0">{{ __('No, sería mi primera vez') }}</option>
                        </flux:select>
                        <flux:error name="previous_experience" />
                    </flux:field>
                </flux:fieldset>

                <flux:fieldset>
                    <flux:legend>{{ __('Motivación') }}</flux:legend>

                    <flux:field>
                        <flux:textarea
                            wire:model="message"
                            :label="__('¿Por qué querés adoptar?')"
                            :placeholder="__('Contanos sobre vos, tu estilo de vida, y por qué esta mascota sería ideal para tu hogar...')"
                            rows="4"
                            required
                        />
                        <flux:error name="message" />
                    </flux:field>
                </flux:fieldset>

                <div class="flex justify-end gap-3">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancelar') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                        {{ __('Enviar solicitud') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

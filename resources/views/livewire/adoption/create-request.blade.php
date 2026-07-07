<div>
    <flux:modal name="adoption-form" class="md:w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Solicitar adopción') }}</flux:heading>
                <flux:text>{{ __('Completá el formulario para adoptar a') }} <strong>{{ $pet->name }}</strong></flux:text>
            </div>

            <form wire:submit="submit" class="space-y-4">
                <flux:fieldset>
                    <flux:legend>{{ __('Contacto') }}</flux:legend>

                    <flux:field>
                        <flux:label>{{ __('Teléfono de contacto') }}</flux:label>
                        <flux:input wire:model="phone" :placeholder="__('+54 11 1234-5678')" required />
                        <flux:error name="phone" />
                    </flux:field>
                </flux:fieldset>

                <flux:fieldset>
                    <flux:legend>{{ __('Tu hogar') }}</flux:legend>

                    <div class="grid grid-cols-2 gap-4">
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

                <flux:fieldset>
                    <flux:legend>{{ __('Convivientes') }}</flux:legend>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>{{ __('¿Hay niños en el hogar?') }}</flux:label>
                            <flux:select wire:model.live="has_children" required>
                                <option value="">{{ __('Seleccionar...') }}</option>
                                <option value="1">{{ __('Sí') }}</option>
                                <option value="0">{{ __('No') }}</option>
                            </flux:select>
                            <flux:error name="has_children" />
                        </flux:field>

                        @if ($has_children)
                            <flux:field>
                                <flux:label>{{ __('Edades de los niños') }}</flux:label>
                                <flux:input wire:model="children_ages" :placeholder="__('Ej: 3, 7 y 10 años')" />
                                <flux:error name="children_ages" />
                            </flux:field>
                        @endif
                    </div>

                    <flux:separator class="my-4" />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>{{ __('¿Tenés otras mascotas?') }}</flux:label>
                            <flux:select wire:model.live="has_other_pets" required>
                                <option value="">{{ __('Seleccionar...') }}</option>
                                <option value="1">{{ __('Sí') }}</option>
                                <option value="0">{{ __('No') }}</option>
                            </flux:select>
                            <flux:error name="has_other_pets" />
                        </flux:field>

                        @if ($has_other_pets)
                            <flux:field>
                                <flux:label>{{ __('Contanos sobre ellas') }}</flux:label>
                                <flux:input wire:model="other_pets_details" :placeholder="__('Ej: 1 perro y 2 gatos')" />
                                <flux:error name="other_pets_details" />
                            </flux:field>
                        @endif
                    </div>
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

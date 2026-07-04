<div>
    <flux:modal name="adoption-form" class="md:w-xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Solicitar adopción') }}</flux:heading>
                <flux:text>{{ __('Contanos por qué querés adoptar a') }} <strong>{{ $pet->name }}</strong></flux:text>
            </div>

            <form wire:submit="submit" class="space-y-4">
                <flux:textarea
                    wire:model="message"
                    :label="__('Tu mensaje')"
                    :placeholder="__('Contanos sobre vos, tu hogar, y por qué querés adoptar a esta mascota...')"
                    rows="5"
                    required
                />

                <div class="flex justify-end gap-3">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancelar') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        {{ __('Enviar solicitud') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

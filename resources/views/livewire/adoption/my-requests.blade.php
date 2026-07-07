<div>
    <flux:heading size="xl" level="1" class="mb-6">{{ __('Mis solicitudes de adopción') }}</flux:heading>

    @if ($this->requests->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <flux:icon name="mail" class="mb-4 size-12 text-neutral-300 dark:text-neutral-600" />
            <flux:heading class="mb-2 text-lg">{{ __('No tenés solicitudes activas') }}</flux:heading>
            <flux:text class="mb-4">{{ __('Explorá las mascotas disponibles y enviá tu primera solicitud.') }}</flux:text>
            <flux:button :href="route('pets.catalog')" variant="primary" wire:navigate>
                {{ __('Ver mascotas') }}
            </flux:button>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($this->requests as $request)
                <div class="flex items-start gap-4 rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-zinc-800">
                    <div class="aspect-square size-20 shrink-0 overflow-hidden rounded-lg bg-neutral-100 dark:bg-zinc-700">
                        @if ($request->pet->primaryImage)
                            <img src="{{ $request->pet->primaryImage->image_path }}" alt="{{ $request->pet->name }}" class="size-full object-cover" />
                        @else
                            <div class="flex size-full items-center justify-center text-neutral-400">
                                <flux:icon name="image" class="size-8" />
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <flux:heading class="text-base">
                                    <a href="{{ route('pets.detail', $request->pet) }}" wire:navigate class="hover:underline">
                                        {{ $request->pet->name }}
                                    </a>
                                </flux:heading>
                                <flux:text class="text-sm">
                                    {{ $request->pet->species->name }} &middot; {{ $request->organization->name }}
                                </flux:text>
                            </div>
                            <flux:badge
                                size="sm"
                                color="{{ $request->status === 'pending' ? 'amber' : ($request->status === 'in_progress' ? 'blue' : ($request->status === 'approved' ? 'emerald' : 'red')) }}"
                            >
                                @switch($request->status)
                                    @case('pending') {{ __('Pendiente') }} @break
                                    @case('in_progress') {{ __('En curso') }} @break
                                    @case('approved') {{ __('Aprobada') }} @break
                                    @case('cancelled') {{ __('Cancelada') }} @break
                                    @default {{ __('Rechazada') }}
                                @endswitch
                            </flux:badge>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                            <flux:text class="text-neutral-500 dark:text-neutral-400">
                                <span class="font-medium">{{ __('Teléfono') }}:</span> {{ $request->phone }}
                            </flux:text>
                            <flux:text class="text-neutral-500 dark:text-neutral-400">
                                <span class="font-medium">{{ __('Vivienda') }}:</span> {{ $request->housing_type === 'house' ? __('Casa') : __('Departamento') }}
                            </flux:text>
                            <flux:text class="text-neutral-500 dark:text-neutral-400">
                                <span class="font-medium">{{ __('Espacio exterior') }}:</span> {{ $request->has_outdoor_space ? __('Sí') : __('No') }}
                            </flux:text>
                            <flux:text class="text-neutral-500 dark:text-neutral-400">
                                <span class="font-medium">{{ __('Otras mascotas') }}:</span>
                                @if ($request->has_other_pets && $request->other_pets_details)
                                    @php
                                        $types = json_decode($request->other_pets_details, true) ?? [];
                                        $labels = ['dog' => 'Perro', 'cat' => 'Gato', 'rodent' => 'Roedor', 'bird' => 'Ave', 'other' => 'Otro'];
                                    @endphp
                                    {{ collect($types)->map(fn($t) => $labels[$t] ?? $t)->implode(', ') }}
                                @else
                                    {{ __('No') }}
                                @endif
                            </flux:text>
                            <flux:text class="text-neutral-500 dark:text-neutral-400">
                                <span class="font-medium">{{ __('Experiencia') }}:</span>
                                {{ $request->previous_experience ? __('Sí') : __('No') }}
                            </flux:text>
                        </div>

                        <div class="mt-2 rounded-lg bg-neutral-50 p-3 dark:bg-zinc-700/50">
                            <flux:text class="text-xs font-medium text-neutral-500 dark:text-neutral-400">{{ __('Tu mensaje') }}</flux:text>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                {{ $request->message }}
                            </p>
                        </div>

                        @if ($request->response)
                            <div class="mt-2 rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                                <flux:text class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ __('Respuesta del refugio') }}</flux:text>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $request->response }}
                                </p>
                            </div>
                        @endif

                        <div class="mt-2 flex items-center gap-2">
                            <flux:text class="text-xs text-neutral-400">
                                {{ $request->created_at->isoFormat('LL') }}
                            </flux:text>
                            @if (in_array($request->status, ['pending', 'in_progress']))
                                <span class="text-neutral-300 dark:text-neutral-600">&middot;</span>
                                <flux:button
                                    wire:click="cancel({{ $request->id }})"
                                    wire:confirm="{{ __('¿Cancelar esta solicitud?') }}"
                                    variant="ghost"
                                    size="xs"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    {{ __('Cancelar solicitud') }}
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

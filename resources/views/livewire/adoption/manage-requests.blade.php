<div>
    <div class="mb-6 flex items-center justify-between">
        <flux:heading size="xl" level="1">{{ __('Solicitudes de adopción') }}</flux:heading>
        <flux:select wire:model.live="statusFilter" class="w-44">
            <option value="">{{ __('Todas') }}</option>
            <option value="pending">{{ __('Pendientes') }}</option>
            <option value="in_progress">{{ __('En curso') }}</option>
            <option value="approved">{{ __('Aprobadas') }}</option>
            <option value="rejected">{{ __('Rechazadas') }}</option>
        </flux:select>
    </div>

    @if ($requests->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <flux:icon name="inbox" class="mb-4 size-12 text-neutral-300 dark:text-neutral-600" />
            <flux:heading class="mb-2 text-lg">{{ __('No hay solicitudes') }}</flux:heading>
            <flux:text>{{ __('Aún no recibiste solicitudes de adopción.') }}</flux:text>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($requests as $request)
                <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-zinc-800">
                    <div class="flex items-start gap-4">
                        <div class="aspect-square size-16 shrink-0 overflow-hidden rounded-lg bg-neutral-100 dark:bg-zinc-700">
                            @if ($request->pet->primaryImage)
                                <img src="{{ $request->pet->primaryImage->image_path }}" alt="" class="size-full object-cover" />
                            @else
                                <div class="flex size-full items-center justify-center text-neutral-400">
                                    <flux:icon name="image" class="size-6" />
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <flux:heading class="text-base">{{ $request->pet->name }}</flux:heading>
                                    <flux:text class="text-sm">
                                        {{ $request->user->name }} &middot;
                                        <a href="mailto:{{ $request->user->email }}" class="hover:underline">{{ $request->user->email }}</a>
                                    </flux:text>
                                </div>
                                <flux:badge
                                    size="sm"
                                    color="{{ $request->status === 'pending' ? 'amber' : ($request->status === 'in_progress' ? 'blue' : ($request->status === 'approved' ? 'emerald' : 'red')) }}"
                                >
                                    {{ $request->status === 'pending' ? __('Pendiente') : ($request->status === 'in_progress' ? __('En curso') : ($request->status === 'approved' ? __('Aprobada') : __('Rechazada'))) }}
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
                                    <span class="font-medium">{{ __('Niños') }}:</span> {{ $request->has_children ? __('Sí') : __('No') }}
                                </flux:text>
                                <flux:text class="text-neutral-500 dark:text-neutral-400">
                                    <span class="font-medium">{{ __('Experiencia previa') }}:</span>
                                    {{ $request->previous_experience ? __('Sí') : __('No') }}
                                </flux:text>
                            </div>

                            <div class="mt-3 rounded-lg bg-neutral-50 p-3 dark:bg-zinc-700/50">
                                <flux:text class="text-xs font-medium text-neutral-500 dark:text-neutral-400">{{ __('Mensaje del adoptante') }}</flux:text>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $request->message }}
                                </p>
                            </div>

                            @if ($request->response)
                                <div class="mt-2 rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                                    <flux:text class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ __('Tu respuesta') }}</flux:text>
                                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                        {{ $request->response }}
                                    </p>
                                </div>
                            @endif

                            <div class="mt-3 flex items-center gap-2 text-xs text-neutral-400">
                                <span>{{ $request->created_at->isoFormat('LL') }}</span>
                                @if ($request->notes)
                                    <span>&middot;</span>
                                    <flux:icon name="file-text" class="size-3" />
                                    <span>{{ __('Tiene notas') }}</span>
                                @endif
                            </div>

                            @if ($request->status === 'pending')
                                <div class="mt-3 flex gap-2">
                                    <flux:button
                                        wire:click="receive({{ $request->id }})"
                                        variant="primary"
                                        size="xs"
                                        wire:confirm="{{ __('¿Recibir esta solicitud?') }}"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ __('Recibir') }}
                                    </flux:button>
                                    <flux:button
                                        wire:click="reject({{ $request->id }})"
                                        variant="danger"
                                        size="xs"
                                        wire:confirm="{{ __('¿Rechazar esta solicitud?') }}"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ __('Rechazar') }}
                                    </flux:button>
                                    <flux:button
                                        wire:click="editNotes({{ $request->id }})"
                                        variant="ghost"
                                        size="xs"
                                    >
                                        {{ __('Notas') }}
                                    </flux:button>
                                </div>
                            @elseif ($request->status === 'in_progress')
                                <div class="mt-3 flex gap-2">
                                    <flux:button
                                        wire:click="editRespond({{ $request->id }})"
                                        variant="primary"
                                        size="xs"
                                    >
                                        {{ $request->response ? __('Editar respuesta') : __('Responder') }}
                                    </flux:button>
                                    <flux:button
                                        wire:click="approve({{ $request->id }})"
                                        variant="primary"
                                        size="xs"
                                        wire:confirm="{{ __('¿Aprobar esta solicitud? La mascota se marcará como adoptada.') }}"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ __('Aprobar') }}
                                    </flux:button>
                                    <flux:button
                                        wire:click="reject({{ $request->id }})"
                                        variant="danger"
                                        size="xs"
                                        wire:confirm="{{ __('¿Rechazar esta solicitud?') }}"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ __('Rechazar') }}
                                    </flux:button>
                                    <flux:button
                                        wire:click="editNotes({{ $request->id }})"
                                        variant="ghost"
                                        size="xs"
                                    >
                                        {{ __('Notas') }}
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($respondRequestId === $request->id)
                        <div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-700">
                            <form wire:submit="respond({{ $request->id }})" class="flex gap-2">
                                <flux:textarea
                                    wire:model="responseText"
                                    :label="__('Respuesta al adoptante')"
                                    :placeholder="__('Escribí un mensaje para el adoptante...')"
                                    rows="3"
                                    class="flex-1"
                                />
                                <div class="flex items-end gap-2">
                                    <flux:button type="submit" variant="primary" size="sm" wire:loading.attr="disabled">
                                        {{ __('Enviar') }}
                                    </flux:button>
                                    <flux:button wire:click="$set('respondRequestId', null)" variant="ghost" size="sm">
                                        {{ __('Cerrar') }}
                                    </flux:button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($selectedRequestId === $request->id)
                        <div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-700">
                            <form wire:submit="saveNotes" class="flex gap-2">
                                <flux:textarea
                                    wire:model="notes"
                                    :label="__('Notas internas')"
                                    rows="2"
                                    class="flex-1"
                                />
                                <div class="flex items-end gap-2">
                                    <flux:button type="submit" variant="primary" size="sm" wire:loading.attr="disabled">
                                        {{ __('Guardar') }}
                                    </flux:button>
                                    <flux:button wire:click="$set('selectedRequestId', null)" variant="ghost" size="sm">
                                        {{ __('Cerrar') }}
                                    </flux:button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif
</div>

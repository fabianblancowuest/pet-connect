<?php

namespace App\Livewire\Adoption;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Gestionar solicitudes de adopción')]
#[Layout('layouts.app')]
class ManageRequests extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public string $notes = '';

    public string $responseText = '';

    public ?int $selectedRequestId = null;

    public ?int $respondRequestId = null;

    protected $queryString = ['statusFilter' => ['except' => '']];

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function requests()
    {
        $orgIds = auth()->user()->organizations()->pluck('id');

        return AdoptionRequest::query()
            ->whereIn('organization_id', $orgIds)
            ->with(['pet.species', 'pet.primaryImage', 'user', 'organization'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.adoption.manage-requests', [
            'requests' => $this->requests(),
        ]);
    }

    public function receive(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('receive', $request);

        if ($request->status !== AdoptionRequest::STATUS_PENDING) {
            Flux::toast(variant: 'error', text: __('La solicitud ya fue procesada.'));
            return;
        }

        $request->update(['status' => AdoptionRequest::STATUS_IN_PROGRESS]);
        Flux::toast(variant: 'success', text: __('Solicitud recibida.'));
    }

    public function respond(int $id): void
    {
        $this->validate(['responseText' => 'required|string|min:10|max:2000']);

        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('respond', $request);

        $updateData = ['response' => $this->responseText];
        if ($request->status === AdoptionRequest::STATUS_PENDING) {
            $updateData['status'] = AdoptionRequest::STATUS_IN_PROGRESS;
        }
        $request->update($updateData);

        $this->reset('respondRequestId', 'responseText');
        Flux::toast(variant: 'success', text: __('Respuesta enviada al adoptante.'));
    }

    public function approve(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('approve', $request);

        if ($request->pet->status === Pet::STATUS_ADOPTED) {
            Flux::toast(variant: 'error', text: __('Esta mascota ya fue adoptada.'));
            return;
        }

        DB::transaction(function () use ($request) {
            $request->update(['status' => AdoptionRequest::STATUS_APPROVED]);
            $request->pet->update([
                'status' => Pet::STATUS_ADOPTED,
                'adopted_by_user_id' => $request->user_id,
            ]);
        });

        Flux::toast(variant: 'success', text: __('Solicitud aprobada.'));
    }

    public function reject(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('reject', $request);
        $request->update(['status' => AdoptionRequest::STATUS_REJECTED]);
        Flux::toast(text: __('Solicitud rechazada.'));
    }

    public function saveNotes(): void
    {
        $request = AdoptionRequest::findOrFail($this->selectedRequestId);
        $this->authorize('updateNotes', $request);
        $request->update(['notes' => $this->notes]);
        $this->reset('selectedRequestId', 'notes');
        Flux::toast(variant: 'success', text: __('Notas guardadas.'));
    }

    public function editNotes(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('updateNotes', $request);
        $this->selectedRequestId = $request->id;
        $this->notes = $request->notes ?? '';
    }

    public function editRespond(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorize('respond', $request);
        $this->respondRequestId = $request->id;
        $this->responseText = $request->response ?? '';
    }
}

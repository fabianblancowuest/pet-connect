<?php

namespace App\Livewire\Adoption;

use App\Models\AdoptionRequest;
use Flux\Flux;
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

    public ?int $selectedRequestId = null;

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

    public function approve(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorizeOrg($request);
        $request->update(['status' => 'approved']);
        Flux::toast(variant: 'success', text: __('Solicitud aprobada.'));
    }

    public function reject(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorizeOrg($request);
        $request->update(['status' => 'rejected']);
        Flux::toast(text: __('Solicitud rechazada.'));
    }

    public function saveNotes(): void
    {
        $request = AdoptionRequest::findOrFail($this->selectedRequestId);
        $this->authorizeOrg($request);
        $request->update(['notes' => $this->notes]);
        $this->reset('selectedRequestId', 'notes');
        Flux::toast(variant: 'success', text: __('Notas guardadas.'));
    }

    public function editNotes(int $id): void
    {
        $request = AdoptionRequest::findOrFail($id);
        $this->authorizeOrg($request);
        $this->selectedRequestId = $request->id;
        $this->notes = $request->notes ?? '';
    }

    protected function authorizeOrg(AdoptionRequest $request): void
    {
        $orgIds = auth()->user()->organizations()->pluck('id');
        abort_unless($orgIds->contains($request->organization_id), 403);
    }
}

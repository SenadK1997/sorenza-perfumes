<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Perfume;
use Livewire\Attributes\Layout;

class PerfumeQuickPreview extends Component
{
    #[Layout('layouts.shop')]
    public ?Perfume $perfume = null;
    public ?Perfume $selectedPerfume = null;
    public bool $showModal = false;

    protected $listeners = ['openQuickPreview'];

    public function openQuickPreview($perfumeId)
    {
        $this->selectedPerfume = Perfume::find($perfumeId);
        $this->showModal = true;
    }

    // Close modal
    public function closeQuickPreview()
    {
        $this->selectedPerfume = null;
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.perfume-quick-preview');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

class OrderDetailLivewire extends Component
{
    #[Layout('layouts.app')]

    public $order;
    public $pretty_id;

    public function mount($pretty_id)
    {
        $this->pretty_id = $pretty_id;
        
        // Find the order or show 404 if invalid ID
        $this->order = Order::with('perfumes')->where('pretty_id', $pretty_id)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-detail-livewire');
    }
}
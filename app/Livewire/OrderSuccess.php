<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

class OrderSuccess extends Component
{
    #[Layout('layouts.app')]
    public $order;

    public function mount($id)
    {
        // We find the order by the pretty_id (e.g., SOR-ABC123)
        $this->order = Order::where('pretty_id', $id)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-success');
    }
}

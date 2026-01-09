<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

class TrackOrder extends Component
{
    #[Layout('layouts.app')]

    public $pretty_id;

    public function findOrder()
    {
        // 1. Basic validation
        $this->validate([
            'pretty_id' => 'required|string|min:4'
        ]);

        // 2. Format the ID (e.g., in case they typed lowercase or omitted 'SOR-')
        $searchId = strtoupper(trim($this->pretty_id));

        // 3. Check if it exists
        $order = Order::where('pretty_id', $searchId)->first();

        if ($order) {
            return redirect()->route('order.track', $order->pretty_id);
        }

        // 4. If not found, add an error manually
        $this->addError('pretty_id', 'Narudžba sa ovim brojem nije pronađena.');
    }

    public function render()
    {
        return view('livewire.track-order');
    }
}

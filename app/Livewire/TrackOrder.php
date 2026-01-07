<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

class TrackOrder extends Component
{
    #[Layout('layouts.app')]

    public $step = 1;
    public $email;
    public $orders = [];

    public function findOrders()
    {
        $this->validate([
            'email' => 'required|email'
        ]);

        $this->orders = Order::where('email', $this->email)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->step = 2;
    }

    public function render()
    {
        return view('livewire.track-order');
    }
}

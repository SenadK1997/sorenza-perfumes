<?php

namespace App\Livewire\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

class LoginPage extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.customer.login');
    }
}

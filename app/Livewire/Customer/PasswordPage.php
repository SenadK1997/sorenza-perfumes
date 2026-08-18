<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PasswordPage extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function save(): void
    {
        $customer = Auth::guard('customer')->user();

        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // Only require current password if one is already set
        if ($customer->password) {
            $rules['current_password'] = 'required|string';
        }

        $this->validate($rules);

        if ($customer->password && !Hash::check($this->current_password, $customer->password)) {
            $this->addError('current_password', 'Trenutna lozinka nije ispravna.');
            return;
        }

        $customer->update(['password' => $this->password]);
        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('status', 'Lozinka je promijenjena.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.customer.password', [
            'customer' => Auth::guard('customer')->user(),
        ]);
    }
}

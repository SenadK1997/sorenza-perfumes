<?php

namespace App\Livewire\Customer;

use App\Enums\Canton;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AddressPage extends Component
{
    public string $full_name = '';
    public string $phone = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $zipcode = '';
    public string $canton = '';

    public function mount(): void
    {
        $c = Auth::guard('customer')->user();

        $this->full_name      = (string) $c->full_name;
        $this->phone          = (string) $c->phone;
        $this->address_line_1 = (string) $c->address_line_1;
        $this->address_line_2 = (string) $c->address_line_2;
        $this->city           = (string) $c->city;
        $this->zipcode        = (string) $c->zipcode;
        $this->canton         = (string) $c->canton;
    }

    public function save(): void
    {
        $data = $this->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:64',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:120',
            'zipcode'        => 'required|string|max:16',
            'canton'         => 'required|string|max:64',
        ]);

        Auth::guard('customer')->user()->update($data);

        session()->flash('status', 'Adresa je sačuvana.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.customer.address', [
            'customer' => Auth::guard('customer')->user(),
            'cantons'  => Canton::cases(),
        ]);
    }
}

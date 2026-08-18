<?php

namespace App\Livewire\Customer;

use App\Models\CustomerMessageThread;
use App\Services\Messaging;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MessagesPage extends Component
{
    public string $body = '';

    public function mount(): void
    {
        Messaging::markCustomerRead(Auth::guard('customer')->user());
    }

    public function send(): void
    {
        $this->validate([
            'body' => 'required|string|min:1|max:5000',
        ]);

        Messaging::customerReply(
            Auth::guard('customer')->user(),
            trim($this->body)
        );

        $this->body = '';
        session()->flash('status', 'Poruka poslata.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $customer = Auth::guard('customer')->user();

        $thread = CustomerMessageThread::firstWhere('customer_id', $customer->id);

        $messages = $thread
            ? $thread->messages()->orderBy('created_at')->get()
            : collect();

        return view('livewire.customer.messages', [
            'customer' => $customer,
            'thread'   => $thread,
            'messages' => $messages,
        ]);
    }
}

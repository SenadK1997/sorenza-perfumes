<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerMagicLink extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $customer;
    public string $token;

    public function __construct(Customer $customer, string $token)
    {
        $this->customer = $customer;
        $this->token    = $token;
    }

    public function build()
    {
        return $this->subject('Vaš link za prijavu — Sorénza')
            ->view('emails.customer-magic-link')
            ->with([
                'customer' => $this->customer,
                'url'      => route('customer.login.consume', ['token' => $this->token]),
                'ttl'      => \App\Services\CustomerMagicLink::TOKEN_TTL_MINUTES,
            ]);
    }
}

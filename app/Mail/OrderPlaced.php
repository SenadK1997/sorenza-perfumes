<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing('perfumes');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hvala na narudžbi #' . $this->order->pretty_id . ' — Sorénza Parfemi',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-placed',
            with: [
                'order'    => $this->order,
                'trackUrl' => route('order.track', ['pretty_id' => $this->order->pretty_id]),
            ],
        );
    }
}

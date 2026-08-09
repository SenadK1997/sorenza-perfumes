<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Services\ShippingCalculator;
use Livewire\Attributes\Layout;

class OrderDetailLivewire extends Component
{
    #[Layout('layouts.app')]

    public $order;
    public $pretty_id;

    public bool $showRefundForm = false;
    public string $refundReason = '';

    public function mount($pretty_id)
    {
        $this->pretty_id = $pretty_id;
        $this->order = Order::with(['perfumes', 'latestRefundRequest'])
            ->where('pretty_id', $pretty_id)
            ->firstOrFail();
    }

    public function toggleRefundForm(): void
    {
        $this->showRefundForm = ! $this->showRefundForm;
        $this->resetErrorBag();
    }

    public function submitRefund(): void
    {
        $this->validate([
            'refundReason' => 'required|string|min:10|max:1000',
        ], [
            'refundReason.required' => 'Molimo unesite razlog za povrat.',
            'refundReason.min'      => 'Razlog mora sadržavati najmanje 10 karaktera.',
        ]);

        // Reload with latest refund request to prevent double-submit
        $this->order->refresh();
        $this->order->load('latestRefundRequest');

        if (! $this->canRequestRefund()) {
            $this->addError('refundReason', 'Zahtjev za povrat trenutno nije moguće poslati.');
            return;
        }

        RefundRequest::create([
            'order_id'       => $this->order->id,
            'user_id'        => $this->order->user_id,
            'customer_name'  => $this->order->full_name,
            'customer_email' => $this->order->email,
            'customer_phone' => $this->order->phone,
            'reason'         => $this->refundReason,
            'status'         => 'pending',
        ]);

        $this->refundReason = '';
        $this->showRefundForm = false;
        $this->order->load('latestRefundRequest');

        session()->flash('refund_success', 'Vaš zahtjev za povrat je uspješno poslan. Kontaktirat ćemo Vas uskoro.');
    }

    /** Number shown to the customer ("besplatni povrat do X dana"). */
    public function getRefundDaysProperty(): int
    {
        return ShippingCalculator::refundDays();
    }

    /**
     * True while the customer can still submit a refund request. Uses a silent
     * effective window (>= 15 days) so slow deliveries don't burn their real time.
     */
    public function getWithinRefundWindowProperty(): bool
    {
        $effective = ShippingCalculator::refundDaysEffective();
        if ($effective <= 0) return false;
        return $this->order->created_at->diffInDays(now()) <= $effective;
    }

    public function getExistingRefundProperty(): ?RefundRequest
    {
        return $this->order->latestRefundRequest;
    }

    public function canRequestRefund(): bool
    {
        if ($this->existingRefund) return false;
        // Only completed (delivered) orders qualify — you can't return what you haven't received.
        if ($this->order->status?->value !== 'completed') return false;
        return $this->withinRefundWindow;
    }

    public function render()
    {
        return view('livewire.order-detail-livewire');
    }
}

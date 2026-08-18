<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerMessage;
use App\Models\CustomerMessageThread;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Messaging
{
    /**
     * Admin (any of the 4 admins) posts a message to a specific customer.
     * Customer only ever sees the identity "Sorenza".
     */
    public static function adminSendToCustomer(User $admin, Customer $customer, string $body, bool $isBroadcast = false): CustomerMessage
    {
        $thread = CustomerMessageThread::forCustomer($customer);

        return DB::transaction(function () use ($thread, $admin, $body, $isBroadcast) {
            $message = $thread->messages()->create([
                'direction'      => 'admin',
                'author_user_id' => $admin->id,
                'body'           => $body,
                'is_broadcast'   => $isBroadcast,
            ]);

            $thread->update([
                'last_message_at'       => $message->created_at,
                'customer_unread_count' => $thread->customer_unread_count + 1,
            ]);

            return $message;
        });
    }

    /**
     * Broadcast a message to every ACTIVE customer (those who actually bought).
     * Creates one message per thread (per-customer), so each customer can reply
     * independently and admins see distinct threads.
     */
    public static function broadcast(User $admin, string $body): int
    {
        $sent = 0;

        Customer::active()->chunkById(200, function ($customers) use ($admin, $body, &$sent) {
            foreach ($customers as $customer) {
                try {
                    self::adminSendToCustomer($admin, $customer, $body, isBroadcast: true);
                    $sent++;
                } catch (\Throwable $e) {
                    // Skip a customer that fails, keep the broadcast rolling.
                    \Illuminate\Support\Facades\Log::warning("Broadcast to customer {$customer->id} failed: " . $e->getMessage());
                }
            }
        });

        return $sent;
    }

    /**
     * Customer replies on their own thread.
     */
    public static function customerReply(Customer $customer, string $body): CustomerMessage
    {
        $thread = CustomerMessageThread::forCustomer($customer);

        return DB::transaction(function () use ($thread, $body) {
            $message = $thread->messages()->create([
                'direction' => 'customer',
                'body'      => $body,
            ]);

            $thread->update([
                'last_message_at'     => $message->created_at,
                'admin_unread_count'  => $thread->admin_unread_count + 1,
            ]);

            return $message;
        });
    }

    /**
     * Called when the customer opens their inbox — mark all admin messages read.
     */
    public static function markCustomerRead(Customer $customer): void
    {
        $thread = CustomerMessageThread::firstWhere('customer_id', $customer->id);
        if (!$thread) return;

        $now = now();
        $thread->messages()
            ->where('direction', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => $now]);

        $thread->update(['customer_unread_count' => 0]);
    }

    /**
     * Called when any admin opens a specific thread.
     */
    public static function markAdminRead(CustomerMessageThread $thread): void
    {
        $now = now();
        $thread->messages()
            ->where('direction', 'customer')
            ->whereNull('read_at')
            ->update(['read_at' => $now]);

        $thread->update(['admin_unread_count' => 0]);
    }

    /**
     * Unread admin messages for a customer (used for the /nalog navbar badge).
     */
    public static function unreadCountFor(Customer $customer): int
    {
        return (int) (CustomerMessageThread::where('customer_id', $customer->id)
            ->value('customer_unread_count') ?? 0);
    }

    /**
     * Total unread customer replies across all threads (for admin badge).
     */
    public static function totalAdminUnread(): int
    {
        return (int) CustomerMessageThread::sum('admin_unread_count');
    }
}

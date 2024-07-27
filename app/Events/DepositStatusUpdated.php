<?php

namespace App\Events;

use App\Models\Deposit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepositStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Deposit $deposit;

    /**
     * Create a new event instance.
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
    }
}

<?php

namespace App\Listeners;

use App\Enums\DepositStatusEnum;
use App\Events\DepositStatusUpdated;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserBalance implements ShouldQueue
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(DepositStatusUpdated $event): void
    {
        $deposit = $event->deposit;
        $user = $deposit->user;

        if ($deposit->status === DepositStatusEnum::APPROVED) {
            $this->userService->addBalance($user->id, $deposit->amount);
        }
    }
}

<?php

namespace Tests;

use App\Enums\DepositStatusEnum;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    public function createUser(mixed $data = []): User
    {
        $user = new User();
        $user->name = $data['name'] ?? fake()->name();
        $user->email = $data['email'] ?? fake()->email();
        $user->password = Hash::make($data['password'] ?? 'password');
        $user->balance = $data['balance'] ?? 0;
        $user->is_admin = $data['is_admin'] ?? false;
        $user->save();

        return $user;
    }

    public function createDeposit(mixed $data = []): Deposit
    {
        $deposit = Deposit::create([
            'user_id' => $data['user_id'] ?? $this->createUser()->id,
            'amount' => $data['amount'] ?? random_int(0, 10000),
            'status' => $data['status'] ?? DepositStatusEnum::WAITING_APPROVAL,
            'description' => $data['description'] ?? fake()->sentence(6),
            'image' => $data['image'] ?? 'test.jpg',
        ]);
        $deposit->save();

        return $deposit;
    }

    public function createOrder(mixed $data = []): Order
    {
        $order = new Order([
            'user_id' => $data['user_id'] ?? $this->createUser()->id,
            'amount' => $data['amount']?? random_int(0, 10000),
            'description' => $data['description']?? fake()->sentence(6),
        ]);
        $order->save();

        return $order;
    }
}

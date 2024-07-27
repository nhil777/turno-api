<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    public function createUser(bool $isAdmin = false, string $password = null, int $balance = 0): User
    {
        $user = new User();
        $user->name = fake()->name();
        $user->email = fake()->email();
        $user->email_verified_at = now();
        $user->password = $password ?? Hash::make('password');
        $user->balance = $balance;
        $user->is_admin = $isAdmin;
        $user->save();

        return $user;
    }
}

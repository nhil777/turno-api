<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Enums\DepositStatusEnum;
use App\Models\Deposit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepositControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_user_deposits()
    {
        $user = $this->createUser();
        $user2 = $this->createUser();

        $this->createDeposit(['user_id' => $user2->id]);

        $deposit1 = $this->createDeposit(['user_id' => $user->id]);
        $deposit2 = $this->createDeposit(['user_id' => $user->id]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->getJson(route('deposit.index'), ['Authorization' => 'Bearer '.$token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => ['data' => [
                    ['id' => $deposit2->id],
                    ['id' => $deposit1->id],
                ]]
            ]);
    }

    #[Test]
    public function it_stores_a_new_deposit()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->postJson(route('deposit.store'), [
            'amount' => 1000,
            'description' => 'Test deposit',
            'image' => UploadedFile::fake()->image('test.jpg'),
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id']]);
    }

    #[Group('Admin')]
    #[Test]
    public function it_list_all_deposits()
    {
        $user1 = $this->createUser(['is_admin' => true]);
        $user2 = $this->createUser();

        $deposit1 = $this->createDeposit(['user_id' => $user1->id]);
        $deposit2 = $this->createDeposit(['user_id' => $user2->id]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user1->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->getJson(route('deposit.index'), ['Authorization' => 'Bearer '.$token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => ['data' => [
                    ['id' => $deposit2->id],
                    ['id' => $deposit1->id],
                ]]
            ]);
    }

    #[Group('Admin')]
    #[Test]
    public function it_approves_a_deposit()
    {
        $user = $this->createUser(['is_admin' => true]);

        $deposit = $this->createDeposit(['user_id' => $user->id]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->patchJson(
            uri: route('deposit.approve', $deposit),
            headers: ['Authorization' => 'Bearer '.$token]
        );

        $response->assertStatus(200);

        $deposit = Deposit::first();
        $this->assertEquals(DepositStatusEnum::APPROVED, $deposit->status);
    }

    #[Group('Admin')]
    #[Test]
    public function it_rejects_a_deposit()
    {
        $user = $this->createUser(['is_admin' => true]);

        $deposit = $this->createDeposit(['user_id' => $user->id]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->patchJson(
            uri: route('deposit.reject', $deposit),
            headers: ['Authorization' => 'Bearer '.$token]
        );

        $response->assertStatus(200);

        $deposit = Deposit::first();
        $this->assertEquals(DepositStatusEnum::REJECTED, $deposit->status);
    }
}

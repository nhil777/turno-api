<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_list_user_orders()
    {
        $user = $this->createUser();

        $order1 = $this->createOrder(['user_id' => $user->id]);
        $order2 = $this->createOrder(['user_id' => $user->id]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->getJson(
            route('order.index'),
            ['Authorization' => 'Bearer '.$token]
        );

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['data' => [
                    ['id' => $order2->id],
                    ['id' => $order1->id],
                ]],
            ]);
    }

    #[Test]
    public function it_create_order_and_charge_user()
    {
        $user = $this->createUser(['balance' => 100]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->postJson(
            route('order.index'),
            [
                'amount' => ($user->balance / 2),
                'description' => 'Test Order',
            ],
            ['Authorization' => 'Bearer '.$token]
        );

        $order = Order::first();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id
                ]
            ]);

        $this->assertEquals(($user->balance / 2), $user->fresh()->balance);
    }
}

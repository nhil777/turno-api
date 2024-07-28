<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Order;
use App\Services\OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class OrderServiceTest extends TestCase
{
    protected $orderService;
    protected $orderRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepositoryMock = Mockery::mock(OrderRepositoryInterface::class);
        $this->orderService = new OrderService($this->orderRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function all()
    {
        $orders = Order::factory()->count(3)->make();

        $this->orderRepositoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn($orders);

        $result = $this->orderService->all();

        $this->assertEquals($orders, $result);
    }

    #[Test]
    public function create()
    {
        $orderData = Order::factory()->make()->toArray();
        $createdOrder = new Order($orderData);

        $this->orderRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($orderData)
            ->andReturn($createdOrder);

        $result = $this->orderService->create($orderData);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($createdOrder, $result);
    }

    #[Test]
    public function find()
    {
        $orderId = 1;
        $foundOrder = new Order(['id' => $orderId]);

        $this->orderRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($orderId)
            ->andReturn($foundOrder);

        $result = $this->orderService->find($orderId);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($foundOrder, $result);
    }
}

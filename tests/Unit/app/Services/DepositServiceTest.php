<?php

namespace Tests\Unit\App\Services;

use Tests\TestCase;
use App\Models\Deposit;
use App\Enums\DepositStatusEnum;
use App\Services\DepositService;
use App\Repositories\Interfaces\DepositRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class DepositServiceTest extends TestCase
{
    protected $depositService;
    protected $depositRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->depositRepositoryMock = Mockery::mock(DepositRepositoryInterface::class);
        $this->depositService = new DepositService($this->depositRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function all()
    {
        $deposits = Deposit::factory()->count(3)->make();

        $this->depositRepositoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn($deposits);

        $result = $this->depositService->all();

        $this->assertEquals($deposits, $result);
    }

    #[Test]
    public function create()
    {
        $depositData = Deposit::factory()->make()->toArray();
        $createdDeposit = new Deposit($depositData);

        $this->depositRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($depositData)
            ->andReturn($createdDeposit);

        $result = $this->depositService->create($depositData);

        $this->assertInstanceOf(Deposit::class, $result);
        $this->assertEquals($createdDeposit, $result);
    }

    #[Test]
    public function approve()
    {
        $depositId = 1;
        $updatedDeposit = new Deposit(['status' => DepositStatusEnum::APPROVED]);

        $this->depositRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with(['status' => DepositStatusEnum::APPROVED], $depositId)
            ->andReturn($updatedDeposit);

        $result = $this->depositService->approve($depositId);

        $this->assertInstanceOf(Deposit::class, $result);
        $this->assertEquals($updatedDeposit, $result);
    }

    #[Test]
    public function reject()
    {
        $depositId = 1;
        $updatedDeposit = new Deposit(['status' => DepositStatusEnum::REJECTED]);

        $this->depositRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with(['status' => DepositStatusEnum::REJECTED], $depositId)
            ->andReturn($updatedDeposit);

        $result = $this->depositService->reject($depositId);

        $this->assertInstanceOf(Deposit::class, $result);
        $this->assertEquals($updatedDeposit, $result);
    }

    #[Test]
    public function find()
    {
        $depositId = 1;
        $foundDeposit = new Deposit(['id' => $depositId]);

        $this->depositRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($depositId)
            ->andReturn($foundDeposit);

        $result = $this->depositService->find($depositId);

        $this->assertInstanceOf(Deposit::class, $result);
        $this->assertEquals($foundDeposit, $result);
    }
}

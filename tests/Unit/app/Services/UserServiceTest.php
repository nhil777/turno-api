<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class UserServiceTest extends TestCase
{
    protected $userService;
    protected $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function create()
    {
        $userData = User::factory()->make()->toArray();
        $createdUser = new User($userData);

        $this->userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andReturn($createdUser);

        $result = $this->userService->create($userData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($createdUser, $result);
    }

    #[Test]
    public function update()
    {
        $userData = User::factory()->make()->toArray();
        $updatedUser = new User($userData);
        $userId = 1;

        $this->userRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($userData, $userId)
            ->andReturn($updatedUser);

        $result = $this->userService->update($userData, $userId);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($updatedUser, $result);
    }

    #[Test]
    public function find()
    {
        $userId = 1;
        $foundUser = new User(['id' => $userId]);

        $this->userRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($foundUser);

        $result = $this->userService->find($userId);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($foundUser, $result);
    }

    #[Group("Balance")]
    #[Test]
    public function add()
    {
        $userId = 1;
        $amount = 100;
        $user = User::factory()->make(['balance' => 0]);

        $this->userRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $user->balance += $amount;

        $result = $this->userService->addBalance($userId, $amount);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->balance, $result->balance);
    }

    #[Group("Balance")]
    #[Test]
    public function remove()
    {
        $userId = 1;
        $amount = 100;
        $user = User::factory()->make(['balance' => 200]);

        $this->userRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $user->balance -= $amount;

        $result = $this->userService->removeBalance($userId, $amount);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->balance, $result->balance);
    }
}

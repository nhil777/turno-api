<?php

namespace App\Providers;

use App\Repositories\DepositRepository;
use App\Repositories\Interfaces\DepositRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\DepositService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });

        // Deposit
        $this->app->bind(DepositRepositoryInterface::class, DepositRepository::class);
        $this->app->bind(DepositService::class, function ($app) {
            return new DepositService($app->make(DepositRepositoryInterface::class));
        });

        // Order
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService($app->make(OrderRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

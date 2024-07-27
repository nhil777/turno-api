<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository
    ) {
    }

    public function all()
    {
        return $this->orderRepository->all();
    }

    public function create(array $data)
    {
        return $this->orderRepository->create($data);
    }

    public function find(int $id)
    {
        return $this->orderRepository->find($id);
    }
}

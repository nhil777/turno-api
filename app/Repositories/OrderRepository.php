<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function all()
    {
        return Order::orderBy('id', 'DESC')->simplePaginate(10);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update(array $data, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($data);
    }

    public function find($id)
    {
        return Order::findOrFail($id);
    }
}

<?php

namespace App\Repositories;

use App\Models\Deposit;
use App\Repositories\Interfaces\DepositRepositoryInterface;

class DepositRepository implements DepositRepositoryInterface
{
    public function all()
    {
        return Deposit::orderBy('id', 'DESC')->simplePaginate(10);
    }

    public function create(array $data)
    {
        return Deposit::create($data);
    }

    public function update(array $data, $id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->update($data);
    }

    public function find($id)
    {
        return Deposit::findOrFail($id);
    }
}

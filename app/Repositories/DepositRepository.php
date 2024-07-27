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

    public function create(array $data): Deposit
    {
        return Deposit::create($data);
    }

    public function update(array $data, $id): Deposit
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->update($data);

        return $deposit;
    }

    public function find($id): Deposit
    {
        return Deposit::findOrFail($id);
    }
}

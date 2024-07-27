<?php

namespace App\Repositories\Interfaces;

interface DepositRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, $id);
    public function find($id);
}

<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, $id);
    public function find($id);
}

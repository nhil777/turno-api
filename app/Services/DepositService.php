<?php

namespace App\Services;

use App\Enums\DepositStatusEnum;
use App\Models\User;
use App\Repositories\Interfaces\DepositRepositoryInterface;

class DepositService
{
    public function __construct(
        protected DepositRepositoryInterface $depositRepository
    ) {
    }

    public function all()
    {
        return $this->depositRepository->all();
    }

    public function create(array $data)
    {
        return $this->depositRepository->create($data);
    }

    public function approve(int $id)
    {
        return $this->depositRepository->update(['status' => DepositStatusEnum::APPROVED], $id);
    }

    public function reject(int $id)
    {
        return $this->depositRepository->update(['status' => DepositStatusEnum::REJECTED], $id);
    }

    public function find(int $id)
    {
        return $this->depositRepository->find($id);
    }
}

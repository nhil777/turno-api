<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, int $id): User
    {
        return $this->userRepository->update($data, $id);
    }

    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function addBalance(int $id, int $amount): User
    {
        $user = $this->userRepository->find($id);
        $user->balance += $amount;
        $user->save();

        return $user;
    }

    public function removeBalance(int $id, int $amount): User
    {
        $user = $this->userRepository->find($id);
        $user->balance -= $amount;
        $user->save();

        return $user;
    }

    public function orders(int $id)
    {
        $user = $this->userRepository->find($id);

        return $user->orders()->orderBy('id', 'DESC')->simplePaginate(10);
    }

    public function deposits(int $id)
    {
        $user = $this->userRepository->find($id);

        return $user->deposits()->orderBy('id', 'DESC')->simplePaginate(10);
    }
}

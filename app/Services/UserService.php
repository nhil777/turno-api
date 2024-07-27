<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, int $id)
    {
        return $this->userRepository->update($data, $id);
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function removeBalance(int $id, int $amount)
    {
        $user = $this->userRepository->find($id);
        $user->balance -= $amount;

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

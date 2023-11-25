<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $attributes): User;

    public function getByEmail(string $email): User|null;

    public function getById(int $id): User|null;
}

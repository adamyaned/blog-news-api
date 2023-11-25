<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepositoryRepository.
 */
class UserRepositoryRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return $this->model->create($attributes);
    }

    public function getByEmail(string $email): User|null
    {
        return $this->model->where('email', $email)->first();
    }

    public function getById(int $id): User|null
    {
        return $this->model->where('id', $id)->first();
    }
}

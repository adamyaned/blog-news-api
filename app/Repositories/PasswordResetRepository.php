<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use Carbon\Carbon;

final class PasswordResetRepository
    extends BaseRepository
    implements PasswordResetRepositoryInterface
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }

    public function findBy(string $email, string $token): PasswordReset|null
    {
        return $this->model
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->first();
    }

    public function findByEmail(string $email): PasswordReset|null
    {
        return $this->model
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->first();
    }

    public function findByTokenOrFailed(string $token): PasswordReset|null
    {
        return $this->model
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->firstOrFail();
    }

    public function deleteByEmail(string $email)
    {
        return $this->model->where('email', $email)->delete();
    }
}

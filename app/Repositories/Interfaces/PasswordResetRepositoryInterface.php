<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface PasswordResetRepositoryInterface extends BaseRepositoryInterface
{
    public function findBy(string $email, string $token);

    public function findByEmail(string $email);

    public function findByTokenOrFailed(string $token);

    public function deleteByEmail(string $email);
}

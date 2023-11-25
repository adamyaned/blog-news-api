<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function create(array $attributes): mixed;

    public function updateOrCreate(array $attributes, array $values = []): mixed;

    public function updateAndGet(string $field, int $id, array $attributes): mixed;

    public function get(): mixed;

    public function getPaginated(): mixed;

    public function find(int $id): mixed;

    public function delete(int $id): mixed;

    public function first(): mixed;

    public function last(): mixed;

    public function update(int $id, array $attributes): mixed;
}

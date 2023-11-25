<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected mixed $model;

    public function __construct(mixed $model = null)
    {
        $this->model = $model;
    }

    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    public function updateOrCreate(array $attributes, array $values = []): mixed
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function updateAndGet(string $field, int $id, array $attributes): mixed
    {
        return tap($this->model::where($field, $id))->update($attributes)->first();
    }

    public function get(): mixed
    {
        return $this->model->get();
    }

    public function getPaginated(): mixed
    {
        return $this->model->paginate(20);
    }

    public function find(int $id): mixed
    {
        return $this->model->find($id);
    }

    public function delete(int $id): mixed
    {
        return $this->model->find($id)->delete();
    }

    public function update(int $id, array $attributes): mixed
    {
        return $this->model->find($id)->update($attributes);
    }

    public function first(): mixed
    {
        return $this->model->first();
    }

    public function last(): mixed
    {
        return $this->model->orderByDesc('id')->first();
    }
}

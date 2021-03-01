<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    private Model $model;

    public function __construct(Model $model)
    {

        $this->model = $model;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes) : Model
    {
        $model = $this->model->find($id);
        $model->update($attributes);

        return $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
}

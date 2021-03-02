<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function create(array $attributes): Model;

    public function update(int $id, array $attributes): Model;

    public function find(int $id): ?Model;

    public function all(): Collection;


}

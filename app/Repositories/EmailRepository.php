<?php

namespace App\Repositories;

use App\Models\Email;

class EmailRepository extends BaseRepository implements EmailRepositoryInterface
{
    public function __construct(Email $model)
    {
        parent::__construct($model);
    }
}

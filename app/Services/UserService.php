<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    private User $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function getAll(): iterable
    {
         return $this->model::all();
    }
}

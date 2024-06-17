<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
    public function __construct(protected Role $model)
    {
    }

    public function roles($input)
    {
        return $this->model->when(isset($input['role_id']), function ($query) use ($input) {
            $query->where('id', $input['role_id']);
        })->get();
    }

    public function getUserByRole($input)
    {
        return $this->model->where('id', $input['role_id'])->first()->users;
    }
}

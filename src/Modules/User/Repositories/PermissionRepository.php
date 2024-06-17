<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRepository extends BaseRepository
{
    public function __construct(protected Permission $model)
    {
    }

    public function all()
    {
        return $this->model->get();
    }

    public function getUserByRole($input)
    {
        return $this->model->where('id', $input['role_id'])->first()->users;
    }
}

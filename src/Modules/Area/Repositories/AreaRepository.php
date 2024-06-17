<?php

namespace Modules\Area\Repositories;

use App\Repositories\BaseRepository;
use Modules\Area\Entities\Area;

class AreaRepository extends BaseRepository
{
    public function __construct(protected Area $model)
    {
    }

    public function index($input)
    {
        return $this->model->when(isset($input['area_id']), function ($query) use ($input) {
            $query->where('id', $input['area_id']);
        })->withCount('pharmacies')->get();
    }
}

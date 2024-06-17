<?php

namespace Modules\Area\Repositories;

use App\Repositories\BaseRepository;
use Modules\Area\Entities\City;

class CityRepository extends BaseRepository
{
    public function __construct(protected City $model)
    {
    }

    public function index($input)
    {
        return $this->model->withCount('pharmacies')->when(isset($input['city_id']) && $input['city_id'] != null, function ($query) use ($input) {
            $query->where('id', $input['city_id'])->with('areas', 'areas.tracks', 'areas.tracks.users');
        })->get();
    }
}

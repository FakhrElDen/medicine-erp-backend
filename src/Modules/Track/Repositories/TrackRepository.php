<?php

namespace Modules\Track\Repositories;

use App\Repositories\BaseRepository;
use Modules\Track\Entities\Track;

class TrackRepository extends BaseRepository
{
    public function __construct(protected Track $model)
    {
    }

    public function index($input)
    {
        return $this->model->when(isset($input['track_id']), function ($query) use ($input) {
            $query->where('id', $input['track_id'])->with('areas', 'users');
        })->withCount('pharmacies')->get();
    }
}

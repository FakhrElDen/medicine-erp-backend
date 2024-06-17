<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client;

class ClientRepository extends BaseRepository
{
    public function __construct(protected Client $model)
    {
    }

    public function get($input)
    {
        return $this->model->query()->applyFilters($input)
            ->with([
                'pharmacies' => [
                    'city',
                    'area',
                    'track',
                    'lists.users',
                    'track.shifts',
                ],
            ])->get();
    }

    public function view($input)
    {
        return $this->model->with([
            'pharmacies' => [
                'city',
                'area',
                'track',
                'lists.users',
                'track.shifts',
            ],
        ])->when(isset($input['id']) && !isset($input['code']), function ($query) use ($input) {
            $query->where('id', $input['id']);
        })->when(isset($input['code']) && !isset($input['id']), function ($query) use ($input) {
            $query->where('code', $input['code']);
        })->first();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function dropdown()
    {
        return $this->model->with('pharmacies')->get();
    }

    public function store($input)
    {
        return $this->model->create($input);
    }
}

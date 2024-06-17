<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Client\Entities\WaitingList;

class WaitingListRepository extends BaseRepository
{
    public function __construct(protected WaitingList $model)
    {
    }

    public function get()
    {
        $this->removeClientAfterOneDay();

        return $this->model->with('sales', 'client', 'pharmacy')->get();
    }

    public function removeClientAfterOneDay()
    {
        $clients = $this->model->where('created_at', '<', Carbon::now()->subHours(24))->get();
        foreach ($clients as $client) {
            $client->delete();
        }

    }

    public function create($input)
    {
        return $this->model->create($input)->load('sales', 'client', 'pharmacy');
    }

    public function destroyByPharmacyId($input)
    {
        return $this->model->where('pharmacy_id', $input['pharmacy_id'])->delete();
    }
}

<?php

namespace Modules\Warehouse\Repositories;

use App\Repositories\BaseRepository;
use Modules\Warehouse\Entities\Corridor;

class CorridorRepository extends BaseRepository
{
    const COLORS = [
        '#333333', '#BB0000', '#008253', '#1700A7', '#F6C000',
        '#3F497F', '#FF3EA5', '#FFBE98', '#265073', '#ECB159',
        '#59B4C3', '#944E63', '#FC6736', '#A94438', '#6DA4AA',
        '#76453B', '#5F0F40', '#2B2A4C', '#D0A2F7', '#6C5F5B',
    ];

    public function __construct(protected Corridor $model)
    {
    }

    public function get()
    {
        return $this->model->orderBy('number', 'asc')->get();
    }

    public function find($corridor_id)
    {
        return $this->model->find($corridor_id);
    }

    public function create($number)
    {
        $count = $this->model->count();

        $color = collect(self::COLORS)->skip($count % 20)->first();

        return $this->model->create([
            'number' => $number,
            'color' => $color,
        ]);
    }

    public function update($id, $number)
    {
        $corridor = $this->find($id);

        $corridor->update([
            'number' => $number,
        ]);

        return true;
    }
}

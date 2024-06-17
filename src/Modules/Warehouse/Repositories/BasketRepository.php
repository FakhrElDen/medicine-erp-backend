<?php

namespace Modules\Warehouse\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Warehouse\Entities\Basket;

class BasketRepository extends BaseRepository
{
    public function __construct(protected Basket $model)
    {
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->get();
    }

    public function get($status = null)
    {
        return $this->model->query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('number')
            ->get();
    }

    public function create($input)
    {
        DB::beginTransaction();

        $basket = $this->model->create($input);

        DB::commit();

        return $basket;
    }

    public function delete($basket_id)
    {
        return $this->model->where('id', $basket_id)->delete();
    }

    /**
     * update basket's order_id and corridor
     */
    public function complete(array $input): int
    {
        return $this->model->where([
            ['order_id', null],
            ['corridor_id', null],
        ])->whereIn('id', $input['basket_ids'])->update([
            'order_id' => $input['order_id'],
            'corridor_id' => $input['corridor_id'],
        ]);
    }

    public function getMaxBasketNumber($status = null): int
    {
        return $this->model->query()
            ->when(!is_null($status), fn ($q) => $q->where('status', $status))
            ->max('number') ?? 0;
    }

    public function checkBasketsTotalNumber($input)
    {
        $settings = collect(Cache::get('settings'));
        $basketsTotalNumber = $settings->firstWhere('key', 'baskets_number')->value;

        if ($input->number >= 1 && $input->number <= $basketsTotalNumber) {
            return true;
        } else {
            return false;
        }
    }
}

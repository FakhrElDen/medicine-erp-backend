<?php

namespace Modules\Warehouse\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Enums\WarehouseType;

class WarehouseRepository extends BaseRepository
{
    public function __construct(protected Warehouse $model)
    {
    }

    /**
     * If there's a product recently added and not assigned to any warehouse
     * then using setRelation() method to make a fake relation and flow continue as normal
     */
    public function get($input, $product)
    {
        $data = $this->model->when(isset($input['id']), function ($query) use ($input) {
            $query->where('id', $input['id']);
        })->when(isset($input['product_id']), function ($query) use ($input) {
            $query->withWhereHas('products', function ($query) use ($input) {
                $query->where('product_id', $input['product_id']);
            });
        })->get();

        if ($data->isEmpty() && $input['product_id']) {
            $warehouse = $this->find($input['id']);
            $warehouse->setRelation('products', collect([$product]));

            return new Collection([$warehouse]);
        }

        return $data;
    }

    public function mainWarehouse($input, $product)
    {
        $data = $this->model->when(isset($input['id']), function ($query) use ($input) {
            $query->where('id', $input['id']);
        })->when(isset($input['product_id']), function ($query) use ($input) {
            $query->withWhereHas('products', function ($query) use ($input) {
                $query->where('product_id', $input['product_id']);
            });
        })->where('type', WarehouseType::MAIN)->get();

        if ($data->isEmpty() && $input['product_id']) {
            $warehouse = $this->find($input['id']);
            $warehouse->setRelation('products', collect([$product]));

            return new Collection([$warehouse]);
        }

        return $data;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getSettlementWarehouse()
    {
        return $this->model->where('type', WarehouseType::SETTLEMENT)->first();
    }
}

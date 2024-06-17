<?php

namespace Modules\Order\Repositories;

use App\Repositories\BaseRepository;
use Modules\Order\Entities\Returnables;

class ReturnableRepository extends BaseRepository
{
    protected $model;

    public function __construct(Returnables $model)
    {
        $this->model = $model;
    }

    public function productQuantity(
        int $product_id,
        int $warehouse_id = null,
        string $from = null,
        string $to = null
    ) {
        return $this->model->query()
            ->select(['quantity', 'created_at'])
            ->filterByProductId($product_id)
            ->when($warehouse_id, fn ($q) => $q->filterByWarehouseId($warehouse_id))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('returnables.quantity');
    }

    public function get($input)
    {
        return $this->model->query()->applyFilters($input)
            ->when(isset($input['warehouse_id']), function ($query) use ($input) {
                $query->whereHas('return', function ($query) use ($input) {
                    $query->where('warehouse_id', $input['warehouse_id']);
                });
            })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
                $query->whereHas('return', function ($query) use ($input) {
                    $query->where('pharmacy_id', $input['pharmacy_id']);
                });
            })->when(isset($input['sort_by']), fn ($query) => $this->applySorts($query, $input))
            ->with([
                'return.warehouse',
                'return.user',
                'return.pharmacy',
            ])->paginate(10);
    }

    public function applySorts($query, $input)
    {
        $query->select('returnables.*');

        match ($input['sort_by']) {
            'product_name_ar', 'product_name_ar' => $query->joinProducts(),
            'manufacturer_ar', 'manufacturer_en' => $query->joinManufacturers(),
            'warehouse_id' => $query->joinWarehouses(),
            default => null,
        };

        $sorts = [
            'manufacturer_ar' => 'manufacturer_name->ar',
            'manufacturer_en' => 'manufacturer_name->en',
            'product_name_ar' => 'product_name->ar',
            'product_name_en' => 'product_name->en',
            'warehouse_id' => 'warehouses.name',
            'corridor_id' => 'returnables.id',      // TO DO
        ];

        $sort_by = $sorts[$input['sort_by']] ?? $input['sort_by'];

        $query->orderBy($sort_by, $input['direction']);
    }

    public function print()
    {
        return $this->model->with(['return.warehouse', 'return.user', 'return.pharmacy'])->get();
    }
}

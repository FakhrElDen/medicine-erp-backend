<?php

namespace Modules\Warehouse\Repositories;

use App\Events\TransfersCount;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Warehouse\Entities\BatchTransfer;
use Modules\Warehouse\Entities\Transfer;
use Modules\Warehouse\Enums\WarehouseType;

class TransferRepository extends BaseRepository
{
    public bool $pagination = true;

    public function __construct(protected Transfer $model, protected BatchTransfer $batchTransfer)
    {
    }

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate(10) : $query->get();
    }

    public function buildQueryForConfirmedTransfers($query, $input)
    {
        return $query->with('fromWarehouse', 'toWarehouse', 'user', 'batches.product.manufacturer', 'batches.product.warehouses', 'batches.corridor')
            ->whereDoesntHave('batches', function ($query) {
                $query->whereNull('batch_transfer.transferred_at');
            })
            ->withWhereHas('batches', function ($query) use ($input) {
                $query->whereNotNull('batch_transfer.transferred_at')
                    ->when(isset($input['from_date']), function ($query) use ($input) {
                        $query->whereDate('batch_transfer.transferred_at', '>=', $input['from_date']);
                    })
                    ->when(isset($input['to_date']), function ($query) use ($input) {
                        $query->whereDate('batch_transfer.transferred_at', '<=', $input['to_date']);
                    })
                    ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                        $query->whereHas('product.manufacturer', function ($query) use ($input) {
                            $query->where('products.manufacturer_id', $input['manufacturer_id']);
                        });
                    })
                    ->when(isset($input['corridor_id']), function ($query) use ($input) {
                        $query->whereHas('product.warehouses', function ($query) use ($input) {
                            $query->where('warehouses.type', WarehouseType::MAIN)
                                ->where('warehouse_product.corridor_id', $input['corridor_id']);
                        });
                    })
                    ->when(isset($input['product_id']), function ($query) use ($input) {
                        $query->whereHas('product', function ($query) use ($input) {
                            $query->where('batches.product_id', $input['product_id']);
                        });
                    });
            })
            ->when(isset($input['transfer_id']), function ($query) use ($input) {
                $query->where('id', $input['transfer_id']);
            })
            ->when(isset($input['transfer_number']), function ($query) use ($input) {
                $query->where('transfer_number', $input['transfer_number']);
            })
            ->when(isset($input['transfer_from_warehouse_id']), function ($query) use ($input) {
                $query->where('transfer_from_warehouse_id', $input['transfer_from_warehouse_id']);
            })
            ->when(isset($input['transfer_to_warehouse_id']), function ($query) use ($input) {
                $query->where('transfer_to_warehouse_id', $input['transfer_to_warehouse_id']);
            })
            ->when(isset($input['created_by']), function ($query) use ($input) {
                $query->where('created_by', $input['created_by']);
            });
    }

    public function confirmedOrdersTransferred($input)
    {
        $query = $this->model->query();
        $query = $this->buildQueryForConfirmedTransfers($query, $input);

        return $this->execute($query);
    }
}

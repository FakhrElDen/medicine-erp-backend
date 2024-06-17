<?php

namespace Modules\Warehouse\Repositories;

use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Warehouse\Entities\BatchTransfer;
use Modules\Warehouse\Enums\WarehouseType;

class BatchTransferRepository extends BaseRepository
{
    public bool $pagination = true;

    public function __construct(protected BatchTransfer $batchTransfer)
    {
    }

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate(10) : $query->get();
    }

    public function buildQueryForConfirmedBatchesTransfers($query, $input)
    {
        return $query->with('batch.product.manufacturer', 'batch.product.warehouses')
            ->whereNotNull('batch_transfer.transferred_at')
            ->when(isset($input['from_date']), function ($query) use ($input) {
                $query->whereDate('batch_transfer.transferred_at', '>=', $input['from_date']);
            })
            ->when(isset($input['to_date']), function ($query) use ($input) {
                $query->whereDate('batch_transfer.transferred_at', '<=', $input['to_date']);
            })
            ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('batch.product.manufacturer', function ($query) use ($input) {
                    $query->where('products.manufacturer_id', $input['manufacturer_id']);
                });
            })
            ->when(isset($input['corridor_id']), function ($query) use ($input) {
                $query->whereHas('batch.product.warehouses', function ($query) use ($input) {
                    $query->where('warehouses.type', WarehouseType::MAIN)
                        ->where('warehouse_product.corridor_id', $input['corridor_id']);
                });
            })
            ->when(isset($input['product_id']), function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('batches.product_id', $input['product_id']);
                });
            })
            ->withWhereHas('transfer', function ($query) use ($input) {
                $query->with('fromWarehouse', 'toWarehouse', 'user')
                    ->when(isset($input['transfer_id']), function ($query) use ($input) {
                        $query->where('transfers.id', $input['transfer_id']);
                    })
                    ->when(isset($input['transfer_number']), function ($query) use ($input) {
                        $query->where('transfers.transfer_number', $input['transfer_number']);
                    })
                    ->when(isset($input['transfer_from_warehouse_id']), function ($query) use ($input) {
                        $query->where('transfers.transfer_from_warehouse_id', $input['transfer_from_warehouse_id']);
                    })
                    ->when(isset($input['transfer_to_warehouse_id']), function ($query) use ($input) {
                        $query->where('transfers.transfer_to_warehouse_id', $input['transfer_to_warehouse_id']);
                    })
                    ->when(isset($input['created_by']), function ($query) use ($input) {
                        $query->where('transfers.created_by', $input['created_by']);
                    });
            });

    }

    public function confirmedProductsTransferred($input)
    {
        $query = $this->batchTransfer->query();
        $query = $this->buildQueryForConfirmedBatchesTransfers($query, $input);

        return $this->execute($query);
    }

    public function getConfirmedBatchesTransferred($input)
    {
        $query = $this->batchTransfer->query();
        $query = $this->buildQueryForConfirmedBatchesTransfers($query, $input);

        return $this->pagination ? $query->count() : $query->get()->count();
    }

    public function getUnconfirmedBatches($input)
    {
        $query = $this->batchTransfer->query()->whereNull('transferred_at')
            ->with('transfer.fromWarehouse', 'transfer.toWarehouse', 'transfer.user', 'batch.product.manufacturer', 'batch.product.warehouses', 'batch.corridor')
            ->whereHas('batch', function ($query) use ($input) {
                $query->when(isset($input['from_date']), function ($query) use ($input) {
                    $query->whereDate('batch_transfer.created_at', '>=', $input['from_date']);
                });
                $query->when(isset($input['to_date']), function ($query) use ($input) {
                    $query->whereDate('batch_transfer.created_at', '<=', $input['to_date']);
                });
            })
            ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('batch.product.manufacturer', function ($query) use ($input) {
                    $query->where('products.manufacturer_id', $input['manufacturer_id']);
                });
            })
            ->when(isset($input['corridor_id']), function ($query) use ($input) {
                $query->whereHas('batch.product.warehouses', function ($query) use ($input) {
                    $query->where('warehouses.type', WarehouseType::MAIN)
                        ->where('warehouse_product.corridor_id', $input['corridor_id']);
                });
            })
            ->when(isset($input['product_id']), function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('batches.product_id', $input['product_id']);
                });
            })
            ->whereHas('transfer', function ($query) use ($input) {
                $query->when(isset($input['transfer_number']), function ($query) use ($input) {
                    $query->where('transfers.transfer_number', $input['transfer_number']);
                })
                    ->when(isset($input['created_by']), function ($query) use ($input) {
                        $query->where('created_by', $input['created_by']);
                    })
                    ->when(isset($input['transfer_from_warehouse_id']), function ($query) use ($input) {
                        $query->where('transfers.transfer_from_warehouse_id', $input['transfer_from_warehouse_id']);
                    })
                    ->when(isset($input['transfer_to_warehouse_id']), function ($query) use ($input) {
                        $query->where('transfers.transfer_to_warehouse_id', $input['transfer_to_warehouse_id']);
                    })->when(isset($input['transfer_number']), function ($query) use ($input) {
                        $query->where('transfers.transfer_number', $input['transfer_number']);
                    });
            });

        return $this->execute($query);
    }

    public function confirmTransfers($input)
    {
        return $this->batchTransfer->query()->whereNull('transferred_at')->with('batch', 'transfer')
            ->when(isset($input['batch_transfer_id']), function ($query) use ($input) {
                $query->where('id', $input['batch_transfer_id']);
            })
            ->get()->map(function ($batch) {
                $batch->transferred_at = Carbon::now()->format('Y-m-d');
                $batch->update();
            });
    }

    public function getUnconfirmedBatchesCount()
    {
        return $this->batchTransfer->query()->whereNull('transferred_at')->count();
    }
}

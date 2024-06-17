<?php

namespace Modules\Warehouse\Repositories;

use App\Events\SettlementBatchesCount;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Product\Repositories\BatchHistoryRepository;
use Modules\Warehouse\DTOs\SettlementIndexDTO;
use Modules\Warehouse\Entities\CartSubBatchWarehouse;

class SettlementRepository extends BaseRepository
{
    public function __construct(
        protected CartSubBatchWarehouse $model,
        protected BatchHistoryRepository $batch_history_repository
    ) {}

    public function find($id): CartSubBatchWarehouse
    {
        return $this->model->query()->find($id);
    }

    public function index(SettlementIndexDTO $input)
    {
        $batches = $this->applyFilters($input)
            ->where('returned_quantity', null)
            ->paginate();

        Redis::set('settlement', $batches->total());

        return $batches;
    }

    public function finished(SettlementIndexDTO $input)
    {
        return $this->applyFilters($input)
            ->where('returned_quantity', '!=', null)
            ->paginate();
    }

    public function ordersCount(SettlementIndexDTO $input, bool $returned)
    {
        return $this->applyFilters($input)
            ->when($returned, fn ($q) => $q->whereNotNull('returned_quantity'), fn ($q) => $q->whereNull('returned_quantity'))
            ->orderId()
            ->get()
            ->unique('order_id')
            ->count();
    }

    public function update(int $id, int $returned_quantity): void
    {
        $settlement_batch = $this->find($id);
        $batch = $settlement_batch->cartSubBatch->batch;

        $original_quantity = $settlement_batch->cartSubBatch->quantity;

        $batch->current_quantity += $original_quantity;
        $settlement_cart_sub_batch = $this->batch_history_repository->findBySubject($settlement_batch?->cartSubBatch);
        $settlement_cart_sub_batch ? $settlement_cart_sub_batch->delete() : null;

        $quantity_missing = $original_quantity - $returned_quantity;

        if ($quantity_missing > 0) {
            $batch->current_quantity -= $quantity_missing;
            $batch->recordChangeInQuantity($quantity_missing * -1, BatchHistoryType::SETTLEMENT, $settlement_batch);
        }

        $batch->save();

        $settlement_batch->update(['returned_quantity' => $returned_quantity]);

        $count = Redis::decrby('settlement', 1);
        event(new SettlementBatchesCount($count, 'removed', $settlement_batch));
    }

    protected function applyFilters(SettlementIndexDTO $input)
    {
        return $this->model->query()
            ->when($input->has('product_id'), fn ($q) => $q->filterByProductId($input->product_id))
            ->when($input->has('warehouse_id'), fn ($q) => $q->filterByWarehouseId($input->warehouse_id))
            ->when($input->has('pharmacy_id'), fn ($q) => $q->filterByPharmacyId($input->pharmacy_id))
            ->when($input->has('reviewed_by'), fn ($q) => $q->filterByReviewerId($input->reviewed_by))
            ->when($input->has('from'), fn ($q) => $q->whereDate('created_at', '>=', $input->from))
            ->when($input->has('to'), fn ($q) => $q->whereDate('created_at', '<=', $input->to));
    }
}

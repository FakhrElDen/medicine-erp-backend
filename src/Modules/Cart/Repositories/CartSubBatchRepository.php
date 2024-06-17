<?php

namespace Modules\Cart\Repositories;

use App\Events\SettlementBatchesCount;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Order\Exceptions\OrderException;
use Modules\Product\Enums\ProductColor;

class CartSubBatchRepository extends BaseRepository
{
    public function __construct(protected CartSubBatch $model)
    {
    }

    public function updateBatchesInProgress($input)
    {
        $existingRecords = $this->model->whereIn('cart_id', array_column($input['batch_ids'], 'cart_id'))
            ->whereNull('completed_at')
            ->where('status', CartSubBatchStatus::IN_PROGRESS)
            ->get();

        if ($existingRecords->count() !== count($input['batch_ids'])) {
            throw new OrderException(trans('cart::message.order_is_completed'));

            return false;
        }

        foreach ($existingRecords as $record) {
            $this->updateBatchRecord($record);

            $batch = collect($input['batch_ids'])
                ->firstWhere('batch_id', $record->batch_id);

            if ($batch && $batch['cart_id'] == $record->cart_id) {
                $record->update([
                    'status' => $batch['status'],
                    'completed_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            } else {
                throw new OrderException(trans('cart::message.invalid_batch_data'));
            }
        }
    }

    public function updateBatchRecord($batch)
    {
        return $batch->update([
            'status' => CartSubBatchStatus::COMPLETED,
            'completed_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function updateDuplicatedBatch($batch, $input)
    {
        $duplicateBatchId = $batch->id;
        $batch = $this->findCompletedCartSubBatch($input);

        if ($batch) {
            $batch->update(['batch_id' => $duplicateBatchId]);
        } else {
            throw new OrderException(trans('cart::message.invalid_batch_data'));
        }
    }

    /**
     * duplicate ordered batch with:
     * quantity (difference between inserted quantity and ordered batch quantity)
     * status make it prepared (COMPLETED)
     */
    public function duplicateCartSubBatch($batchQuantity, $batch, $input)
    {
        $newBatch = $batch->replicate();
        $newBatch->quantity = $batchQuantity - $input['quantity'];
        $newBatch->bonus = 0;
        $newBatch->total = $this->calculateTotalForCartSubBatch($newBatch, $batchQuantity - $input['quantity']);
        $newBatch->status = CartSubBatchStatus::COMPLETED;
        $newBatch->completed_at = Carbon::now()->format('Y-m-d H:i:s');
        $newBatch->inventoried_at = null;
        $newBatch->inventoried_by = null;
        $newBatch->save();

        return $newBatch;
    }

    /**
     * find ordered batch in pivot table by cart_id and batch_id
     * where this ordered batch is has been prepared(COMPLETED)
     * OR (NOT_FOUND) in warehouse
     */
    public function findCompletedCartSubBatch($input)
    {
        return $this->model->where([
            ['batch_id', $input['batch_id']],
            ['cart_id', $input['cart_id']],
        ])->whereIn('status', [CartSubBatchStatus::COMPLETED, CartSubBatchStatus::NOT_FOUND])->first();
    }

    /**
     * first find the batch by findCompletedCartSubBatch() method
     * if it's exist will check on inserted quantity
     * if ordered batch quantity equal inserted quantity will be INVENTORIED
     * if not will update ordered batch quantity with inserted quantity
     * then using duplicateCartSubBatch() method to create new ordered batch
     */
    public function inventorying($input)
    {
        $batch = $this->findCompletedCartSubBatch($input);
        if ($batch) {
            $batchQuantity = $batch->quantity;

            if ($batchQuantity < $input['quantity']) {
                throw new OrderException('Invalid quantity.');
            }

            if ($batch->quantity == $input['quantity']) {
                $batch->update([
                    'status' => CartSubBatchStatus::INVENTORIED,
                    'inventoried_by' => auth()->id(),
                    'inventoried_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            } else {
                $batch->update([
                    'quantity' => $input['quantity'],
                    'status' => CartSubBatchStatus::INVENTORIED,
                    'inventoried_by' => auth()->id(),
                    'total' => $this->calculateTotalForCartSubBatch($batch, $input['quantity']),
                    'inventoried_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                $this->duplicateCartSubBatch($batchQuantity, $batch, $input);
            }
        } else {
            throw new OrderException(trans('cart::message.invalid_batch_data'));
        }
    }

    public function addBatchesToSettlementWarehouse($settlementWarehouse, $input)
    {
        $cart_sub_batches = $this->model->whereIn('id', $input['non_inventoried_batches_ids'])->get();

        $settlement_batches = $cart_sub_batches->map(function ($cart_sub_batch) use ($settlementWarehouse) {
            return $settlementWarehouse->settlementBatches()->create(['cart_sub_batch_id' => $cart_sub_batch->id]);
        });

        $count = Redis::incrBy('settlement', $settlement_batches->count());
        event(new SettlementBatchesCount($count, 'added', $settlement_batches));

        foreach ($cart_sub_batches as $cart_sub_batch) {
            $cart_sub_batch->update(['color' => ProductColor::getStringValue(ProductColor::IN_SETTLEMENT_WAREHOUSE)]);
        }

        return $cart_sub_batches;
    }

    public function calculateTotalForCartSubBatch($batch, $quantity)
    {
        $total = $batch->price * $quantity;
        $discount = $batch->discount / 100;
        $discount_value = $discount * $total;
        $total = $total - $discount_value;

        return $total;
    }

    public function inventoriedBatches($input)
    {
        return $this->model->where([
            ['cart_id', $input['cart_id']],
            ['inventoried_by', '!=', null],
            ['inventoried_at', '!=', null],
        ])->latest()->with('batch')->first();
    }

    public function nonInventoriedBatches($input)
    {
        return $this->model->where([
            ['cart_id', $input['cart_id']],
            ['inventoried_by', null],
            ['inventoried_at', null],
        ])->latest()->with('batch')->first();
    }

    public function delete($id)
    {
        $cart_sub_batch = $this->model->find($id);
        $cart_sub_batch->delete();

        return $cart_sub_batch;
    }

    public function checkItemInventoried($cartItem)
    {
        return $this->model->where('cart_id', $cartItem->id)
            ->where('status', CartSubBatchStatus::COMPLETED)
            ->first();
    }
}

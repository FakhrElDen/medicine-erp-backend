<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Modules\Cart\Entities\Cart;
use Modules\Product\Entities\SubBatch;
use Modules\Product\Enums\BatchHistoryType;

class SubBatchRepository extends BaseRepository
{
    public function __construct(protected SubBatch $model)
    {
        //
    }

    /**
     * Calculate quantity by adding a bonus to the ordered quantity.
     *
     * Map on batches to deduct the current quantity by ordered quantity.
     *
     * If the actual quantity is less than the ordered quantity it will continue to another batch.
     *
     * If not will deduct the current quantity and make the ordered quantity equal to zero to get in first if condition and get off the map.
     */
    public function getOrderedQuantityFromBatches($input, Cart $cartItem)
    {
        $quantity = intval($input['quantity']);

        $this->model->where('warehouse_id', $cartItem['warehouse_id'])
            ->where('current_quantity', '!=', 0)
            ->whereHas('parentBatch', function ($query) use ($cartItem) {
                $query->where('product_id', $cartItem['product_id'])->orderBy('expired_at', 'asc');
            })->get()
            ->map(function (SubBatch $batch) use (&$quantity, $cartItem) {
                if ($quantity == 0) {
                    $cartItem->update(['corridor_id' => $batch->corridor_id]);
                    return false;
                } elseif ($batch->current_quantity < $quantity) {
                    $quantity = $quantity - $batch->current_quantity;
                    $total = $this->calculateTotalForOrderedBatch($cartItem, $batch->current_quantity);
                    $cart_sub_batch = $cartItem->subBatches()->create([
                        'sub_batch_id' => $batch->id,
                        'cart_id' => $cartItem->id,
                        'quantity' => $batch->current_quantity,
                        'price' => $cartItem['price'],
                        'discount' => $cartItem['discount'],
                        'total' => $total,
                    ]);
                    $batch->updateQuantity(0, BatchHistoryType::SALES, $cart_sub_batch);

                    $cartItem->update(['corridor_id' => $batch->corridor_id]);
                } else {
                    $total = $this->calculateTotalForOrderedBatch($cartItem, $quantity);

                    $cart_sub_batch = $cartItem->subBatches()->attach($batch->id,[
                        // 'cart_id' => $cartItem->id,
                        'quantity' => $quantity,
                        'price' => $cartItem['price'],
                        'discount' => $cartItem['discount'],
                        'total' => $total,
                    ]);
                    $batch->decrement('current_quantity', $quantity);
                    $batch->recordChangeInQuantity($quantity * -1, BatchHistoryType::SALES, $cart_sub_batch);
                    $cartItem->update(['corridor_id' => $batch->corridor_id]);
                    $quantity = 0;
                }

                return $batch;
            });
    }

    public function getOrderedQuantityFromBatchesForItemHasOffer($input, Cart $cartItem)
    {
        $bonus = isset($input['bonus']) ? $input['bonus'] : 0;
        $found = 0;
        $quantity = intval($input['quantity'] +  $bonus);

        $this->model->where('warehouse_id', $cartItem['warehouse_id'])
            ->where('current_quantity', '!=', 0)
            ->whereHas('parentBatch', function ($query) use ($cartItem) {
                $query->where('product_id', $cartItem['product_id']);
            })->orderBy('expired_at', 'asc')->get()
            ->map(function (SubBatch $batch) use (&$quantity, $cartItem, $input, &$found) {

                if ($batch->current_quantity >= $quantity && $found == 0) {
                    $total = $this->calculateTotalForOrderedBatch($cartItem, $input['quantity']);
                    $cart_sub_batch = $cartItem->subBatches()->create([
                        'batch_id' => $batch->id,
                        'quantity' => $quantity,
                        'price' => $cartItem['price'],
                        'discount' => $cartItem['discount'],
                        'bonus' => $input['bonus'] ?? 0,
                        'total' => $total,
                    ]);
                    $batch->decrement('current_quantity', $quantity);
                    $batch->recordChangeInQuantity($quantity * -1, BatchHistoryType::SALES, $cart_sub_batch);
                    $cartItem->update(['corridor_id' => $batch->corridor_id]);
                    $found = 1;

                    return false;
                }

                return $batch;
            });
    }

    public function calculateTotalForOrderedBatch($cartItem, $quantity)
    {
        $total = $cartItem['price'] * $quantity;
        $discount = $cartItem['discount'] / 100;
        $discount_value = $discount * $total;
        $total = $total - $discount_value;

        return $total;
    }
}

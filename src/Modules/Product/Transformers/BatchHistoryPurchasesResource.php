<?php

namespace Modules\Product\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Modules\Product\Enums\BatchHistoryType;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class BatchHistoryPurchasesResource extends JsonResource
{
    use CheckNestedRelations;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request, $product_prices = null)
    {
        $product = $this->when(
            $this->checkRelation('batch.product'),
            fn () => new ProductResource($this->batch->product),
        );

        $price_after = $this->when(
            $this->checkRelation('subject'),
            fn () => $this->subject->public_price,
        );

        if ($product_prices && !$product instanceof MissingValue && !$price_after instanceof MissingValue) {
            $last_selling_price = $product_prices->where('type', BatchHistoryType::SALES)->last()?->price ?? $product->price;

            if ($last_selling_price == $price_after) {
                $price_before = $price_after;
            } else {
                $buying_prices = $product_prices->whereIn('type', [BatchHistoryType::PURCHASE, BatchHistoryType::SALES_RETURN]);

                $price_before = $buying_prices->where('price', $price_after)->isEmpty()
                    ? $last_selling_price
                    : $price_after;
            }
        }

        return [
            'id' => $this->id,
            'order_id' => $this->when(
                $this->checkRelation('subject'),
                fn () => $this->subject->purchase_id
            ),
            'supplier' => $this->when(
                $this->checkRelation('subject.purchase.supplier'),
                fn () => new UserResource($this->subject->purchase->supplier)
            ),
            'created_at' => $this->created_at,
            'product' => $product,
            'price_before' => $price_before,
            'price_after' => $price_after,
            'quantity_before' => $this->warehouse_product_quantity_before,
            'amount' => abs($this->amount),
            'quantity_after' => $this->warehouse_product_quantity_after,
            'batch' => new BatchResource($this->whenLoaded('batch')),
            'user' => new UserResource($this->whenLoaded('user')),
            'warehouse' => $this->when(
                $this->checkRelation('batch.warehouse'),
                fn () => new WarehouseResource($this->batch->warehouse)
            ),
        ];
    }
}

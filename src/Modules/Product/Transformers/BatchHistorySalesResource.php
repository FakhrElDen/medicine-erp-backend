<?php

namespace Modules\Product\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class BatchHistorySalesResource extends JsonResource
{
    use CheckNestedRelations;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->when(
                $this->checkRelation('subject.cart'),
                fn () => $this->subject->cart->order_id
            ),
            'pharmacy' => $this->when(
                $this->checkRelation('subject.cart.order.pharmacy'),
                fn () => new PharmacyResource($this->subject->cart->order->pharmacy)
            ),
            'created_at' => $this->created_at,
            'product' => $this->when(
                $this->checkRelation('batch.product'),
                fn () => new ProductResource($this->batch->product),
            ),
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

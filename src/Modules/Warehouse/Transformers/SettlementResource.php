<?php

namespace Modules\Warehouse\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Product\Transformers\BatchResource;
use Modules\Product\Transformers\ProductResource;
use Modules\User\Transformers\UserResource;

class SettlementResource extends JsonResource
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
        $cart = $this->checkRelation('cartSubBatch.cart') ? $this->cartSubBatch->cart : null;

        return [
            'id'                    => $this->id,
            'order_id'              => $this->when($this->checkRelation('cartSubBatch.cart'), fn () => $cart?->order_id),
            'product'               => new ProductResource(
                $this->when($this->checkRelation('cartSubBatch.cart.product'), fn () => $cart?->product)
            ),
            'from_warehouse'        => new WarehouseResource(
                $this->when($this->checkRelation('cartSubBatch.cart.warehouse'), fn () => $cart?->warehouse)
            ),
            'to_warehouse'          => new WarehouseResource(
                $this->whenLoaded('warehouse', fn () => $this->warehouse)
            ),
            'quantity'              => $this->whenLoaded('cartSubBatch', $this->cartSubBatch->quantity),
            'returned_quantity'     => $this->returned_quantity,
            'batch'                 => new BatchResource(
                $this->when($this->checkRelation('cartSubBatch.batch'), fn () => $this->cartSubBatch->batch)
            ),
            'pharmacy'              => new PharmacyResource(
                $this->when($this->checkRelation('cartSubBatch.cart.order.pharmacy'), fn () => $cart?->order->pharmacy)
            ),
            'created_at'            => $this->created_at,
            'reviewed_by'           => new UserResource(
                $this->when($this->checkRelation('cartSubBatch.cart.order.reviewedBy'), fn () => $cart?->order->reviewedBy)
            ),
            'status'                => $this->when(
                $this->returned_quantity !== null && $this->relationLoaded('cartSubBatch'),
                match ($this->returned_quantity) {
                    0                           => trans('warehouse::other.settlement.not-returned'),
                    $this->cartSubBatch->quantity  => trans('warehouse::other.settlement.returned'),
                    default                     => trans('warehouse::other.settlement.partially-returned'),
                }
            ),
        ];
    }
}

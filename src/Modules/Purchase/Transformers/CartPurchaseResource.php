<?php

namespace Modules\Purchase\Transformers;

use Modules\User\Transformers\UserResource;
use Modules\Purchase\Enums\CartPurchaseStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class CartPurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                            => $this->id,
            'discount'                      => $this->discount,
            'discount_value'                => $this->discount_value,
            'taxes'                         => $this->taxes,
            'subtotal'                      => $this->subtotal,
            'total'                         => $this->total,
            'bonus'                         => $this->bonus,
            'quantity'                      => $this->quantity,
            'public_price'                  => $this->public_price,
            'supply_price'                  => $this->supply_price,
            'purchase_number'               => $this->purchase_number,
            'status'                        => CartPurchaseStatus::getStringValue($this->status),
            'status_value'                  => $this->status,
            'note'                          => $this->note,
            'inventoried_quantity'          => $this->inventoried_quantity,
            'inventoried_quantity_price'    => $this->inventoried_quantity_price,
            'quantity_difference'           => $this->quantity - $this->inventoried_quantity,
            'created_at'                    => $this->created_at,
            'return'                        => $this->return ?? null,
            'product'                       => new ProductResource($this->whenLoaded('product')),
            'purchase'                      => new PurchaseResource($this->whenLoaded('purchase')),
            'warehouse'                     => new WarehouseResource($this->whenLoaded('warehouse')),
            'created_by'                    => new UserResource($this->whenLoaded('createdBy')),
        ];
    }
}

<?php

namespace Modules\Cart\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Enums\CartStatus;
use Modules\Client\Transformers\ClientResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Order\Transformers\OrderResource;
use Modules\Product\Transformers\BatchResourceCollection;
use Modules\Product\Transformers\ProductResource;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\CorridorResource;

class CartResource extends JsonResource
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
            'id'                           => $this->id,
            'quantity'                     => $this->quantity,
            'taxes'                        => $this->taxes,
            'total'                        => $this->total,
            'price'                        => $this->price,
            'client_discount_difference'   => $this->client_discount_difference,
            'status'                       => CartStatus::getStringValue($this->status),
            'bonus'                        => $this->bonus ?? 0,
            'discount'                     => $this->discount ?? 0,
            'cart_number'                  => $this->cart_number,
            'completed_at'                 => $this->completed_at,
            'color'                        => $this->color,
            'created_at'                   => $this->created_at,
            'order'                        => new OrderResource($this->whenLoaded('order')),
            'client'                       => new ClientResource($this->whenLoaded('client')),
            'pharmacy'                     => new PharmacyResource($this->whenLoaded('pharmacy')),
            'corridor'                     => new CorridorResource($this->whenLoaded('corridor')),
            'prepared_by'                  => new UserResource($this->whenLoaded('preparedBy')),
            'created_by'                   => new UserResource($this->whenLoaded('createdBy')),
            'product'                      => new ProductResource($this->whenLoaded('product')),
            'batches'                      => new BatchResourceCollection($this->whenLoaded('batches')),
        ];
    }
}

<?php

namespace Modules\Order\Transformers;

use Modules\User\Transformers\UserResource;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Cart\Transformers\CartResourceCollection;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Warehouse\Transformers\BasketResourceCollection;
use Modules\Warehouse\Transformers\CorridorResourceCollection;

class WarehouseOrderResource extends JsonResource
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
      'id'                        => $this->id,
      'total_quantity'            => $this->total_quantity,
      'total'                     => $this->total,
      'order_number'              => $this->order_number,
      'order_status'              => $this->status,
      'delivery_received_at'      => $this->delivery_received_at,
      'completed_at'              => $this->completed_at,
      'closed_at'                 => $this->closed_at,
      'created_at'                => $this->created_at,
      'corridors'                 => new CorridorResourceCollection($this->whenLoaded('corridors')),
      'client'                    => new ClientResource($this->whenLoaded('client')),
      'pharmacy'                  => new PharmacyResource($this->whenLoaded('pharmacy')),
      'created_by'                => new UserResource($this->whenLoaded('createdBy')),
      'warehouse'                 => new WarehouseResource($this->whenLoaded('warehouse')),
      'reviewed_by'               => new UserResource($this->whenLoaded('reviewedBy')),
      'baskets'                   => new BasketResourceCollection($this->whenLoaded('baskets')),
      'invoice'                   => new InvoiceResource($this->whenLoaded('invoice')),
      'cart'                      => new CartResourceCollection($this->whenLoaded('cart')),
    ];
  }
}

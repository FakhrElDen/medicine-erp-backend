<?php

namespace Modules\Order\Transformers;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\ShippingType;
use Modules\Area\Transformers\AreaResource;
use Modules\Area\Transformers\CityResource;
use Modules\User\Transformers\UserResource;
use Modules\Track\Transformers\ShiftResource;
use Modules\Track\Transformers\TrackResource;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Cart\Transformers\CartResourceCollection;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Warehouse\Transformers\BasketResourceCollection;

class OrderResource extends JsonResource
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
      'id'                              => $this->id,
      'total_price'                     => $this->total_price,
      'total_quantity'                  => $this->total_quantity,
      'total_taxes'                     => $this->total_taxes,
      'total'                           => $this->total,
      'note'                            => $this->note,
      'extra_discount'                  => $this->extra_discount,
      'order_number'                    => $this->order_number,
      'returns'                         => $this->returns,
      'latitude'                        => $this->latitude,
      'longitude'                       => $this->longitude,
      'created_at'                      => $this->created_at,
      'closed_at'                       => $this->closed_at,
      'order_status'                    => $this->status,
      'total_after_extra_discount'      => $this->total_after_extra_discount,
      'status'                          => OrderStatus::getStringValue($this->status),
      'shipping_type'                   => ShippingType::getStringValue($this->shipping_type),
      'pharmacy'                        => new PharmacyResource($this->whenLoaded('pharmacy')),
      'warehouse'                       => new WarehouseResource($this->whenLoaded('warehouse')),
      'city'                            => new CityResource($this->whenLoaded('city')),
      'area'                            => new AreaResource($this->whenLoaded('area')),
      'track'                           => new TrackResource($this->whenLoaded('track')),
      'shift'                           => new ShiftResource($this->whenLoaded('shift')),
      'baskets'                         => new BasketResourceCollection($this->whenLoaded('baskets')),
      'sales'                           => new UserResource($this->whenLoaded('sales')),
      'client'                          => new ClientResource($this->whenLoaded('client')),
      'delivery'                        => new UserResource($this->whenLoaded('delivery')),
      'prepared_by'                     => new UserResource($this->whenLoaded('preparedBy')),
      'reviewed_by'                     => new UserResource($this->whenLoaded('reviewedBy')),
      'created_by'                      => new UserResource($this->whenLoaded('createdBy')),
      'deleted_by'                      => new UserResource($this->whenLoaded('deletedBy')),
      'invoice'                         => new InvoiceResource($this->whenLoaded('invoice')),
      'cart'                            => new CartResourceCollection($this->whenLoaded('cart')),
    ];
  }
}

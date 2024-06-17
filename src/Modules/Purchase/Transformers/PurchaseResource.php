<?php

namespace Modules\Purchase\Transformers;

use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Area\Transformers\AreaResource;
use Modules\Area\Transformers\CityResource;
use Modules\User\Transformers\UserResource;
use Modules\Track\Transformers\TrackResource;
use Modules\Purchase\Enums\CartPurchaseStatus;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Product\Transformers\ManufacturerResource;
use Modules\Product\Transformers\BatchResourceCollection;

class PurchaseResource extends JsonResource
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
            'id'                                => $this->id,
            'last_balance'                      => $this->last_balance,
            'current_balance'                   => $this->current_balance,
            'total_quantity'                    => $this->total_quantity,
            'total_price'                       => $this->total_price,
            'purchase_number'                   => $this->purchase_number,
            'status'                            => PurchaseStatus::getStringValue($this->status),
            'status_value'                      => $this->status,
            'note'                              => $this->note,
            'reviewed_at'                       => $this->reviewed_at,
            'created_at'                        => $this->created_at,
            'city'                              => new CityResource($this->whenLoaded('city')),
            'area'                              => new AreaResource($this->whenLoaded('area')),
            'track'                             => new TrackResource($this->whenLoaded('track')),
            'pharmacy'                          => new PharmacyResource($this->whenLoaded('pharmacy')),
            'warehouse'                         => new WarehouseResource($this->whenLoaded('warehouse')),
            'cart'                              => new CartPurchaseResourceCollection($this->whenLoaded('cart')),
            'batches'                           => new BatchResourceCollection($this->whenLoaded('batches')),
            'total_cart_items'                  => isset($this->cart) ? $this->cart->count() : 0,
            'total_inventoried_cart_items'      => isset($this->cart) ? $this->cart->filter(function ($item) {
                return $item->status === CartPurchaseStatus::INVENTORIED || $item->status === CartPurchaseStatus::SEMI_INVENTORIED;
            })->count() : 0,
            'total_non_inventoried_cart_items'  => isset($this->cart) ? $this->cart->filter(function ($item) {
                return $item->status === CartPurchaseStatus::NON_INVENTORIED;
            })->count() : 0,
            'total_purchase_price'              => isset($this->cart) ? $this->cart->sum('inventoried_quantity_price') : 0,
            'client'                            => new ClientResource($this->whenLoaded('client')),
            'return'                            => new PurchaseReturnResource($this->whenLoaded('return')),
            'created_by'                        => new UserResource($this->whenLoaded('createdBy')),
            'reviewed_by'                       => new UserResource($this->whenLoaded('reviewedBy')),
            'supplier'                          => new UserResource($this->whenLoaded('supplier')),
            'manufacturer'                      => new ManufacturerResource($this->whenLoaded('manufacturer')),
        ];
    }
}

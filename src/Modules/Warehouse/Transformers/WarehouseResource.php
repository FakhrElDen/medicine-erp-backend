<?php

namespace Modules\Warehouse\Transformers;

use Modules\Warehouse\Enums\WarehouseType;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResourceCollection;
use Modules\Warehouse\Transformers\WarehouseProductResource;

class WarehouseResource extends JsonResource
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
            'name'                      => $this->name,
            'address'                   => $this->address,
            'order_number'              => $this->order_number,
            'type'                      => WarehouseType::getStringValue($this->type),
            'type_value'                => $this->type,
            'created_at'                => $this->created_at,
            'products'                  => new ProductResourceCollection($this->whenLoaded('products')),
            'product_location'          => new WarehouseProductResource($this->pivot),
        ];
    }
}

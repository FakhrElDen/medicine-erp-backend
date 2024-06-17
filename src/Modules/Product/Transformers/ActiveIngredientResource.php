<?php

namespace Modules\Product\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Warehouse\Transformers\CorridorResource;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Transaction\Transformers\PurchaseResource;

class ActiveIngredientResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'description'           => $this->description,
        ];
    }
}

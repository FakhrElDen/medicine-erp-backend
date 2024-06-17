<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Transformers\CartSubBatchResourceCollection;
use Modules\Client\Transformers\PharmacyResource;
use Modules\Product\Transformers\ProductResourceCollection;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class ReturnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'created_at'    => $this->created_at,
            'created_by'    => new UserResource($this->whenLoaded('user')),
            'pharmacy'      => new PharmacyResource($this->whenLoaded('pharmacy')),
            'order'         => new OrderResource($this->whenLoaded('order')),
            'returnables'   => new ReturnablesResourceCollection($this->whenLoaded('returnables')),
            'warehouse'     => new WarehouseResource($this->whenLoaded('warehouse')),
            'products'      => new ProductResourceCollection($this->whenLoaded('products')),
            'batch_info'    => new CartSubBatchResourceCollection($this->whenLoaded('subBatches')),
        ];
    }
}

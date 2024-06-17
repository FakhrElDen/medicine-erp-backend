<?php

namespace Modules\Warehouse\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\BatchResourceCollection;
use Modules\User\Transformers\UserResource;

class TransferResource extends JsonResource
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
            'id'                      => $this->id,
            'transfer_number'         => $this->transfer_number,
            'transfer_from_warehouse' => new WarehouseResource($this->whenLoaded('fromWarehouse')),
            'transfer_to_warehouse'   => new WarehouseResource($this->whenLoaded('toWarehouse')),
            'created_by'              => new UserResource($this->whenLoaded('user')),
            'batches'                 => new BatchResourceCollection($this->whenLoaded('batches')),
        ];
    }
}

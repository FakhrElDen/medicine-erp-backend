<?php

namespace Modules\Warehouse\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProductResource extends JsonResource
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
            // 'id' => $this->id,
            'stand' => $this->stand,
            'shelf' => $this->shelf,
            'corridor' => new CorridorResource($this->whenLoaded('corridor')),
        ];
    }
}

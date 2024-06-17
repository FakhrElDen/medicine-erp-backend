<?php

namespace Modules\Warehouse\Transformers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CorridorResource extends JsonResource
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
            'id'                 => $this->id,
            'number'             => $this->number,
            'color'              => $this->color,
            'is_main_corridor'   => $this->is_main_corridor,
            'completed_at'       => $this->pivot?->completed_at,
            'baskets'            => new BasketResourceCollection($this->whenLoaded('baskets')),
        ];
    }
}

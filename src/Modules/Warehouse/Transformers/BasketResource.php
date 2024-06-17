<?php

namespace Modules\Warehouse\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Warehouse\Transformers\CorridorResource;

class BasketResource extends JsonResource
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
            'id'                     => $this->id,
            'number'                 => intval($this->number),
            'created_at'             => $this->created_at,
            'user'                   => new UserResource($this->whenLoaded('user')),
            'corridor'               => new CorridorResource($this->whenLoaded('corridor')),
        ];
    }
}

<?php

namespace Modules\Client\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WaitingListResource extends JsonResource
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
            'id'                       => $this->id,
            'minutes_waited'           => now()->diffInMinutes($this->created_at),
            'created_at'               => $this->created_at,
            'pharmacy'                 => new ClientResource($this->whenLoaded('pharmacy')),
            'client'                   => new ClientResource($this->whenLoaded('client')),
            'sales'                    => new UserResource($this->whenLoaded('sales')),
        ];
    }
}

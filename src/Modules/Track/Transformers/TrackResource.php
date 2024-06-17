<?php

namespace Modules\Track\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResourceCollection;

class TrackResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'pharmacies_count' => $this->pharmacies_count ?? null,
            'shifts' => new ShiftResourceCollection($this->whenLoaded('shifts')),
            'delivers' => new UserResourceCollection($this->whenLoaded('users')),
        ];
    }
}

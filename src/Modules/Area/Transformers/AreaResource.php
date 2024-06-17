<?php

namespace Modules\Area\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Track\Transformers\TrackResourceCollection;

class AreaResource extends JsonResource
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
            'name'               => $this->name,
            'pharmacies_count'   => $this->pharmacies_count ?? null,
            'tracks'             => new TrackResourceCollection($this->whenLoaded('tracks')),
        ];
    }
}

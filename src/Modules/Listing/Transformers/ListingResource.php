<?php

namespace Modules\Listing\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Listing\Enums\ListingType;
use Modules\Client\Transformers\PharmacyResourceCollection;
use Modules\User\Transformers\UserResourceCollection;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'type_value'           => $this->type,
            'type'                 => ListingType::getStringValue($this->type),
            'pharmacies_number'    => $this->whenLoaded('pharmacies') ? count($this->pharmacies) : null,
            'list_target'          => $this->whenLoaded('pharmacies') ? $this->pharmacies->sum('target') : null,
            'users'                => new UserResourceCollection($this->whenLoaded('users')),
            'pharmacies'           => new PharmacyResourceCollection($this->whenLoaded('pharmacies')),
        ];
    }
}

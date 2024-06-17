<?php

namespace Modules\Client\Transformers;

use Modules\Client\Enums\ClientType;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResourceCollection;

class ClientResource extends JsonResource
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
            'id'                             => $this->id,
            'name'                           => $this->name,
            'code'                           => $this->code,
            'iterate_available_quantity'     => $this->iterate_available_quantity,
            'type_value'                     => $this->type,
            'type'                           => ClientType::getStringValue($this->type),
            'days_of_creation'               => $this->days_of_creation,
            'created_at'                     => $this->created_at,
            'pharmacies'                     => new PharmacyResourceCollection($this->whenLoaded('pharmacies')),
            'users'                          => new UserResourceCollection($this->whenLoaded('users')),
        ];
    }
}

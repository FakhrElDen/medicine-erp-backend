<?php

namespace Modules\User\Transformers;

use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Listing\Transformers\ListingResourceCollection;
use Modules\Warehouse\Transformers\BasketResourceCollection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $role = $this->roles->first();
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'role'            => clone $role,
            'permissions'     => $role->permissions,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'target'          => $this->target,
            'has_list'        => $this->listing()->exists(),
            'client'          => new ClientResource($this->whenLoaded('client')),
            'lists'           => new ListingResourceCollection($this->whenLoaded('listing')),
            'baskets'         => new BasketResourceCollection($this->whenLoaded('baskets')),
        ];
    }
}

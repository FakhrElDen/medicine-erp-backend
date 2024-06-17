<?php

namespace Modules\Product\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProhibitedBatchResource extends JsonResource
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
            'id'                => $this->id,
            'operating_number'  => $this->operating_number,
            'expiry_date'       => $this->expiry_date,
            'post_number'       => $this->post_number,
            'post_reason'       => $this->post_reason,
            'created_at'        => $this->created_at,
            'created_by'        => new UserResource($this->whenLoaded('createdBy')),
            'product'           => new ProductResource($this->whenLoaded('product')),
        ];
    }
}

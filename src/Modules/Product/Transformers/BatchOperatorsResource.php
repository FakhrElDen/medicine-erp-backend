<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;

class BatchOperatorResource extends JsonResource
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
            'id'                                => $this->id,
            'created_at'                        => $this->created_at,
            'distributor_received_at'           => $this->distributor_received_at,
            'reviewer_received_at'              => $this->reviewer_received_at,
            'stored_at'                         => $this->stored_at,
            'supplied_at'                       => $this->supplied_at,
            'supplier'                          => new UserResource($this->whenLoaded('supplier')),
            'receiver_reviewer'                 => new UserResource($this->whenLoaded('receiverReviewer')),
            'receiver_distributor'              => new UserResource($this->whenLoaded('receiverDistributor')),
            'storing_worker'                    => new UserResource($this->whenLoaded('storingWorker')),
            'created_by'                        => new UserResource($this->whenLoaded('createdBy')),
            'updated_by'                        => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}

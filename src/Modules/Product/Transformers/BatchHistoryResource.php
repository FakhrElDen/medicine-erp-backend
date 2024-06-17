<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;

class BatchHistoryResource extends JsonResource
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
            'id'            => $this->id,
            'user'          => new UserResource($this->whenLoaded('user')),
            'second_user'   => new UserResource($this->whenLoaded('secondUser')),
            'batch'         => new BatchResource($this->whenLoaded('batch')),
            'subject'       => $this->subject,
        ];
    }
}

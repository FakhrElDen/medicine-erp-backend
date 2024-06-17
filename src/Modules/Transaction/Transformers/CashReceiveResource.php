<?php

namespace Modules\Transaction\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class CashReceiveResource extends JsonResource
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
            'previous_balance'       => $this->previous_balance,
            'received_amount'        => $this->received_amount,
            'remaining_amount'       => $this->remaining_amount,
            'created_at'             => $this->created_at,
            'pharmacy'               => new PharmacyResource($this->whenLoaded('pharmacy')),
            'user'                   => new UserResource($this->whenLoaded('user')),
        ];
    }
}

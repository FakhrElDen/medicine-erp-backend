<?php

namespace Modules\Transaction\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class BalanceResource extends JsonResource
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
            'from_previous_balance'  => $this->from_previous_balance,
            'to_previous_balance'    => $this->to_previous_balance,
            'amount'                 => $this->amount,
            'from_remaining_amount'  => $this->from_previous_balance - $this->amount ?? 0,
            'to_remaining_amount'    => $this->to_previous_balance + $this->amount ?? 0,
            'created_at'             => $this->created_at,
            'pharmacy'               => $this->relationLoaded('from_pharmacy') ?  new PharmacyResource($this->whenLoaded('from_pharmacy')) :  new PharmacyResource($this->whenLoaded('to_pharmacy')),
            'user'                   => new UserResource($this->whenLoaded('user')),
        ];
    }
}

<?php

namespace Modules\Transaction\Transformers;

use Modules\User\Transformers\UserResource;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class TransactionsResource extends JsonResource
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
      'id'                              => $this->id, 
      'last_balance'                    => $this->last_balance,
      'current_balance'                 => $this->current_balance,
      'total'                           => $this->total,
      'created_at'                      => $this->created_at,
      'pharmacy'                        => new PharmacyResource($this->whenLoaded('pharmacy')),
      'client'                          => new ClientResource($this->whenLoaded('client')),
      'created_by'                      => new UserResource($this->whenLoaded('createdBy')),
      'deleted_by'                      => new UserResource($this->whenLoaded('deletedBy')),
    ];
  }
}

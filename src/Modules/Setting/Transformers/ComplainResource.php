<?php

namespace Modules\Setting\Transformers;

use Modules\Setting\Enums\ComplainType;
use Modules\User\Transformers\RoleResource;
use Modules\User\Transformers\UserResource;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class ComplainResource extends JsonResource
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
      'id'                  => $this->id,
      'body'                => $this->body,
      'status'              => ComplainType::getStringValue($this->status),
      'created_at'          => $this->created_at,
      'minutes_waited'      => now()->diffInMinutes($this->created_at),
      'solved_duration'     => $this->solved_duration,
      'user'                => new UserResource($this->whenLoaded('user')),
      'sales'               => new UserResource($this->whenLoaded('sales')),
      'client'              => new ClientResource($this->whenLoaded('client')),
      'pharmacy'            => new PharmacyResource($this->whenLoaded('pharmacy')),
      'role'                => new RoleResource($this->whenLoaded('role')),
      'created_by'          => new UserResource($this->whenLoaded('createdBy')),
      'solver'              => new UserResource($this->whenLoaded('solver')),
    ];
  }
}

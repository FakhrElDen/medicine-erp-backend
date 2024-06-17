<?php

namespace Modules\Transaction\Transformers;

use Modules\User\Transformers\UserResource;
use Modules\Client\Transformers\ClientResource;
use Modules\Transaction\Enums\NotificationType;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class NotificationResource extends JsonResource
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
            'notification_value'        => $this->notification_value,
            'remaining_amount'          => $this->pharmacy ? $this->pharmacy->balance - $this->notification_value : null,
            'created_at'                => $this->created_at,
            'type'                      => NotificationType::getStringValue($this->type),
            'pharmacy'                  => new PharmacyResource($this->whenLoaded('pharmacy')),
            'client'                    => new ClientResource($this->whenLoaded('client')),
            'accountant'                => new UserResource($this->whenLoaded('user')),
        ];
    }
}

<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Enums\ReturnsReasons;

class ReturnablesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'total' => $this->total,
            'reason' => [
                'value' => $this->reason,
                'name' => ReturnsReasons::getStringValue($this->reason),
            ],
            'return'    => new ReturnResource($this->whenLoaded('return')),
            'expired_at' => $this->expired_at,
            'operating_number' => $this->operating_number,
            'product_name' => $this->product_name,
            'product_location' => $this->product_location,
            'manufacturer_name' => $this->manufacturer_name,
        ];
    }
}

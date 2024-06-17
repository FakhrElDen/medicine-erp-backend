<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Enums\OfferType;
use Modules\Product\Enums\SlatType;

class OfferResource extends JsonResource
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
            'id' => $this->id,
            'quantity_for_offer' => $this->quantity_for_offer,
            'quantity' => OfferType::getStringValue($this->type) == 'quantity' ? $this->offer_value : null,
            'percentage' => OfferType::getStringValue($this->type) == 'percentage' ? $this->offer_value : null,
            'type' => OfferType::getStringValue($this->type),
            'slat_type' => SlatType::getStringValue($this->slat_type),
        ];
    }
}

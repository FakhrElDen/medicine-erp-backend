<?php

namespace Modules\Client\Transformers;

use Modules\Client\Enums\PaymentType;
use Modules\Client\Enums\PharmaciesType;
use Modules\Client\Enums\PharmacyActive;
use Modules\Client\Enums\PharmacyStatus;
use Modules\Client\Enums\DiscountSlatType;
use Modules\Area\Transformers\AreaResource;
use Modules\Area\Transformers\CityResource;
use Modules\User\Transformers\UserResource;
use Modules\Track\Transformers\TrackResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Enums\DayShifts;
use Modules\Client\Enums\PharmacyShiftType;
use Modules\Listing\Transformers\ListingResourceCollection;

class PharmacyResource extends JsonResource
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
            'id'                          => $this->id,
            'name'                        => $this->name,
            'address'                     => $this->address,
            'code'                        => $this->code,
            'debt_limit'                  => $this->debt_limit,
            'extra_discount'              => $this->extra_discount,
            'phone_number'                => $this->phone_number,
            'optional_phone_number'       => $this->optional_phone_number,
            'latitude'                    => $this->latitude,
            'longitude'                   => $this->longitude,
            'email'                       => $this->email,
            'doctor'                      => $this->doctor,
            'commercial_registration_no'  => $this->commercial_registration_no,
            'license_no'                  => $this->license_no,
            'tax_card_no'                 => $this->tax_card_no,
            'target'                      => $this->target,
            'minimum_target'              => $this->minimum_target,
            'payment_period'              => $this->payment_period,
            'iterate_available_quantity'  => $this->iterate_available_quantity,
            'discount_slat'               => DiscountSlatType::getStringValue(intval($this->discount_slat)),
            'discount_slat_value'         => $this->discount_slat,
            'note'                        => $this->note,
            'balance'                     => $this->balance,
            'all'                         => $this->all,
            'days_of_creation'            => $this->days_of_creation,
            'location_url'                => $this->location_url,
            'created_at'                  => $this->created_at,
            'pharmacy_media'              => $this->pharmacy_media ? $this->pharmacy_media->map(function ($item) {
                return [
                    'original_url' => $item->getFullUrl(), 'id' => $item->id,
                ];
            }) : null,
            'has_client'                  => $this->has_client,
            'not_has_client_num'          => $this->doesntHave('lists')->count(),
            'last_invoice'                => $this->lastInvoice() ? $this->lastInvoice()->first()?->total : null,
            'call_shift_value'            => $this->call_shift,
            'call_shift'                  => PharmacyShiftType::getStringValue(intval($this->call_shift)),
            'active_value'                => $this->active,
            'active'                      => PharmacyActive::getStringValue(intval($this->active)),
            'status_value'                => $this->status,
            'status'                      => PharmacyStatus::getStringValue(intval($this->status)),
            'follow_up_value'             => $this->follow_up,
            'follow_up'                   => DayShifts::getStringValue(intval($this->follow_up)),
            'type_value'                  => $this->type,
            'type'                        => PharmaciesType::getStringValue(intval($this->type)),
            'payment_type_value'          => $this->payment_type,
            'payment_type'                => PaymentType::getStringValue($this->payment_type),
            'clients'                     => new ClientResourceCollection($this->whenLoaded('clients')),
            'lists'                       => new ListingResourceCollection($this->whenLoaded('lists')),
            'track'                       => new TrackResource($this->whenLoaded('track')),
            'city'                        => new CityResource($this->whenLoaded('city')),
            'area'                        => new AreaResource($this->whenLoaded('area')),
            'delivery'                    => new UserResource($this->whenLoaded('delivery')),
        ];
    }
}

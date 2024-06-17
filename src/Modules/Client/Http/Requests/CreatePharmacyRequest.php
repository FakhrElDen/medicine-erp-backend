<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Client\Rules\PharmacyExtraDiscountRule;

class CreatePharmacyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city_id'                       => 'required|exists:cities,id',
            'area_id'                       => 'required|exists:areas,id',
            'delivery_id'                   => 'nullable|exists:users,id',
            'track_id'                      => 'nullable|exists:tracks,id',
            'morning_sales_id'              => 'nullable|exists:users,id|required_if:morning_list_id,set',
            'morning_list_id'               => 'nullable|exists:listings,id|required_if:morning_sales_id,set',
            'night_sales_id'                => 'nullable|exists:users,id|required_if:night_list_id,set',
            'night_list_id'                 => 'nullable|exists:listings,id|required_if:night_sales_id,set',
            'client_name'                   => 'nullable',
            'client_type'                   => 'required',
            'client_phone_number'           => 'nullable|unique:clients,phone_number',
            'name'                          => 'required',
            'phone_number'                  => 'required|unique:pharmacies,phone_number',
            'optional_phone_number'         => 'nullable',
            'email'                         => 'nullable|unique:pharmacies,email',
            'address'                       => 'nullable',
            'status'                        => 'nullable',
            'longitude'                     => 'nullable',
            'latitude'                      => 'nullable',
            'location_url'                  => 'nullable',
            'payment_period'                => 'nullable',
            'commercial_registration_no'    => 'nullable|unique:pharmacies,commercial_registration_no',
            'license_no'                    => 'nullable|unique:pharmacies,license_no',
            'tax_card_no'                   => 'nullable|unique:pharmacies,tax_card_no',
            'active'                        => 'nullable',
            'doctor'                        => 'required',
            'note'                          => 'nullable',
            'target'                        => 'nullable',
            'minimum_target'                => 'nullable',
            'iterate_available_quantity'    => 'nullable',
            'extra_discount'                => ['nullable', new PharmacyExtraDiscountRule()],
            'all'                           => 'nullable',
            'follow_up'                     => 'nullable',
            'call_shift'                    => ['nullable', 'required_with:follow_up'],
            'payment_type'                  => 'nullable',
            'debt_limit'                    => 'nullable',
            'pharmacy_media'                => 'nullable',
            'discount_slat'                 => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

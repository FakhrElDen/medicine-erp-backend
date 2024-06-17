<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPharmacyToClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id'                     => 'required|exists:clients,id',
            'city_id'                       => 'required|exists:cities,id',
            'area_id'                       => 'required|exists:areas,id',
            'track_id'                      => 'nullable|exists:tracks,id',
            'delivery_id'                   => 'nullable|exists:users,id',
            'phone_number'                  => 'nullable|unique:pharmacies,phone_number',
            'email'                         => 'required|unique:pharmacies,email',
            'optional_phone_number'         => 'nullable',
            'name'                          => 'required',
            'address'                       => 'required',
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
            'target'                        => 'nullable|numeric',
            'minimum_target'                => 'nullable|numeric',
            'iterate_available_quantity'    => 'nullable|numeric',
            'extra_discount'                => 'nullable',
            'all'                           => 'nullable|numeric',
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

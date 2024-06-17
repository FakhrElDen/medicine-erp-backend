<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'                          => 'nullable|numeric',
            'name'                          => 'nullable',
            'pharmacy_id'                   => 'nullable',
            'city_id'                       => 'nullable',
            'area_id'                       => 'nullable',
            'track_id'                      => 'nullable',
            'iterate_available_quantity'    => 'nullable',
            'payment_type'                  => 'nullable',
            'debt_limit'                    => 'nullable',
            'target'                        => 'nullable',
            'minimum_target'                => 'nullable',
            'all'                           => 'nullable',
            'active'                        => 'nullable',
            'payment_period'                => 'nullable',
            'extra_discount'                => 'nullable',
            'follow_up'                     => 'nullable',
            'call_shift'                    => 'nullable',
            'status'                        => 'nullable',
            'client_type'                   => 'nullable',
            'morning_sales_id'              => 'nullable',
            'night_sales_id'                => 'nullable',
            'pagination_number'             => 'nullable',
            'discount_slat'                 => 'nullable',
            'client_id'                     => 'nullable',
            'client_code'                   => 'nullable',
            'sort_by'                       => 'nullable',
            'sorted_by'                     => 'nullable',
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

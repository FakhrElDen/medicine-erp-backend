<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingProhibitedBatchesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'        => 'nullable|exists:products,id',
            'manufacturer_id'   => 'nullable|exists:manufacturers,id',
            'created_by'        => 'nullable|exists:users,id',
            'operating_number'  => 'nullable',
            'post_number'       => 'nullable|integer',
            'expiry_date'       => 'nullable',
            'post_reason'       => 'nullable',
            'product_name'      => 'nullable',
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

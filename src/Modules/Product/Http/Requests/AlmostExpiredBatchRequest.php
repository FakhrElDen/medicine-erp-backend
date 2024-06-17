<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlmostExpiredBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name'          => 'nullable',
            'manufacturer_id'       => 'nullable|exists:manufacturers,id',
            'corridor_id'           => 'nullable|exists:corridors,id',
            'supplier_id'           => 'nullable|exists:users,id',
            'supplied_at'           => 'nullable',
            'current_quantity'         => 'nullable',
            'remaining_expiry'      => 'nullable',
            'sort_by'               => 'nullable',
            'direction'             => 'nullable|required_if:sort_by,set'
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

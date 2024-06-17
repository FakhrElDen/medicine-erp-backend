<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'created_at'            => 'nullable',
            'supplied_at'           => 'nullable',
            'batch_id'              => 'nullable|exists:batches,id',
            'manufacturer_id'       => 'nullable|exists:manufacturers,id',
            'product_id'            => 'nullable|exists:products,id',
            'supplier_id'           => 'nullable|exists:users,id',
            'receiver_reviewer_id'   => 'nullable|exists:users,id',
            'old_operating_number'  => 'nullable',
            'new_operating_number'  => 'nullable',
            'quantity_more_than_zero'=> 'nullable',
            'new_expired_at'        => 'nullable',
            'old_expired_at'        => 'nullable',
            'sort_by'               => 'nullable',
            'direction'             => 'nullable|in:desc,asc',
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

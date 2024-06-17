<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterBatchesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'warehouse_id'                 => 'nullable',
            'manufacturer_id'              => 'nullable',
            'supplier_id'                  => 'nullable',
            'receiver_reviewer_id'          => 'nullable',
            'corridor_id'                  => 'nullable',
            'quantity'                     => 'nullable',
            'code'                         => 'nullable',
            'price'                        => 'nullable',
            'product_name'                 => 'nullable',
            'product_type'                 => 'nullable',
            'supplied_at'                  => 'nullable', 
            'reviewer_received_at'          => 'nullable',
            'stored_at'                    => 'nullable',
            'quantity_before_supplying'    => 'nullable',
            'quantity_after_supplying'     => 'nullable',
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

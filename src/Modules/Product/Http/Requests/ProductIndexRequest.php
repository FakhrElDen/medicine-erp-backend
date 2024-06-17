<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'                => 'nullable|exists:products,id',
            'corridor_id'               => 'nullable|exists:corridors,id',
            'warehouse_id'              => 'nullable|exists:warehouses,id',
            'stand'                     => 'nullable',
            'shelf'                     => 'nullable',
            'product_type'              => 'nullable',
            'supplied_at'               => 'nullable|date_format:Y-m-d',
            'manufacturer_id'           => 'nullable',
            'name'                      => 'nullable',
            'discount_from'             => 'nullable',
            'discount_to'               => 'nullable',
            'price_from'                => 'nullable',
            'price_to'                  => 'nullable',
            'quantity_more_than_zero'   => 'nullable',
            'selling_status'            => 'nullable',
            'buying_status'             => 'nullable',
            'sort_by'                   => 'nullable',
            'direction'                 => 'nullable|in:desc,asc',
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

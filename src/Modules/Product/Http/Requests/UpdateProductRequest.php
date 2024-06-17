<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.ar'                       => 'nullable|string',
            'name.en'                       => 'nullable|string',
            'manufacturer_id'               => 'nullable|exists:manufacturers,id',
            'manufacturing_type'            => 'nullable|numeric',
            'type'                          => 'nullable|numeric',
            'items_number_in_packet'        => 'nullable|numeric',
            'packets_number_in_package'     => 'nullable|numeric',
            'warehouses'                    => ['nullable', 'array'],
            'warehouses.*.warehouse_id'     => 'nullable|exists:warehouses,id',
            'warehouses.*.corridor_id'      => 'nullable|exists:corridors,id',
            'warehouses.*.stand'            => 'nullable',
            'warehouses.*.shelf'            => 'nullable',
            'buying_status'                 => 'nullable|numeric',
            'selling_status'                => 'nullable|numeric',
            'barcode'                       => 'nullable|string',
            'note'                          => 'string',
            'active_ingredient_ids'         => 'nullable|array',
            'active_ingredient_ids.*'       => 'nullable|exists:active_ingredients,id',

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

<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTransferBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transfer_id'               => 'nullable|exists:transfers,id',
            'transfer_number'           => 'nullable|numeric',
            'transfer_from_warehouse_id'=> 'nullable|exists:warehouses,id',
            'transfer_to_warehouse_id'  => 'nullable|exists:warehouses,id',
            'created_by'                => 'nullable|exists:users,id',
            'from_date'                 => 'nullable',
            'to_date'                   => 'nullable|after:from_date',
            'manufacturer_id'           => 'nullable|exists:manufacturers,id',
            'corridor_id'               => 'nullable|exists:corridors,id',
            'product_id'                => 'nullable|exists:products,id',
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

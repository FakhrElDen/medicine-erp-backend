<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingPurchasesReturnsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'return_id'                    => 'nullable|exists:purchases_returns,id',
            'warehouse_id'                 => 'nullable|exists:warehouses,id',
            'supplier_id'                  => 'nullable|exists:users,id',
            'reviewed_by'                  => 'nullable|exists:users,id',
            'created_by'                   => 'nullable|exists:users,id',
            'from'                         => 'nullable',
            'to'                           => 'nullable',
            'purchase_number'              => 'nullable',
            'packets_number_in_package'    => 'nullable|integer',
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

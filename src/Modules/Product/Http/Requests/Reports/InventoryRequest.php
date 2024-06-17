<?php

namespace Modules\Product\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'warehouse_id' => 'integer|exists:warehouses,id',
            'user_id' => 'integer|exists:users,id',
            'from' => 'date',
            'to' => 'date|after:from',
            'sort_by' => 'string|in:user_name,warehouse_name,created_at,quantity_before,amount,quantity_after',
            'direction' => 'string|in:asc,desc',
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

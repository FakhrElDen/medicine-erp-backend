<?php

namespace Modules\Product\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'supplier_id' => 'integer|exists:users,id',
            'from' => 'date',
            'to' => 'date|after_or_equal:from',
            'sort_by' => 'string|in:supplier_name,created_at,quantity_before,amount,quantity_after,user_name,warehouse_name',
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

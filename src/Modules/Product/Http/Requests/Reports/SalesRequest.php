<?php

namespace Modules\Product\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class SalesRequest extends FormRequest
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
            'pharmacy_id' => 'integer|exists:pharmacies,id',
            'from' => 'date',
            'to' => 'date|after:from',
            'sort_by' => 'string|in:pharmacy_name,created_at,quantity_before,amount,quantity_after,user_name,warehouse_name',
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

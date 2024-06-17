<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['integer', Rule::exists('products', 'id')],
            'warehouse_id' => ['integer', Rule::exists('warehouses', 'id')],
            'from' => ['date'],
            'to' => array_filter(['date', $this->from ? 'after:from' : null]),
            'sort_by' => ['string', 'in:user_name,warehouse_name,created_at,current_quantity,excess,shortage'],
            'direction' => ['string', 'in:asc,desc'],
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

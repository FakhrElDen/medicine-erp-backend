<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettlementRequest extends FormRequest
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
            'pharmacy_id' => ['integer', Rule::exists('pharmacies', 'id')],
            'reviewed_by' => ['integer', Rule::exists('users', 'id')],
            'from' => ['date'],
            'to' => array_filter(['date', $this->from ? 'after:from' : null]),
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

<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cart\Rules\CheckWarehouseQuantityRule;

class CheckOfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => ['required', 'gt:0'],
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->passes()) {
            $validator->addRules([
                'quantity' => [new CheckWarehouseQuantityRule()],
            ]);
        }
    }

    public function messages()
    {
        return [
            'quantity.gt' => trans('cart::message.insert_quantity_with_zero'),
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

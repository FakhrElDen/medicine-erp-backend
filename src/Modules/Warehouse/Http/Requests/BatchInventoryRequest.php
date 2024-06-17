<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Rules\CheckQuantityForProductHasBonusDiscountRule;

class BatchInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_id' => 'required|exists:batches,id',
            'cart_id'  => 'required|exists:carts,id',
            'quantity' => ['required', 'gt:0', 'integer', new CheckQuantityForProductHasBonusDiscountRule],
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

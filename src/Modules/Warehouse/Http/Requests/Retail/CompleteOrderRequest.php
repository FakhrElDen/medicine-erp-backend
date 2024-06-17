<?php

namespace Modules\Warehouse\Http\Requests\Retail;

use Illuminate\Foundation\Http\FormRequest;

class CompleteOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'basket_ids'            => 'sometimes|array',
            'basket_ids.*'          => 'sometimes|exists:baskets,id|integer',

            'batch_ids'             => 'required|array',
            'batch_ids.*.batch_id'  => 'required|exists:batches,id|integer',
            'batch_ids.*.cart_id'   => 'required|exists:carts,id|integer',
            'batch_ids.*.status'    => 'required',
            
            'order_id'              => 'required|exists:orders,id|integer',
            'corridor_id'           => 'required|exists:corridors,id|integer',
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

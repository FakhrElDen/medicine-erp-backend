<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Warehouse\Rules\CheckBasketNumberRule;

class CreateBasketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id'               => 'nullable|integer|exists:carts,id',
            'order_id'              => 'required|integer|exists:orders,id',
            'corridor_id'           => 'required|integer|exists:corridors,id',
            'number'                => [
                'required',
                new CheckBasketNumberRule(),
                'unique:baskets,number',
            ],
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

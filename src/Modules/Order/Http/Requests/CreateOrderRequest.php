<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'client_id' => 'required|exists:clients,id',
            'sales_id' => 'nullable|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'order_id' => 'required|exists:orders,id',
            'total_quantity' => 'required|integer',
            'total_price' => 'required|numeric',
            'total_taxes' => 'required|numeric',
            'extra_discount' => 'required|numeric',
            'extra_discount_condition' => 'required|numeric',
            'total_after_extra_discount' => 'required|numeric',
            'total' => 'required|numeric',
            'shipping_type' => 'required|in:0,1',
            'last_balance' => 'required',
            'current_balance' => 'required',
            'order_number' => 'required',
            'note' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => trans('cart::message.empty_invoice'),
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

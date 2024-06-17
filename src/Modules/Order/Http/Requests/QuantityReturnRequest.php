<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuantityReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cart_sub_batch_id' => 'required|integer|exists:cart_sub_batch,id',
            'quantity' => 'required|numeric|gt:0',
            'discount' => 'nullable|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'cart_sub_batch_id.required' => trans('order::message.cart_sub_batch_required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_id'           => 'required|exists:purchases,id',
            'cart_purchase_ids'     => 'required|array',
            'cart_purchase_ids.*'   => 'required|exists:cart_purchases,id|unique:cart_purchases_returns,cart_purchase_id',
            'reason'                => 'required|integer',
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

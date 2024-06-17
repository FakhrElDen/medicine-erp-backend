<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DuplicateBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_id'              => 'required|exists:batches,id',
            'cart_id'               => 'required|exists:carts,id',
            'expired_at'            => 'required|date_format:Y-m',
            'order_quantity'        => 'required',
            'operating_number'      => 'required|regex:/^[A-Za-z0-9]{12}$/',
        ];
    }

    public function messages()
    {
        return [
            'expired_at.date_format' => trans('order::message.expired_at'),
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

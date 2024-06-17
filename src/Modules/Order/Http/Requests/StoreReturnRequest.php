<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pharmacy_id'               => 'required|exists:pharmacies,id',
            'warehouse_id'              => 'required|exists:warehouses,id',
            'order_id'                  => 'nullable|exists:orders,id',
            'products'                  => ['required', 'array'],
            'products.*.returnable_type'=> 'required|in:Product,CartSubBatch',
            'products.*.returnable_id'  => 'required',
            'products.*.quantity'       => 'required|numeric|gt:0',
            'products.*.price'          => 'required|numeric|gt:0',
            'products.*.discount'       => 'required|numeric|gt:0',
            'products.*.total'          => 'required|numeric|gt:0',
            'products.*.reason'         => 'required|numeric',
            'products.*.operating_number' => 'required|string|regex:/^[A-Za-z0-9]{12}$/',
            'products.*.expired_at'     => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'products.*.operating_number.regex' => trans('order::message.operating_number_regex'),
            'products.*.operating_number.required' => trans('order::message.operating_number'),
            'products.*.expired_at.required' => trans('order::message.expired_at_req'),
            'products.*.discount.required' => trans('order::message.discount'),
            'products.*.discount.gt' => trans('order::message.discount_gt'),
            'products.*.reason.required' => trans('order::message.reason'),
            'pharmacy_id.required' => trans('order::message.pharmacy_id'),
            'warehouse_id.required' => trans('order::message.warehouse'),
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

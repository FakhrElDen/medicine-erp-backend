<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status'            => 'nullable|integer',
            'order_id'          => 'nullable',
            'pagination_number' => 'nullable',
            'client_id'         => 'nullable',
            'created_at'        => 'nullable',
            'track_id'          => 'nullable',
            'city_id'           => 'nullable',
            'area_id'           => 'nullable',
            'sales_id'          => 'nullable',
            'payment_type'      => 'nullable',
            'warehouse_id'      => 'nullable',
            'product_id'        => 'nullable|exists:products,id',
            'pharmacy_id'       => 'nullable|exists:pharmacies,id',
            'operating_number'  => 'nullable|exists:batches,operating_number',
            'order_number'      => 'nullable',
            'sort_by'           => 'nullable',
            'direction'         => 'nullable',
            'from'              => 'nullable|before:to',
            'to'                => 'nullable',
            'expired_at'        => 'nullable|date_format:Y-m',
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

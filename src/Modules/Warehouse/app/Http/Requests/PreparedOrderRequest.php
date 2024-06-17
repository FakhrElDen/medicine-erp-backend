<?php

namespace Modules\Warehouse\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreparedOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'batch_ids'             => 'required|array',
            'batch_ids.*.batch_id'  => 'required|exists:batches,id|integer',
            'batch_ids.*.cart_id'   => 'required|exists:carts,id|integer',
            'batch_ids.*.status'    => 'required',
            
            'order_id'              => 'required|exists:orders,id|integer',
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

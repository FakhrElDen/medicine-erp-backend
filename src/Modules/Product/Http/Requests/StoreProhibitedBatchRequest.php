<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProhibitedBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'        => 'required|exists:products,id',
            'operating_number'  => 'required|string|regex:/^[A-Za-z0-9]{12}$/',
            'expiry_date'       => 'required',
            'post_number'       => 'required|integer',
            'post_reason'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'operating_number.regex' => trans('product::message.wrong_operating_number'),
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

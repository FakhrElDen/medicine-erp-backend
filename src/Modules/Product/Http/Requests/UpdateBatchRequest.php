<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Rules\CheckQuantityRule;

class UpdateBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_id'          => 'required|exists:batches,id',
            'quantity'          => ['required', 'integer', 'gt:0', new CheckQuantityRule],
            'expired_at'        => 'required|date_format:Y-m',
            'operating_number'  => 'required|string|regex:/^[A-Za-z0-9]{12}$/',
        ];
    }

    public function messages()
    {
        return [
            'operating_number.regex' => trans('order::message.operating_number_regex'),
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

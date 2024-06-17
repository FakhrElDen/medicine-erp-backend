<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'operating_number'  => 'required|string|regex:/^[A-Za-z0-9]{12}$/',
            'expired_at'        => 'required|date',
            'quantity'          => 'required|numeric|gt:0',
            'discount'          => 'required|numeric|gt:0',
            'reason'            => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'expired_at.date_format' => trans('order::message.expired_at'),
            'operating_number.regex' => trans('order::message.operating_number_regex'),
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

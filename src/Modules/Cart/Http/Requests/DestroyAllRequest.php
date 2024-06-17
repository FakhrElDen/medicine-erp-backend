<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyAllRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_sub_batch_ids'     => 'required|array',
            'cart_sub_batch_ids.*'   => 'required|integer|exists:cart_sub_batch,id',
        ];
    }

    public function messages()
    {
        return [
            'cart_sub_batch_ids.*.required' => trans('cart::message.empty_invoice'),
            'cart_sub_batch_ids.required' => trans('cart::message.empty_invoice'),
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

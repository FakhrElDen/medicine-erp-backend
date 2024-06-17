<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cart\Rules\CheckItemRelatedWithOrderRule;
use Illuminate\Http\Exceptions\HttpResponseException;

class ViewProductByBarcodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'barcode'           => 'required|exists:products,barcode',
            'order_id'          => ['nullable', 'exists:orders,id', new CheckItemRelatedWithOrderRule()],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($validator->errors()->first('barcode') == trans('validation.exists', ['attribute' => trans('validation.attributes.barcode')])) {
                $response = [
                    'message' => 'Invalid barcode',
                    'data' => []
                ];

                throw new HttpResponseException(response()->json($response, 200));
            }
        });
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

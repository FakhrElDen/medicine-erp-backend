<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClintIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['nullable', 'regex:/^[gG]?(\d+|1)$/'],
            'name' => 'nullable',
            'id' => 'nullable|exists:clients,id',
        ];
    }

    public function messages()
    {
        return [
            'code.regex' =>  trans('client::validation.code_regex'),
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

<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Client\Rules\ViewClientRule;

class ClientViewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'    => ['required_without:code', 'exists:clients,id', new ViewClientRule()],
            'code'  => ['required_without:id','exists:clients,code', 'regex:/^[gG]\d+$/'],
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

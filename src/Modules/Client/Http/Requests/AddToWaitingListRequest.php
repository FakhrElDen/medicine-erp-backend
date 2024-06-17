<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToWaitingListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'pharmacy_id' => 'required|exists:pharmacies,id|unique:waiting_lists,pharmacy_id',
        ];
    }

    public function messages()
    {
        return [
            'pharmacy_id.unique' => trans('client::validation.adding_pharmacy_already_exist'),
        ] ;
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

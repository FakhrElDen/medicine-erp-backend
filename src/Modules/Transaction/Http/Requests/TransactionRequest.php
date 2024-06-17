<?php

namespace Modules\Transaction\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from'              => 'nullable|before:to',
            'to'                => 'nullable',
            'pagination_number' => 'nullable',
            'code'              => 'nullable',
            'client_id'         => 'nullable|exists:clients,id',
            'pharmacy_id'       => 'nullable|exists:pharmacies,id',
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

<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sorted_by'         => 'nullable',
            'created_at'        => 'nullable',
            'pharmacy_id'       => 'nullable',
            'invoice_number'    => 'nullable',
            'invoice_id'        => 'nullable',
            'basket_number'     => 'nullable',
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

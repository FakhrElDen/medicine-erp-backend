<?php

namespace Modules\Warehouse\Http\Requests\Retail;

use Illuminate\Foundation\Http\FormRequest;

class ListingPreparedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'created_at'               => 'nullable|required_with:invoice_number,pharmacy_id',
            'pharmacy_id'              => 'nullable|required_with:invoice_number,created_at',
            'invoice_number'           => 'nullable|required_with:pharmacy_id,created_at',
            'invoice_id'               => 'nullable',
            'corridor_id'              => 'nullable',
            'prepared_by'              => 'nullable',
            'basket_number'            => 'nullable',
            'auditor_id'               => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'created_at.required_with' => trans('warehouse::message.created_at_validate_related_fields'),
            'pharmacy_id.required_with' => trans('warehouse::message.pharmacy_id_validate_related_fields'),
            'invoice_number.required_with' => trans('warehouse::message.invoice_number_validate_related_fields'),
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

<?php

namespace Modules\Warehouse\Http\Requests\Bulk;

use Illuminate\Foundation\Http\FormRequest;

class ViewPreparedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'corridor_id'              => 'nullable',
            'invoice_number'           => 'nullable',
            'created_at'               => 'nullable',
            'pharmacy_id'              => 'nullable',
            'invoice_id'               => 'nullable|exists:orders,id',
            'sort_by'                  => 'nullable',
            'direction'                => 'nullable',
            'basket_number'            => 'nullable',
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

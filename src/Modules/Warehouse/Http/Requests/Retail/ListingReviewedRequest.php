<?php

namespace Modules\Warehouse\Http\Requests\Retail;

use Illuminate\Foundation\Http\FormRequest;

class ListingReviewedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'created_at'        => 'nullable',
            'pharmacy_id'       => 'nullable',
            'invoice_number'    => 'nullable',
            'paginated'         => 'nullable',
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

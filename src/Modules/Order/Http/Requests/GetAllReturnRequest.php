<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAllReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pharmacy_id'   => 'nullable|exists:pharmacies,id',
            'warehouse_id'  => 'nullable|exists:warehouses,id',
            'sort_by'       => ['string', 'in:corridor_id,product_name_en,product_name_ar,warehouse_id,manufacturer_en,manufacturer_ar'],
            'direction'     => ['string', 'in:asc,desc'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Warehouse\Rules\ValidatePackagingRule;

class CompleteInventoryingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'packaging'                     => ['required', 'array', new ValidatePackagingRule()],
            'packaging.*.bags_num'          => 'integer|nullable',
            'packaging.*.cartons_num'       => 'integer|nullable',
            'packaging.*.fridges_num'       => 'integer|nullable',
            'packaging.*.invoices_num'      => 'integer|nullable',
            'non_inventoried_batches_ids'   => 'array|nullable',
            'order_id'                      => 'required',
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

<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivingReviewerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from'              => 'nullable',
            'to'                => 'nullable',
            'created_at'        => 'nullable',
            'status'            => 'required',
            'code'              => 'nullable',
            'status'            => 'nullable',
            'warehouse_id'      => 'nullable',
            'purchase_number'   => 'nullable',
            'pagination_number' => 'nullable',
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

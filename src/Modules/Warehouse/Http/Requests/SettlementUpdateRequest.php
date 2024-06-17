<?php

namespace Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Warehouse\Rules\SettlementMaxQuantityRule;

class SettlementUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:batch_cart_warehouse,id,returned_quantity,NULL'],
            'quantity' => ['bail', 'required', 'integer', 'min:0', new SettlementMaxQuantityRule($this->id)],
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

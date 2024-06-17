<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Setting\Entities\Setting;
use Modules\Warehouse\Enums\BasketStatus;

class BasketSetDamagedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                'max:'. Setting::getValue('baskets_number'),
                Rule::unique('baskets', 'number')->where('status', BasketStatus::DAMAGED),
            ],
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

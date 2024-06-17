<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientSalesReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pharmacy_id' => 'nullable|required_if:client_id,null|exists:pharmacies,id',
            'client_id' => 'nullable|required_if:pharmacy_id,null|exists:clients,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
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

<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUpdatedBatchesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manufacturer_id'           => 'nullable|exists:manufacturers,id',
            'corridor_id'               => 'nullable|exists:corridors,id',
            'stand'                     => 'nullable',
            'shelf'                     => 'nullable',
            'warehouse_id'              => 'nullable|exists:warehouses,id',
            'supplied_at'               => 'nullable',
            'supplier_id'               => 'nullable|exists:users,id',
            'created_by'                => 'nullable|exists:users,id',
            'updated_by'                => 'nullable|exists:users,id',
            'receiver_reviewer_id'       => 'nullable|exists:users,id',
            'storing_worker_id'         => 'nullable|exists:users,id',
            'second_user_id'            => 'nullable|exists:users,id',
            'quantity_more_than_zero'   => 'nullable',
            'price_from'                => 'nullable',
            'price_to'                  => 'nullable',
            'sort_by'                   => 'nullable',
            'direction'                 => 'nullable|in:desc,asc',
            'name'                      => 'nullable',
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

<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cart\Rules\CheckCartItemsNumberRule;
use Modules\Cart\Rules\CheckItemAlreadyInCartRule;
use Modules\Cart\Rules\CheckPharmacyDebtLimitRule;
use Modules\Cart\Rules\CheckLimitedProductRule;
use Modules\Cart\Rules\CheckWarehouseQuantityRule;
use Modules\Cart\Rules\CheckProductBatchesQuantityRule;
use Modules\Cart\Rules\CheckSameWarehouseIdInOrderRule;
use Modules\Cart\Rules\CheckProductQuantityForBonusRule;
use Modules\Cart\Rules\CheckProductQuantityForOfferRule;
use Modules\Cart\Rules\CheckWarehouseBatchesQuantityRule;

class AddToCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pharmacy_id'                   => 'bail|required|integer|exists:pharmacies,id',
            'client_id'                     => 'required|integer|exists:clients,id',
            'shift_id'                      => 'required|integer|exists:shifts,id',
            'product_id'                    => [
                'required',
                'integer',
                'exists:products,id',
                new CheckWarehouseBatchesQuantityRule(),
                new CheckProductQuantityForBonusRule(),
                new CheckProductQuantityForOfferRule(),
                new CheckProductBatchesQuantityRule(),
                new CheckWarehouseQuantityRule(),
                new CheckLimitedProductRule(),
                new CheckItemAlreadyInCartRule(),
                new CheckPharmacyDebtLimitRule(),
                new CheckCartItemsNumberRule(),
            ],
            'warehouse_id'                  => ['required', 'integer', 'exists:warehouses,id', new CheckSameWarehouseIdInOrderRule()],
            'client_discount_difference'    => 'required',
            'status'                        => 'required|in:0',
            'quantity'                      => ['required', 'integer', 'numeric', 'gt:0'],
            'bonus'                         => 'nullable|integer',
            'discount'                      => 'required',
            'note'                          => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'quantity.gt' => trans('cart::message.insert_quantity_with_zero'),
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

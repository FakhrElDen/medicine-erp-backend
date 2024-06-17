<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Rules\CheckProhibitedBatchRule;
use Modules\Purchase\Rules\CheckCartPurchaseStatusRule;
use Modules\Purchase\Rules\CheckCartPurchaseQuantityReviewingRule;

class InventoryingProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'warehouse_id'                  => 'required|exists:warehouses,id',
            'product_id'                    => 'required|exists:products,id',
            'purchase_id'                   => 'required|exists:purchases,id',
            'supplier_id'                   => 'required|exists:users,id',
            'cart_purchase_id'              => ['required', 'exists:cart_purchases,id', new CheckCartPurchaseStatusRule()],
            'quantity'                      => ['required', 'gt:0', new CheckCartPurchaseQuantityReviewingRule()],
            'expired_at'                    => 'required|date|after:now',
            'operating_number'              => ['required', 'string', 'regex:/^[A-Za-z0-9]{12}$/', new CheckProhibitedBatchRule()],
            'items_number_in_packet'        => 'required|integer',
            'packets_number_in_package'     => 'required|integer',
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

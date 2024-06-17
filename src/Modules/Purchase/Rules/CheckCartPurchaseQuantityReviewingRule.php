<?php

namespace Modules\Purchase\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Purchase\Repositories\CartPurchaseRepository;

class CheckCartPurchaseQuantityReviewingRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value)
    {
        $cartPurchaseRepository = app(CartPurchaseRepository::class);
        $item = $cartPurchaseRepository->find(request()->cart_purchase_id);

        if ($item->quantity - $item->inventoried_quantity >= request()->quantity) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('purchase::message.quantity_check');
    }
}

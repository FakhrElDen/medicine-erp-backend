<?php

namespace Modules\Purchase\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Purchase\Enums\CartPurchaseStatus;
use Modules\Purchase\Repositories\CartPurchaseRepository;

class CheckCartPurchaseStatusRule implements Rule
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
        $cartPurchaseItem = $cartPurchaseRepository->find(request()->cart_purchase_id);
        if ($cartPurchaseItem->status == CartPurchaseStatus::INVENTORIED) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('purchase::message.status_check');
    }
}

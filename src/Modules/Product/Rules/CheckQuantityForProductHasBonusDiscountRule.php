<?php

namespace Modules\Product\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Repositories\CartRepository;

class CheckQuantityForProductHasBonusDiscountRule implements Rule
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
        $cartRepository = app(CartRepository::class);
        if (request()->has('cart_id')) {
            $cartItem = $cartRepository->find(request()->cart_id);
            $totalQuantity = $cartItem->quantity + $cartItem->bonus;
            if ($cartItem->product->offers()->exists()) {
                return request()->quantity % ($totalQuantity / $cartItem->bonus) === 0 ? true : false;
            }
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
        return trans('product::message.quantity_check');
    }
}

<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Enums\CartStatus;
use Modules\Cart\Repositories\CartRepository;

class CheckItemAlreadyInCartRule implements Rule
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
        /**
         * @var CartRepository $cartRepository
         */
        $cartRepository = app(CartRepository::class);

        $item = $cartRepository->getCart([
            'status'        => CartStatus::PENDING,
            'product_id'    => request()->product_id,
            'pharmacy_id'   => request()->pharmacy_id,
        ]);

        if ($item->isNotEmpty()) {
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
        return trans('cart::message.item_already_in_cart');
    }
}

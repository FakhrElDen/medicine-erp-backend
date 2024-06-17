<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Modules\Cart\Enums\CartStatus;
use Modules\Cart\Repositories\CartRepository;

class CheckCartItemsNumberRule implements Rule
{
    protected $cart_items_limit;

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

        $settings = collect(Cache::get('settings'));
        $this->cart_items_limit = $settings->firstWhere('key', 'cart_items_limit')->value;

        $cart = $cartRepository->getCart([
            'status'        => CartStatus::PENDING,
            'product_id'    => request()->product_id,
            'pharmacy_id'   => request()->pharmacy_id,
        ]);
        
        $cartItems = $cartRepository->calculateItemsNumber($cart);

        if ((int) $cartItems >= (int) $this->cart_items_limit) {
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
        return trans('cart::message.cart_items_limit') . " {$this->cart_items_limit}";
    }
}

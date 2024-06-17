<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Enums\CartStatus;
use Modules\Cart\Repositories\CartRepository;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Product\Repositories\ProductRepository;

class CheckPharmacyDebtLimitRule implements Rule
{
    protected $pharmacy_debt_limit;

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
         *  @var CartRepository $cartRepository
         */
        $cartRepository = app(CartRepository::class);

        /**
         *  @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        
        /**
         *  @var PharmacyRepository $pharmacyRepository
         */
        $pharmacyRepository = app(PharmacyRepository::class);

        $pharmacy = $pharmacyRepository->find(request()->pharmacy_id);
        $this->pharmacy_debt_limit = $pharmacy?->debt_limit;

        $product = $productRepository->find(request()->product_id);

        $cart = $cartRepository->getCart([
            'status'        => CartStatus::PENDING,
            'pharmacy_id'   => request()->pharmacy_id,
        ]);

        $cart_item_total = $cartRepository->calculateCartItemTotal($product->price, intval(request()->quantity), request()->discount);

        $cartTotal = $cart->sum('total') + $cart_item_total;

        if ($cartTotal > $this->pharmacy_debt_limit) {
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
        return trans('client::message.the_pharmacy_debt_limit_is') . " {$this->pharmacy_debt_limit}";
    }
}

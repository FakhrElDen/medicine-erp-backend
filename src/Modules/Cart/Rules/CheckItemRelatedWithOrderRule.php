<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Enums\CartStatus;
use Modules\Cart\Repositories\CartSubBatchRepository;
use Modules\Cart\Repositories\CartRepository;
use Modules\Product\Repositories\ProductRepository;

class CheckItemRelatedWithOrderRule implements Rule
{
    protected $reason;

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

        /**
         * @var CartSubBatchRepository $cartSubBatchRepository
         */
        $cartSubBatchRepository = app(CartSubBatchRepository::class);

        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);

        $product = $productRepository->getProductByBarcode(request()->barcode);
        if (!$product) {
            return false;
        }

        $cartItem = $cartRepository->getCart([
            'product_id'    => request()->product_id,
            'order_id'      => request()->order_id,
        ]);
        
        if (!$cartItem) {
            return false;
        }

        $batchItem = $cartSubBatchRepository->checkItemInventoried($cartItem);
        if (!$batchItem) {
            $this->reason = 1;

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
        if ($this->reason == 1) {
            return trans('cart::message.item_already_inventoried');
        } else {
            return trans('cart::message.item_not_related_with_order');
        }
    }
}

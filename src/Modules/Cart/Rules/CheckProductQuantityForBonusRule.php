<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Enums\OfferType;
use Modules\Product\Repositories\ProductRepository;

class CheckProductQuantityForBonusRule implements Rule
{
    protected $bonus;

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
        $productRepository = app(ProductRepository::class);

        $product = $productRepository->find(request()->product_id);

        if ($product->offers()->exists()) {
            $productOffer = $product->offers()->first();
            if ($productOffer->type == OfferType::QUANTITY) {
                $productOfferQuantity = $productOffer->quantity_for_offer;
                if (
                    request()->quantity == $productOfferQuantity ||
                    request()->quantity % $productOfferQuantity === 0 ||
                    request()->quantity < $productOfferQuantity
                ) {
                    return true;
                }

                $this->bonus = $productOffer;
                return false;
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
        return trans('product::message.the_product_has_bonus') . " {$this->bonus->quantity_for_offer} + {$this->bonus->offer_value}";
    }
}

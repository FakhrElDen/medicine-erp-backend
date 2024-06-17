<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Enums\OfferType;
use Modules\Product\Repositories\ProductRepository;

class CheckProductQuantityForOfferRule implements Rule
{
    protected $first_offer;

    protected $second_offer;

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
     * *first check if product has offer
     * second if offer type is percentage can has tow offers
     * so get two vales @param maxQuantity and @param minQuantity
     * then check ordered quantity equal maxQuantity OR less than or equal minQuantity OR multiples from maxQuantity
     * 
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value)
    {
        $productRepository = app(ProductRepository::class);

        $product = $productRepository->find(request()->product_id);

        if ($product->offers()->exists()) {
            $productOffers = $product->offers();

            if ($productOffers->first()->type == OfferType::PERCENTAGE) {

                $maxQuantity = $product->offers->max('quantity_for_offer');
                $minQuantity = $product->offers->min('quantity_for_offer');

                if (
                    request()->quantity == $maxQuantity ||
                    request()->quantity <= $minQuantity ||
                    request()->quantity % $maxQuantity === 0 ||
                    request()->quantity % $minQuantity === 0
                ) {
                    return true;
                }

                $result = $productOffers->get();
                $this->first_offer = $result[0];
                isset($result[1]) ? $this->second_offer = $result[1] : null;
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
        if (!is_null($this->second_offer)) {
            return trans('product::message.the_product_has_offer') . " {$this->first_offer->quantity_for_offer} + %{$this->first_offer->offer_value}\n" .
                trans('product::message.the_product_has_offer') . " {$this->second_offer->quantity_for_offer} + %{$this->second_offer->offer_value}";
        } else {
            return trans('product::message.the_product_has_offer') . " {$this->first_offer->quantity_for_offer} + %{$this->first_offer->offer_value}";
        }
    }
}

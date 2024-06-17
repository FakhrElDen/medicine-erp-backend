<?php

namespace Modules\Cart\Rules;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Repositories\CartRepository;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Product\Repositories\ProductRepository;

class CheckLimitedProductRule implements Rule
{
    protected $limit;

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
        $this->reason = 0;

        $cartRepository = app(CartRepository::class);
        $productRepository = app(ProductRepository::class);
        $pharmacyRepository = app(PharmacyRepository::class);

        $product = $productRepository->find(request()->product_id);
        $pharmacy = $pharmacyRepository->find(request()->pharmacy_id);

        if ($product->is_limited == 1) {

            $this->limit = $product->limited_quantity * $pharmacy?->iterate_available_quantity;

            $now = Carbon::now();
            $validDate = $now->copy()->subDays($pharmacy?->all);

            $cartItem = $cartRepository->getCart([
                'product_id' => request()->product_id,
                'pharmacy_id' => request()->pharmacy_id,
                'client_id' => request()->client_id,
            ])->filter(function ($item) use ($validDate, $now) {
                $createdAt = Carbon::parse($item->created_at);

                return $createdAt->between($validDate, $now);
            });

            $availableQuantity = $this->limit - $cartItem->sum('quantity');

            if ($availableQuantity < request()->quantity) {
                $this->reason = 1;

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
        if ($this->reason == 0) {
            return trans('product::message.the_product_is_limited') . " {$this->limit}";
        }

        return trans('client::message.pharmacy_iterate_available_quantity');
    }
}

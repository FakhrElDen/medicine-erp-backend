<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Repositories\ProductRepository;

class CheckProductBatchesQuantityRule implements Rule
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
     * get ordered quantity by adding quantity on bonus
     * then mapping on product batches if there batch
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value)
    {
        $productRepository = app(ProductRepository::class);

        $product = $productRepository->find(request()->product_id)->load('batches.subBatches');

        if ($product->offers()->exists()) {

            $ordered_quantity = request()->quantity + request()->bonus;
            $found = false;

            $product->batches->subBatches->each(function ($item) use ($ordered_quantity, &$found) {
                if ($item->current_quantity >= $ordered_quantity) {
                    $found = true;

                    return false;
                }
            });

            return $found;
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
        return trans('product::message.no_batch_has_enough_quantity');
    }
}

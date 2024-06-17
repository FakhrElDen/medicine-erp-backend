<?php

namespace Modules\Product\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cart\Repositories\CartRepository;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\BatchRepository;

class CheckQuantityRule implements Rule
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
        $batchRepository = app(BatchRepository::class);

        $batch = $batchRepository->find(request()->input('batch_id'));

        // dd($value);
        
        if($batch->current_quantity < intVal($value)){
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
        return trans('product::message.quantaty_check_minus');
    }
}

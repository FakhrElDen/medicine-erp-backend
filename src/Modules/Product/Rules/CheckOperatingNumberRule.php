<?php

namespace Modules\Product\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Repositories\BatchRepository;

class CheckOperatingNumberRule implements Rule
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

        $batch = $batchRepository->all([
            'operating_number'  => request()->operating_number,
            'expired_at'        => request()->expired_at
        ])->first();

        if ($batch) {
            if ($batch->product_id != request()->product_id) {
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
        return trans('product::message.wrong_product_operating_number');
    }
}

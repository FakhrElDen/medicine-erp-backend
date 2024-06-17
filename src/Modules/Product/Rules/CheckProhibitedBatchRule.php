<?php

namespace Modules\Product\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Repositories\ProhibitedBatchRepository;

class CheckProhibitedBatchRule implements Rule
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
        $prohibitedBatchRepository = app(ProhibitedBatchRepository::class);

        $batch = $prohibitedBatchRepository->all([
            'operating_number' => request()->operating_number,
            'expiry_date'      => request()->expired_at
        ])->first();

        if ($batch) {
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
        return trans('product::message.batch_is_prohibited');
    }
}

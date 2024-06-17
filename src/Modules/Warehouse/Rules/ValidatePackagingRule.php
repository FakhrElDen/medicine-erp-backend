<?php

namespace Modules\Warehouse\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePackagingRule implements Rule
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
        $values = array_values(request()->packaging);

        if (request()->bulk == true) {
            return true;
        }

        if (empty(array_filter($values))) {
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
        return trans('warehouse::message.packaging_required');
    }
}

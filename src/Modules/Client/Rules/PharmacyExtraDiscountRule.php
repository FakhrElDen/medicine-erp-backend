<?php

namespace Modules\Client\Rules;

use Illuminate\Contracts\Validation\Rule;

class PharmacyExtraDiscountRule implements Rule
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
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        return $user->hasAnyPermission(['super_admin', 'accountant_manager']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('user::message.check_sales_director');
    }
}

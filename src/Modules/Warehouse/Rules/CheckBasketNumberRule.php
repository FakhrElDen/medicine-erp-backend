<?php

namespace Modules\Warehouse\Rules;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Validation\Rule;
use Modules\Warehouse\Enums\BasketStatus;
use Modules\Warehouse\Repositories\BasketRepository;

class CheckBasketNumberRule implements Rule
{
    protected $damaged;

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
        $basketRepository = app(BasketRepository::class);
        $basket = $basketRepository->all(['number' => request()->number])->first();

        if ($basket?->status == BasketStatus::DAMAGED) {
            $this->damaged = true;
            return false;
        }

        $settings = collect(Cache::get('settings'));
        $basketsTotalNumber = $settings->firstWhere('key', 'baskets_number')->value;

        if (request()->number >= 1 && request()->number <= $basketsTotalNumber) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->damaged == true || $this->damaged == 1) {
            return trans('warehouse::message.basket_is_damaged');
        } else {
            return trans('warehouse::message.basket_not_found');
        }
    }
}

<?php

namespace Modules\Setting\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Warehouse\Enums\BasketStatus;
use Modules\Warehouse\Repositories\BasketRepository;

class CheckMaxExistingNumber implements ValidationRule
{
    protected BasketRepository $basket_repository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->basket_repository = resolve(BasketRepository::class);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->basket_repository->getMaxBasketNumber(BasketStatus::DAMAGED) > $value) {
            $fail('setting::message.removing_damaged_baskets')->translate();
        } elseif ($this->basket_repository->getMaxBasketNumber(BasketStatus::UNDAMAGED) > $value) {
            $fail('setting::message.removing_active_baskets')->translate();
        }
    }
}

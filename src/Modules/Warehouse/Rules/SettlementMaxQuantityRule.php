<?php

namespace Modules\Warehouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Warehouse\Repositories\SettlementRepository;

class SettlementMaxQuantityRule implements Rule
{
    protected $quantity;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $settlement_repository = app(SettlementRepository::class);

        $batch_cart_warehouse = $settlement_repository->find($id);

        $this->quantity = $batch_cart_warehouse->cartSubBatch->quantity;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->quantity;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.max.numeric', ['attribute' => trans('validation.attributes.quantity'), 'max' => $this->quantity]);
    }
}

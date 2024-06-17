<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Order\Repositories\OrderRepository;

class CheckSameWarehouseIdInOrderRule implements Rule
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
         * @var OrderRepository $orderRepository
         */
        $orderRepository = app(OrderRepository::class);
        $order = $orderRepository->returnOpenOrder(request()->pharmacy_id);
        if ($order) {
            if ($order->warehouse_id != request()->warehouse_id) {
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
        return trans('warehouse::message.same_warehouse_id_in_order');
    }
}

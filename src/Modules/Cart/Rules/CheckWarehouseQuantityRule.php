<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Warehouse\Repositories\WarehouseRepository;

class CheckWarehouseQuantityRule implements Rule
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
     * check product quantity in warehouse by:
     * getting warehouse by id and get product's quantity in this warehouse by relation between them
     * then compare it with ordered quantity
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value)
    {
        /**
         * @var WarehouseRepository $warehouseRepository
         */
        $warehouseRepository = app(WarehouseRepository::class);

        $warehouseQuantity = $warehouseRepository->find(request()->warehouse_id)
            ->products()->where('product_id', request()->product_id)->first();

        $warehouseQuantity = $warehouseQuantity ? $warehouseQuantity->warehouse_quantity : 0;

        return $warehouseQuantity < request()->quantity ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('warehouse::message.insufficient_quantity');
    }
}

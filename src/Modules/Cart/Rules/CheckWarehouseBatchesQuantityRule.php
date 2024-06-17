<?php

namespace Modules\Cart\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Warehouse\Repositories\WarehouseRepository;

class CheckWarehouseBatchesQuantityRule implements Rule
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
         * @var WarehouseRepository $warehouseRepository
         */
        $warehouseRepository = app(WarehouseRepository::class);

        $batchesQuantity = $warehouseRepository->find(request()->warehouse_id)
            ->subBatches()->whereHas('parentBatch', function ($query) {
                $query->where('product_id', request()->product_id);
            })->sum('current_quantity');

        return $batchesQuantity < request()->quantity ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('warehouse::message.insufficient_quantity_in_warehouse');
    }
}

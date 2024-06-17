<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Product\Entities\Product;

class ReturnablesResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Order\Transformers\ReturnablesResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($returnable_item) {
            if ($returnable_item->returnable_type == CartSubBatch::class && $returnable_item->returnable) {
                $returnable_item->returnable->cart->product->load('manufacturer', 'warehouses');
                $returnable_item['product_name'] = $returnable_item->returnable->cart->product->name;
                $returnable_item['product_location'] = $returnable_item->returnable->cart->product->getMainLocation();
                $returnable_item['manufacturer_name'] = $returnable_item->returnable->cart->product->manufacturer->name;
            } elseif ($returnable_item->returnable_type == Product::class && $returnable_item->returnable) {
                $returnable_item->returnable->load('manufacturer', 'warehouses');
                $returnable_item['product_name'] = $returnable_item->returnable->name;
                $returnable_item['product_location'] = $returnable_item->returnable->getMainLocation();
                $returnable_item['manufacturer_name'] = $returnable_item->returnable->manufacturer->name;
            }

            return $returnable_item;
        });

    }
}

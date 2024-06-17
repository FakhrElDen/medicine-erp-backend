<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\BatchOperator;
use Modules\Product\Entities\SubBatch;
use Modules\Product\Entities\Product;

class BatchDatabaseSeeder extends Seeder
{
    function generateDivisibleByThree()
    {
        while (true) {
            $number = rand(500, 1000); // Adjust the range as needed
            if ($number % 3 === 0) {
                return $number;
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::with('warehouses')->get();

        $products->each(function ($product) {
            $quantity = $this->generateDivisibleByThree();
            $product->warehouses->each(function ($warehouseProduct) use ($product, $quantity) {

                $batches = Batch::factory()->count(3)->create([
                    'product_id'       => $product->id,
                    'packet'           => $product->items_number_in_packet,
                    'package'          => $product->packets_number_in_package,
                    'quantity'         => $quantity,
                    'price'            => $product->price,
                ]);

                $batches->each(function ($batch) use ($warehouseProduct, $quantity) {
                    SubBatch::factory()->count(3)->create([
                        'batch_id'          => $batch->id,
                        'warehouse_id'      => $warehouseProduct->pivot->warehouse_id,
                        'corridor_id'       => $warehouseProduct->pivot->corridor_id,
                        'stand'             => $warehouseProduct->pivot->stand,
                        'shelf'             => $warehouseProduct->pivot->shelf,
                        'current_quantity'  => $quantity / 3,
                    ]);
                });

                $batches->each(function ($batch) {
                    BatchOperator::factory()->count(1)->create();
                });
            });
        });
    }
}

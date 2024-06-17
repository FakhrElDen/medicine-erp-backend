<?php

namespace Modules\Cart\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Entities\Cart;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'created_by' => 1, // Fixed user ID for all products
            // 'updated_by' => 1, // Fixed user ID for all products
            // 'product_id' => $this->faker->numberBetween(1, 40),
            // 'batch_id' => $this->faker->name(),
            // 'description' => $this->faker->paragraph(),
            // 'sku' => $this->faker->unique()->randomNumber(),
            // 'total_quantity' => $this->faker->numberBetween(1, 100),
            // 'limited_quantity' => $this->faker->numberBetween(1, 100),
            // 'is_limited' => $this->faker->boolean(),
            // 'manufacturer_id' => 1,
            // 'price' => $this->faker->randomFloat(2, 10, 100),
            // 'taxes' => $this->faker->randomFloat(2, 0, 10),
            // 'normal_discount' => $this->faker->numberBetween(1, 30),
            // 'items_number_in_packet' => $this->faker->numberBetween(1, 10),
            // 'packets_number_in_package' => $this->faker->numberBetween(1, 10),

            // $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            // $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('batch_id')->constrained('product_batches')->cascadeOnDelete();
            // $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            // $table->integer('cart_number');
            // $table->integer('quantity');
            // $table->float('price');
            // $table->integer('status')->default(0);
            // $table->float('taxes');
            // $table->float('subtotal');
            // $table->float('total');
            // $table->integer('client_discount_difference')->nullable()->default(0);
            // $table->integer('client_discount_difference_value')->nullable()->default(0);
            // $table->text('note')->nullable();
            // $table->float('bonus')->nullable();
            // $table->float('product_discount');
            // $table->float('discount')->nullable();
            // $table->float('discount_value')->nullable();
        ];
    }
}

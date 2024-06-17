<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_by' => 1, // Fixed user ID for all products
            'updated_by' => 1, // Fixed user ID for all products
            'name' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'sku' => $this->faker->unique()->randomNumber(),
            'total_quantity' => $this->faker->numberBetween(1, 100),
            'limited_quantity' => $this->faker->numberBetween(1, 100),
            'is_limited' => $this->faker->boolean(),
            'manufacturer_id' => 1,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'taxes' => $this->faker->randomFloat(2, 0, 10),
            'normal_discount' => $this->faker->numberBetween(1, 30),
            'items_number_in_packet' => $this->faker->numberBetween(1, 10),
            'packets_number_in_package' => $this->faker->numberBetween(1, 10),
        ];
    }
}

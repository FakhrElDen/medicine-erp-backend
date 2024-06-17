<?php

namespace Modules\Order\Database\factories;

use Modules\Order\Entities\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_by'        => $this->faker->numberBetween(14, 25),
            'sales_id'          => $this->faker->numberBetween(14, 25),
            'delivery_id'       => 10,
            'client_id'         => $this->faker->numberBetween(1, 5),
            'pharmacy_id'       => $this->faker->numberBetween(1, 18),
            'track_id'          => $this->faker->numberBetween(1, 2),
            'city_id'           => $this->faker->numberBetween(1, 2),
            'area_id'           => $this->faker->numberBetween(1, 5),
            'shift_id'          => $this->faker->numberBetween(1, 2),
            'total_quantity'    => $this->faker->numberBetween(2, 100),
            'total_taxes'       => $this->faker->numberBetween(5, 10),
            'total_price'       => $this->faker->numberBetween(100, 2000),
            'total'             => $this->faker->numberBetween(100, 1000),
            'returns'           => $this->faker->numberBetween(100, 1000),
            'extra_discount'    => $this->faker->numberBetween(1, 10),
            'status'            => $this->faker->numberBetween(1, 7),
            'order_number'      => $this->faker->numberBetween(1, 190),
            'shipping_type'     => $this->faker->numberBetween(0, 1),
            'latitude'          => $this->faker->latitude(),
            'longitude'         => $this->faker->longitude(),
            'note'              => $this->faker->paragraph(),
            'created_at'        => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

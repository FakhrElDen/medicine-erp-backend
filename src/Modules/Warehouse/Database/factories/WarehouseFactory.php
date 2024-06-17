<?php

namespace Modules\Warehouse\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Warehouse\Entities\Warehouse;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->numberBetween(0, 3),
            'address' => $this->faker->address(),
        ];
    }
}

<?php

namespace Modules\Warehouse\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Warehouse\Entities\Corridor;

class CorridorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Corridor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // return [
        //     'number' => $this->faker->numberBetween(1, 4),
        // ];
    }
}

<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\SubBatch;

class SubBatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubBatch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'purchase_id'            => rand(1, 4),
            'production_date'        => $this->faker->dateTimeBetween('-1 year', 'now'),
            'discount'               => $this->faker->numberBetween(1, 30),
        ];
    }
}

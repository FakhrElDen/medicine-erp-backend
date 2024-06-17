<?php

namespace Modules\Product\Database\factories;

use Modules\Product\Entities\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Batch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'operating_number'       => $this->faker->regexify('[A-Za-z0-9]{12}'),
            'expired_at'             => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}

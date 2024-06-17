<?php

namespace Modules\Product\Database\factories;

use Modules\Product\Entities\BatchOperator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Entities\User;

class BatchOperatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BatchOperator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $supplier = User::whereHas('roles', function ($query) {
            $query->where('name', 'supplier');
        })->inRandomOrder()->first();

        $storing_worker = User::whereHas('roles', function ($query) {
            $query->where('name', 'storing_worker');
        })->inRandomOrder()->first();
        
        $receiver_reviewer = User::whereHas('roles', function ($query) {
            $query->where('name', 'receiving_reviewer');
        })->inRandomOrder()->first();
        
        $receiver_distributor = User::whereHas('roles', function ($query) {
            $query->where('name', 'receiving_distributor');
        })->inRandomOrder()->first();

        return [
            'supplier_id'               => $supplier->id,
            'receiver_reviewer_id'      => $receiver_reviewer->id,
            'created_by'                => $receiver_reviewer->id,
            'receiver_distributor_id'   => $receiver_distributor->id,
            'storing_worker_id'         => $storing_worker->id,
            'distributor_received_at'   => $receiver_distributor ? $this->faker->dateTimeBetween('-2 month', '-1 month') : $receiver_distributor,
            'stored_at'                 => $storing_worker ? $this->faker->dateTimeBetween('-1 month', 'now') : $storing_worker,
            'supplied_at'               => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'reviewer_received_at'       => $this->faker->dateTimeBetween('-5 month', '-3 month'),
        ];
    }
}

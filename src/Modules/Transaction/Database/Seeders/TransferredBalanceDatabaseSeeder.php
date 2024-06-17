<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transaction\Entities\TransferredBalance;

class TransferredBalanceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferredBalance::create([
            'client_id'                 => 1,
            'from_pharmacy_id'          => 1,
            'to_pharmacy_id'            => 2,
            'user_id'                   => 6,
            'from_previous_balance'     => 12000,
            'to_previous_balance'       => 10000,
            'amount'                    => 2000,
        ]);

        TransferredBalance::create([
            'client_id'                 => 1,
            'from_pharmacy_id'          => 1,
            'to_pharmacy_id'            => 2,
            'user_id'                   => 6,
            'from_previous_balance'     => 10000,
            'to_previous_balance'       => 12000,
            'amount'                    => 2000,
        ]);

        TransferredBalance::create([
            'client_id'                 => 1,
            'from_pharmacy_id'          => 3,
            'to_pharmacy_id'            => 1,
            'user_id'                   => 6,
            'from_previous_balance'     => 10000,
            'to_previous_balance'       => 8000,
            'amount'                    => 7000,
        ]);

        TransferredBalance::create([
            'client_id'                 => 2,
            'from_pharmacy_id'          => 5,
            'to_pharmacy_id'            => 6,
            'user_id'                   => 6,
            'from_previous_balance'     => 25000,
            'to_previous_balance'       => 10000,
            'amount'                    => 5000,
        ]);

        TransferredBalance::create([
            'client_id'                 => 2,
            'from_pharmacy_id'          => 5,
            'to_pharmacy_id'            => 6,
            'user_id'                   => 6,
            'from_previous_balance'     => 20000,
            'to_previous_balance'       => 15000,
            'amount'                    => 2500,
        ]);

        TransferredBalance::create([
            'client_id'                 => 2,
            'from_pharmacy_id'          => 7,
            'to_pharmacy_id'            => 5,
            'user_id'                   => 6,
            'from_previous_balance'     => 30000,
            'to_previous_balance'       => 17500,
            'amount'                    => 15000,
        ]);
    }
}

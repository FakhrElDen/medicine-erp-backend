<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transaction\Entities\CashPayment;
use Modules\Transaction\Entities\CashReceive;

class TransactionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //cash receive seeders
        
        CashReceive::create([
            'client_id'         => 1,
            'pharmacy_id'       => 4,
            'user_id'           => 6,
            'previous_balance'  => "60000",
            'received_amount'   => "25000",
            'remaining_amount'  => "35000",
        ]);

        CashReceive::create([
            'client_id'         => "1",
            'pharmacy_id'       => "3",
            'user_id'           => 6,
            'previous_balance'  => "40000",
            'received_amount'   => "20000",
            'remaining_amount'  => "20000",
        ]);

        CashReceive::create([
            'client_id'         => "2",
            'pharmacy_id'       => "6",
            'user_id'           => 6,
            'previous_balance'  => "20000",
            'received_amount'   => "10000",
            'remaining_amount'  => "10000",
        ]);

        CashReceive::create([
            'client_id'         => "3",
            'pharmacy_id'       => "9",
            'user_id'           => 6,
            'previous_balance'  => "75000",
            'received_amount'   => "50000",
            'remaining_amount'  => "25000",
        ]);

        CashReceive::create([
            'client_id'         => "3",
            'pharmacy_id'       => "9",
            'user_id'           => 6,
            'previous_balance'  => "25000",
            'received_amount'   => "10000",
            'remaining_amount'  => "15000",
        ]);

        //cash payment seeders

        CashPayment::create([
            'client_id'         => "1",
            'pharmacy_id'       => "4",
            'user_id'           => 6,
            'previous_balance'  => "25000",
            'paid_amount'       => "10000",
            'remaining_amount'  => "35000",
        ]);

        CashPayment::create([
            'client_id'         => "1",
            'pharmacy_id'       => "3",
            'user_id'           => 6,
            'previous_balance'  => "40000",
            'paid_amount'       => "20000",
            'remaining_amount'  => "60000",
        ]);

        CashPayment::create([
            'client_id'         => "1",
            'pharmacy_id'       => "2",
            'user_id'           => 6,
            'previous_balance'  => "20000",
            'paid_amount'       => "10000",
            'remaining_amount'  => "30000",
        ]);
    }
}

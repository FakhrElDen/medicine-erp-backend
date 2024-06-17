<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transaction\Entities\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Notifications Add

        Notification::create([
            'client_id'             => 1,
            'pharmacy_id'           => 4,
            'user_id'               => 32,
            'notification_value'    => 5000,
            'type'                  => 1,
        ]);

        Notification::create([
            'client_id'             => 1,
            'pharmacy_id'           => 3,
            'user_id'               => 32,
            'notification_value'    => 1000,
            'type'                  => 1,
        ]);

        Notification::create([
            'client_id'             => 2,
            'pharmacy_id'           => 6,
            'user_id'               => 32,
            'notification_value'    => 2000,
            'type'                  => 1,
        ]);

        Notification::create([
            'client_id'             => 3,
            'pharmacy_id'           => 9,
            'user_id'               => 32,
            'notification_value'    => 5000,
            'type'                  => 1,
        ]);

        // Notifications Discount

        Notification::create([
            'client_id'             => 3,
            'pharmacy_id'           => 9,
            'user_id'               => 33,
            'notification_value'    => 2000,
            'type'                  => 0,
        ]);


        Notification::create([
            'client_id'             => 1,
            'pharmacy_id'           => 4,
            'user_id'               => 33,
            'notification_value'    => 2000,
            'type'                  => 0,
        ]);

        Notification::create([
            'client_id'             => 1,
            'pharmacy_id'           => 3,
            'user_id'               => 33,
            'notification_value'    => 4000,
            'type'                  => 0,
        ]);

        Notification::create([
            'client_id'             => 1,
            'pharmacy_id'           => 2,
            'user_id'               => 33,
            'notification_value'    => 2000,
            'type'                  => 0,
        ]);
    }
}

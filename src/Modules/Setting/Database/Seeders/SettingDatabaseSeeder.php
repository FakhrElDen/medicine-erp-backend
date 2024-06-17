<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Enums\SettingType;
use Modules\Setting\Repositories\SettingRepository;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key' => 'address',
            'type' => SettingType::STRING,
            'value' => 'ص كفر الجيار',
        ]);

        Setting::create([
            'key' => 'commercial_record',
            'type' => SettingType::INTEGER,
            'value' => '123123',
        ]);

        Setting::create([
            'key' => 'license_number',
            'type' => SettingType::INTEGER,
            'value' => '321321',
        ]);

        Setting::create([
            'key' => 'returned_roles',
            'type' => SettingType::STRING,
            'value' => 'مرتجع التلاجة فى نفس اليوم/التالف ثانى يوم من تاريخ التسليم و اقصى مرتجع 7 ايام من تاريخ الفاتورة',
        ]);

        Setting::create([
            'key' => 'sales_service',
            'type' => SettingType::JSON,
            'value' => [
                '01102239758',
                '01102239758',
                '01102239758',
                '01102239758',
            ],
        ]);

        Setting::create([
            'key' => 'baskets_number',
            'type' => SettingType::INTEGER,
            'value' => '250',
        ]);

        Setting::create([
            'key' => 'corridors_number',
            'type' => SettingType::INTEGER,
            'value' => '5',
        ]);

        Setting::create([
            'key' => 'high_price',
            'type' => SettingType::INTEGER,
            'value' => '200',
        ]);

        Setting::create([
            'key' => 'cart_items_limit',
            'type' => SettingType::INTEGER,
            'value' => '25',
        ]);

        Setting::create([
            'key' => 'self_limit',
            'type' => SettingType::INTEGER,
            'value' => '25',
        ]);

        Setting::create([
            'key' => 'stand_limit',
            'type' => SettingType::INTEGER,
            'value' => '25',
        ]);

        Setting::create([
            'key' => 'minutes_expiration_session',
            'type' => SettingType::INTEGER,
            'value' => '480',
        ]);

        Setting::create([
            'key' => 'remaining_months_for_expiration',
            'type' => SettingType::INTEGER,
            'value' => '6',
        ]);

        Setting::create([
            'key' => 'date_filter_by_day',
            'type' => SettingType::INTEGER,
            'value' => '10',
        ]);

        Setting::create([
            'key' => 'date_filter_beginning_of_month',
            'type' => SettingType::INTEGER,
            'value' => '30',
        ]);

        Cache::delete('settings');
        Cache::rememberForever('settings', function () {
            $settingRepository = app(SettingRepository::class);

            return $settingRepository->all();
        });
    }
}

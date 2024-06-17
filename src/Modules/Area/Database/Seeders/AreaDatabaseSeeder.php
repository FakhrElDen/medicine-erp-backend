<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Area\Entities\Area;
use Modules\Area\Entities\City;
use Modules\Area\Entities\Country;

class AreaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::create([
            'name' => [
                'en' => 'Egypt',
                'ar' => 'مصر',
            ],
        ]);

        $city = City::create([
            'country_id' => $country->id,
            'name' => [
                'en' => 'cairo',
                'ar' => 'القاهرة',
            ],
        ]);

        $area = Area::create([
            'city_id' => $city->id,
            'name' => [
                'en' => 'Nasr City',
                'ar' => 'مدينة نصر',
            ],
        ]);

        $area = Area::create([
            'city_id' => $city->id,
            'name' => [
                'en' => 'Masr El-Gedida',
                'ar' => 'مصر الجديدة',
            ],
        ]);

        $area = Area::create([
            'city_id' => $city->id,
            'name' => [
                'en' => 'Maadi',
                'ar' => 'المعادي',
            ],
        ]);

        $city = City::create([
            'country_id' => $country->id,
            'name' => [
                'en' => 'Alex',
                'ar' => 'اسكندريه',
            ],
        ]);

        $area = Area::create([
            'city_id' => $city->id,
            'name' => [
                'en' => 'Aboker',
                'ar' => 'ابوقير',
            ],
        ]);

        $area = Area::create([
            'city_id' => $city->id,
            'name' => [
                'en' => 'Maamora',
                'ar' => 'المعموره',
            ],
        ]);
    }
}

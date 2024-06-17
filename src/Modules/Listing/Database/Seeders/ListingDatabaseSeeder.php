<?php

namespace Modules\Listing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Listing\Entities\Listing;

class ListingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = Listing::create([
            'name' => 'القائمة الأولى صباحي',
            'type' => 0,
        ]);
        $list->pharmacies()->attach([1, 2, 3]);
        $list->users()->attach([17]);

        $list = Listing::create([
            'name' => 'القائمة الأولى مسائي',
            'type' => 1,
        ]);
        $list->pharmacies()->attach([1, 2, 3]);
        $list->users()->attach([16]);

        $list = Listing::create([
            'name' => 'القائمة الثانية صباحي',
            'type' => 0,
        ]);
        $list->pharmacies()->attach([4, 5, 6]);
        $list->users()->attach([15]);

        $list = Listing::create([
            'name' => 'القائمة الثانية مسائي',
            'type' => 1,
        ]);
        $list->pharmacies()->attach([4, 5, 6]);
        $list->users()->attach([18]);

        $list = Listing::create([
            'name' => 'القائمة الثالثة صباحي',
            'type' => 0,
        ]);
        $list->pharmacies()->attach([7, 8, 9]);
        $list->users()->attach([11]);

        $list = Listing::create([
            'name' => 'القائمة الثالثة مسائي',
            'type' => 1,
        ]);
        $list->pharmacies()->attach([7, 8, 9]);
        $list->users()->attach([14]);

        $list = Listing::create([
            'name' => 'القائمة الرابعة صباحي',
            'type' => 0,
        ]);
        $list->pharmacies()->attach([10, 11, 12]);
        $list->users()->attach([13]);

        $list = Listing::create([
            'name' => 'القائمة الرابعة مسائي',
            'type' => 1,
        ]);
        $list->pharmacies()->attach([10, 11, 12]);
        $list->users()->attach([12]);

        $list = Listing::create([
            'name' => 'القائمة الخامسة صباحي',
            'type' => 0,
        ]);
        $list->pharmacies()->attach([13, 14, 15]);
        $list->users()->attach([19]);

        $list = Listing::create([
            'name' => 'القائمة الخامسة مسائي',
            'type' => 1,
        ]);
        $list->pharmacies()->attach([13, 14, 15]);
        $list->users()->attach([10]);
    }
}

<?php

namespace Modules\Track\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Track\Entities\Shift;
use Modules\Track\Entities\Track;

class TrackDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shift::create([
            'name' => [
                'en' => 'shift 1',
                'ar' => 'دورة رقم 1',
            ],
            'from' => now(),
            'to' => now(),
        ]);

        Shift::create([
            'name' => [
                'en' => 'shift 2',
                'ar' => 'دورة رقم 2',
            ],
            'from' => Carbon::now()->subHours(2),
            'to' => now(),
        ]);

        $track = Track::create([
            'name' => [
                'en' => 'track 1',
                'ar' => 'خط السير رقم 1',
            ],
        ]);
        $track->users()->attach([34, 35]);
        $track->areas()->attach([1, 2]);
        $track->shifts()->attach([1, 2]);

        $track = Track::create([
            'name' => [
                'en' => 'track 2',
                'ar' => 'خط السير رقم 2',
            ],
        ]);
        $track->users()->attach([37, 38]);
        $track->shifts()->attach([2]);
        $track->areas()->attach([4, 5]);

        $track = Track::create([
            'name' => [
                'en' => 'track 3',
                'ar' => 'خط السير رقم 3',
            ],
        ]);
        $track->users()->attach([37, 36]);
        $track->shifts()->attach([1]);
        $track->areas()->attach([2]);
    }
}

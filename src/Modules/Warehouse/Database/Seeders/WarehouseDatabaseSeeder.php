<?php

namespace Modules\Warehouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Warehouse\Entities\Corridor;
use Modules\Warehouse\Entities\Warehouse;

class WarehouseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $corridors = [
            [
                'number' => 0,
                'color' => "#333333",
                'is_main_corridor' => 1,
            ],
            [
                'number' => 1,
                'color' => "#333333",
                'is_main_corridor' => 0,
            ],
            [
                'number' => 3,
                'color' => "#008253",
                'is_main_corridor' => 0,
            ],
            [
                'number' => 2,
                'color' => "#bb0000",
                'is_main_corridor' => 0,
            ],
            [
                'number' => 5,
                'color' => "#f6c000",
                'is_main_corridor' => 0,
            ],
            [
                'number' => 4,
                'color' => "#1700a7",
                'is_main_corridor' => 0,
            ]
        ];

        $warehouse = Warehouse::create([
            'name' => 'قطاعي',
            'address' => 'شبرا',
            'type' => 0,
        ]);
        $products = [
            [
                'product_id' => 1,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 2,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 3,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 4,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 5,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 6,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 7,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 8,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 9,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 10,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 11,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 12,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 13,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 14,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 15,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 16,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 17,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 18,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 19,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 20,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 21,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 22,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 23,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 24,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 25,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 26,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 27,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 28,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 29,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 30,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 31,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 32,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 33,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 34,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 35,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 36,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 37,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 38,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 39,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 40,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 41,
                'corridor_id' => rand(2, 6),
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
        ];
        foreach ($corridors as $corridor) {
            $corridor['warehouse_id'] = $warehouse->id;
            Corridor::create($corridor);
        }
        $warehouse->products()->attach($products);

        $warehouse = Warehouse::create([
            'name' => 'جملة',
            'address' => 'حلوان',
            'type' => 1,
        ]);
        $products = [
            [
                'product_id' => 1,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 2,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 3,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 4,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 5,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 6,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 7,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 8,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 9,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 10,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 11,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 12,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 13,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 14,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 15,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 16,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 17,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 18,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 19,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 20,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 21,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 22,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 23,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 24,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 25,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 26,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 27,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 28,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 29,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 30,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 31,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 32,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 33,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 34,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 35,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 36,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 37,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 38,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 39,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 40,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
            [
                'product_id' => 41,
                'corridor_id' => 1,
                'stand' => 'S' . rand(1, 25),
                'shelf' => 'R' . rand(1, 25),
            ],
        ];
        $warehouse->products()->attach($products);

        $corridor = Corridor::create([
            'number' => 5,
            'color' => "##37B35A",
            'is_main_corridor' => 0,
            'warehouse_id' => $warehouse->id,
        ]);

        $warehouse = Warehouse::create([
            'name' => 'مشتريات',
            'address' => 'المرج',
            'type' => 2,
        ]);

        $warehouse = Warehouse::create([
            'name' => 'نواقص',
            'address' => 'المعادي',
            'type' => 3,
        ]);

        $warehouse = Warehouse::create([
            'name' => 'تسويه',
            'address' => 'دار السلام',
            'type' => 4,
        ]);
    }
}

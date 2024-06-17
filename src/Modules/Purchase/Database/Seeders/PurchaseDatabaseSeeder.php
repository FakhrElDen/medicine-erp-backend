<?php

namespace Modules\Purchase\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Entities\User;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\CartPurchase;

class PurchaseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers  = User::role('supplier')->get();

        $purchase = Purchase::create([
            'supplier_id'           => $suppliers->random()->id,
            'warehouse_id'          => 1,
            'manufacturer_id'       => 1,
            'created_by'            => 52,
            'total_price'           => 5000,
            'status'                => 0,
            'purchase_number'       => random_int(100000, 999999),
            'note'                  => "first note",
        ]);

        $cartPurchases = [
            [
                'purchase_id' => $purchase->id,
                'product_id' => 1,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
            [
                'purchase_id' => $purchase->id,
                'product_id' => 2,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
        ];

        foreach ($cartPurchases as $cartPurchase) {
            CartPurchase::create($cartPurchase);
        }

        $purchase = Purchase::create([
            'supplier_id'           => $suppliers->random()->id,
            'warehouse_id'          => 1,
            'client_id'             => 1,
            'pharmacy_id'           => 3,
            'created_by'            => 52,
            'area_id'               => 3,
            'city_id'               => 1,
            'track_id'              => 1,
            'total_price'           => 1000,
            'status'                => 0,
            'note'                  => "second note",
            'purchase_number'       => random_int(100000, 999999),
        ]);

        $cartPurchases = [
            [
                'purchase_id' => $purchase->id,
                'product_id' => 3,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
            [
                'purchase_id' => $purchase->id,
                'product_id' => 4,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
        ];

        foreach ($cartPurchases as $cartPurchase) {
            CartPurchase::create($cartPurchase);
        }

        $purchase = Purchase::create([
            'supplier_id'           => $suppliers->random()->id,
            'warehouse_id'          => 1,
            'manufacturer_id'       => 1,
            'created_by'            => 52,
            'total_price'           => 2000,
            'status'                => 0,
            'note'                  => "third note",
            'purchase_number'       => random_int(100000, 999999),
        ]);

        $cartPurchases = [
            [
                'purchase_id' => $purchase->id,
                'product_id' => 3,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
            [
                'purchase_id' => $purchase->id,
                'product_id' => 4,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
        ];

        foreach ($cartPurchases as $cartPurchase) {
            CartPurchase::create($cartPurchase);
        }

        $purchase = Purchase::create([
            'supplier_id'           => $suppliers->random()->id,
            'warehouse_id'          => 1,
            'client_id'             => 3,
            'pharmacy_id'           => 9,
            'created_by'            => 52,
            'area_id'               => 2,
            'city_id'               => 1,
            'track_id'              => 1,
            'total_price'           => 5000,
            'status'                => 0,
            'note'                  => "fourth note",
            'purchase_number'       => random_int(100000, 999999),
        ]);

        $cartPurchases = [
            [
                'purchase_id' => $purchase->id,
                'product_id' => 3,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
            [
                'purchase_id' => $purchase->id,
                'product_id' => 3,
                'created_by' => 51,
                'quantity' => 10,
                'public_price' => 10,
                'supply_price' => 10,
                'taxes' => 10,
                'discount' => 10,
                'discount_value' => 100,
                'status' => 0,
                'subtotal' => 100,
                'total' => 100,
                'note' => "NOTE",
            ],
        ];

        foreach ($cartPurchases as $cartPurchase) {
            CartPurchase::create($cartPurchase);
        }
    }
}

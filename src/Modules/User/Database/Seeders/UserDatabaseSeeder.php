<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\User\Entities\User;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admins = [
            [
                'name'              => 'Fady',
                'email'             => 'fady@medical.com',
                'phone'             => 0100200300,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Hana',
                'email'             => 'hana@medical.com',
                'phone'             => 0100200301,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Awny',
                'email'             => 'awny@medical.com',
                'phone'             => 0100200302,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Adham',
                'email'             => 'adham@medical.com',
                'phone'             => 0100200304,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Shasha',
                'email'             => 'shasha@medical.com',
                'phone'             => 0100200305,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Fakhr',
                'email'             => 'fakhr@medical.com',
                'phone'             => 0100200306,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Andrew Khalil',
                'email'             => 'andrew_khalil@medical.com',
                'phone'             => 0100200510,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Andrew Ayman',
                'email'             => 'andrew_ayman@medical.com',
                'phone'             => 0100200310,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'Admin',
                'email'             => 'admin@medical.com',
                'phone'             => 0100320310,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
        ];

        foreach ($super_admins as $super_admin) {
            $super_admin = User::create($super_admin);
            $super_admin->assignRole('super_admin');
        }

        $sales_manager = User::create([
            'name'              => 'sales admin',
            'email'             => 'salesadmin@medical.com',
            'phone'             => 011200300,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $sales_manager->assignRole('sales_manager');

        $sales_employees = [
            [
                'name'              => 'testing sales',
                'email'             => 'sales@medical.com',
                'phone'             => 011200301,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'another sales',
                'email'             => 'anothersales@medical.com',
                'phone'             => 011200302,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales zero',
                'email'             => 'saleszero@medical.com',
                'phone'             => 011200303,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 1',
                'email'             => 'salesone@medical.com',
                'phone'             => 011200304,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 2',
                'email'             => 'salestwo@medical.com',
                'phone'             => 011200305,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 3',
                'email'             => 'salesthree@medical.com',
                'phone'             => 011200306,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 4',
                'email'             => 'salesfour@medical.com',
                'phone'             => 011200307,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 5',
                'email'             => 'salesfive@medical.com',
                'phone'             => 011200310,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 6',
                'email'             => 'salessix@medical.com',
                'phone'             => 011200311,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 7',
                'email'             => 'salesseven@medical.com',
                'phone'             => 011200312,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 8',
                'email'             => 'saleseight@medical.com',
                'phone'             => 011200313,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 9',
                'email'             => 'salesnine@medical.com',
                'phone'             => 011200314,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 10',
                'email'             => 'salesten@medical.com',
                'phone'             => 011200315,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 11',
                'email'             => 'saleseleven@medical.com',
                'phone'             => 011200316,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 12',
                'email'             => 'salestwelev@medical.com',
                'phone'             => 011200317,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 13',
                'email'             => 'salesthereteen@medical.com',
                'phone'             => 011200320,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 14',
                'email'             => 'salesfourteen@medical.com',
                'phone'             => 011200321,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 15',
                'email'             => 'salesfifteen@medical.com',
                'phone'             => 011200322,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 16',
                'email'             => 'salessixteen@medical.com',
                'phone'             => 011200323,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 17',
                'email'             => 'salesseventeen@medical.com',
                'phone'             => 011200324,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'sales 18',
                'email'             => 'saleseighteen@medical.com',
                'phone'             => 011200325,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
                'shift'             => 1,
            ],
        ];

        foreach ($sales_employees as $sales_employee) {
            $sales_employee = User::create($sales_employee);
            $sales_employee->assignRole('sales_employee');
        }

        $accountant_manager = User::create([
            'name'              => 'accountant manager',
            'email'             => 'accountant_manager@medical.com',
            'phone'             => 012200300,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
            'shift'             => 1,
        ]);
        $accountant_manager->assignRole('accountant_manager');

        $accountant_employees = [
            [
                'name'              => 'accountant 0',
                'email'             => 'accountant@medical.com',
                'phone'             => 012200301,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
                'shift'             => 1,
            ],
            [
                'name'              => 'accountant 1',
                'email'             => 'accountantone@medical.com',
                'phone'             => 012200302,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
                'shift'             => 1,
            ],
        ];

        foreach ($accountant_employees as $accountant_employee) {
            $accountant_employee = User::create($accountant_employee);
            $accountant_employee->assignRole('accountant_employee');
        }

        $deliveries = [
            [
                'name'              => 'delivery',
                'email'             => 'delivery@medical.com',
                'phone'             => 012200303,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'delivery 1',
                'email'             => 'deliveryone@medical.com',
                'phone'             => 015200300,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'delivery 2',
                'email'             => 'deliverytwo@medical.com',
                'phone'             => 015200301,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'delivery 3',
                'email'             => 'deliverythree@medical.com',
                'phone'             => 015200302,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'delivery 4',
                'email'             => 'deliveryfour@medical.com',
                'phone'             => 015200303,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'delivery 5',
                'email'             => 'deliveryfive@medical.com',
                'phone'             => 015200304,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
        ];

        foreach ($deliveries as $delivery) {
            $delivery = User::create($delivery);
            $delivery->assignRole('delivery');
        }

        $suppliers = [
            [
                'name'              => 'supplier',
                'email'             => 'supplier@medical.com',
                'phone'             => 0100200300401,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'first supplier',
                'email'             => 'first_supplier@medical.com',
                'phone'             => 010020030041,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'second_supplier',
                'email'             => 'second_supplier@medical.com',
                'phone'             => 0100200300417,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
        ];

        foreach ($suppliers as $supplier) {
            $supplier = User::create($supplier);
            $supplier->assignRole('supplier');
        }

        $receiving_reviewer = User::create([
            'name'              => 'receiving reviewer',
            'email'             => 'receiving_reviewer@medical.com',
            'phone'             => 0100200300400,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $receiving_reviewer->assignRole('receiving_reviewer');

        $receiving_distributors = [
            [
                'name'              => 'receiving distributor',
                'email'             => 'receiving_distributor@medical.com',
                'phone'             => 0100200300500,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'receiving distributor zero',
                'email'             => 'receiving_distributor_zero@medical.com',
                'phone'             => 0100200300501,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
            [
                'name'              => 'receiving distributor one',
                'email'             => 'receiving_distributor_one@medical.com',
                'phone'             => 0100200300502,
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => Str::random(10),
            ],
        ];

        foreach ($receiving_distributors as $receiving_distributor) {
            $receiving_distributor = User::create($receiving_distributor);
            $receiving_distributor->assignRole('receiving_distributor');
        }

        $storing_worker = User::create([
            'name'              => 'storing worker',
            'email'             => 'storing_worker@medical.com',
            'phone'             => 0100200300600,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $storing_worker->assignRole('storing_worker');

        $retail_sales_reviewer = User::create([
            'name'              => 'retail sales reviewer',
            'email'             => 'retail_sales_reviewer@medical.com',
            'phone'             => 0100200300700,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $retail_sales_reviewer->assignRole('retail_sales_reviewer');

        $retail_preparation = User::create([
            'name'              => 'retail preparation',
            'email'             => 'retail_preparation@medical.com',
            'phone'             => 017200300,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $retail_preparation->assignRole('retail_preparation');

        $general_preparation = User::create([
            'name'              => 'general preparation',
            'email'             => 'general_preparation@medical.com',
            'phone'             => 0152010300,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $general_preparation->assignRole('general_preparation');

        $purchases_manager = User::create([
            'name'              => 'Purchases Manager',
            'email'             => 'purchases_manager@medical.com',
            'phone'             => 0152010301,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $purchases_manager->assignRole('purchases_manager');

        $purchases_employee = User::create([
            'name'              => 'purchases employee',
            'email'             => 'purchases_employee@medical.com',
            'phone'             => 0152010302,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $purchases_employee->assignRole('purchases_employee');

        $bulk_preparation = User::create([
            'name'              => 'bulk preparation',
            'email'             => 'bulk_preparation@medical.com',
            'phone'             => 0152010401,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $bulk_preparation->assignRole('bulk_preparation');

        $bulk_reviewer = User::create([
            'name'              => 'bulk reviewer',
            'email'             => 'bulk_reviewer@medical.com',
            'phone'             => 0152010400,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $bulk_reviewer->assignRole('bulk_reviewer');

        // storekeeper users
        $balance_and_stores_user = User::create([
            'name'              => 'balance and stores',
            'email'             => 'balance_and_stores@medical.com',
            'phone'             => 0152010412,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $balance_and_stores_user->assignRole('balance_and_stores_role');

        $returns_orders_user = User::create([
            'name'              => 'returns orders',
            'email'             => 'returns_orders@medical.com',
            'phone'             => 0152010422,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $returns_orders_user->assignRole('returns_orders_role');

        $product_movement_user = User::create([
            'name'              => 'product movement',
            'email'             => 'product_movement@medical.com',
            'phone'             => 0152010432,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $product_movement_user->assignRole('product_movement_role');

        $transfers_between_warehouses_user = User::create([
            'name'              => 'transfers between warehouses',
            'email'             => 'transfers_between_warehouses@medical.com',
            'phone'             => 0152010442,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $transfers_between_warehouses_user->assignRole('transfers_between_warehouses_role');

        $audit_settlements_list_user = User::create([
            'name'              => 'audit settlements list role',
            'email'             => 'audit_settlements_list@medical.com',
            'phone'             => 0152010452,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $audit_settlements_list_user->assignRole('audit_settlements_list_role');

        $updated_operations_user = User::create([
            'name'              => 'updated operations',
            'email'             => 'updated_operations@medical.com',
            'phone'             => 0152010462,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $updated_operations_user->assignRole('updated_operations_role');

        $inventory_role_user = User::create([
            'name'              => 'inventory',
            'email'             => 'inventory_role@medical.com',
            'phone'             => 0152010472,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $inventory_role_user->assignRole('inventory_role');

        $storekeeper_settings_user = User::create([
            'name'              => 'storekeeper settings',
            'email'             => 'storekeeper_settings_role@medical.com',
            'phone'             => 0152010402,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $storekeeper_settings_user->assignRole('storekeeper_settings_role');

        $storekeeper = User::create([
            'name'              => 'storekeeper',
            'email'             => 'storekeeper@medical.com',
            'phone'             => 014200300,
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
        ]);
        $storekeeper->assignRole('storekeeper');

    }
}

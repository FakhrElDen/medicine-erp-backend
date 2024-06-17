<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Area\Database\Seeders\AreaDatabaseSeeder;
use Modules\Cart\Database\Seeders\CartSubBatchDatabaseSeeder;
use Modules\Cart\Database\Seeders\CartDatabaseSeeder;
use Modules\Client\Database\Seeders\ClientDatabaseSeeder;
use Modules\Listing\Database\Seeders\ListingDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Product\Database\Seeders\BatchDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Purchase\Database\Seeders\PurchaseDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;
use Modules\Track\Database\Seeders\TrackDatabaseSeeder;
use Modules\Transaction\Database\Seeders\NotificationSeeder;
use Modules\Transaction\Database\Seeders\TransactionDatabaseSeeder;
use Modules\Transaction\Database\Seeders\TransferredBalanceDatabaseSeeder;
use Modules\User\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Warehouse\Database\Seeders\CorridorDatabaseSeeder;
use Modules\Warehouse\Database\Seeders\InventorySeeder;
use Modules\Warehouse\Database\Seeders\TransferDataSeeder;
use Modules\Warehouse\Database\Seeders\WarehouseDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleDatabaseSeeder::class);
        $this->call(UserDatabaseSeeder::class);
        $this->call(AreaDatabaseSeeder::class);
        $this->call(TrackDatabaseSeeder::class);
        $this->call(SettingDatabaseSeeder::class);
        $this->call(ClientDatabaseSeeder::class);
        $this->call(CorridorDatabaseSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
        $this->call(WarehouseDatabaseSeeder::class);
        $this->call(PurchaseDatabaseSeeder::class);
        $this->call(BatchDatabaseSeeder::class);
        $this->call(ListingDatabaseSeeder::class);
        $this->call(TransactionDatabaseSeeder::class);
        $this->call(TransferredBalanceDatabaseSeeder::class);
        $this->call(NotificationSeeder::class);
        // $this->call(OrderDatabaseSeeder::class);
        // $this->call(CartDatabaseSeeder::class);
        // $this->call(CartSubBatchDatabaseSeeder::class);
        // $this->call(TransferDataSeeder::class); // refactor
        // $this->call(InventorySeeder::class); // refactor
    }
}

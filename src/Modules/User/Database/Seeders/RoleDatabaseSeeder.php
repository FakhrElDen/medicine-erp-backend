<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        // *General Permission
        $general_permission = Permission::firstOrCreate(['name' => 'general']);

        // *Clint Permissions
        $listing_clients_permission = Permission::firstOrCreate(['name' => 'listing_clients']);
        $create_client_permission = Permission::firstOrCreate(['name' => 'create_client']);

        // *Lists
        $list_crud_permission = Permission::firstOrCreate(['name' => 'list_crud']);

        // *Cart 
        $cart_crud_permission = Permission::firstOrCreate(['name' => 'cart_crud']);

        // *Sales Permission
        // Sales Admin
        $sales_manager_permission = Permission::firstOrCreate(['name' => 'sales_manager']);
        // Sales Employee
        $sales_employee_permission = Permission::firstOrCreate(['name' => 'sales_employee']);
        // Free Delegate
        $free_delegate_permission = Permission::firstOrCreate(['name' => 'free_delegate']);

        // *Pharmacy Permissions
        $listing_pharmacies_permission = Permission::firstOrCreate(['name' => 'listing_pharmacies']);
        $add_pharmacy_permission = Permission::firstOrCreate(['name' => 'add_pharmacy']);
        $edit_pharmacy_permission = Permission::firstOrCreate(['name' => 'edit_pharmacy']);
        $add_pharmacy_to_client_permission = Permission::firstOrCreate(['name' => 'add_pharmacy_to_client']);
        $pharmacy_statics_permission = Permission::firstOrCreate(['name' => 'pharmacy_statics']);
        $pharmacy_crud_permission = Permission::firstOrCreate(['name' => 'pharmacy_crud']);

        // Pharmacy Fields
        $pharmacy_client_name_permission = Permission::firstOrCreate(['name' => 'client_name']);
        $pharmacy_name_permission = Permission::firstOrCreate(['name' => 'name']);
        $pharmacy_email_permission = Permission::firstOrCreate(['name' => 'email']);
        $pharmacy_address_permission = Permission::firstOrCreate(['name' => 'address']);
        $pharmacy_phone_permission = Permission::firstOrCreate(['name' => 'phone_number']);
        $pharmacy_optional_phone_permission = Permission::firstOrCreate(['name' => 'optional_phone_number']);
        $pharmacy_doctor_permission = Permission::firstOrCreate(['name' => 'doctor']);
        $pharmacy_city_permission = Permission::firstOrCreate(['name' => 'city_id']);
        $pharmacy_area_permission = Permission::firstOrCreate(['name' => 'area_id']);
        $pharmacy_location_url_permission = Permission::firstOrCreate(['name' => 'location_url']);
        $pharmacy_commercial_registration_no_permission = Permission::firstOrCreate(['name' => 'commercial_registration_no']);
        $pharmacy_license_no_permission = Permission::firstOrCreate(['name' => 'license_no']);
        $pharmacy_tax_card_no_permission = Permission::firstOrCreate(['name' => 'tax_card_no']);
        $pharmacy_pharmacy_media_permission = Permission::firstOrCreate(['name' => 'pharmacy_media']);

        $pharmacy_extra_discount_permission = Permission::firstOrCreate(['name' => 'extra_discount']);
        $pharmacy_status_permission = Permission::firstOrCreate(['name' => 'status']);
        $pharmacy_debt_limit_permission = Permission::firstOrCreate(['name' => 'debt_limit']);
        $pharmacy_payment_type_permission = Permission::firstOrCreate(['name' => 'payment_type']);
        $pharmacy_payment_period_permission = Permission::firstOrCreate(['name' => 'payment_period']);
        $pharmacy_track_permission = Permission::firstOrCreate(['name' => 'track_id']);
        $pharmacy_delivery_permission = Permission::firstOrCreate(['name' => 'delivery_id']);
        $pharmacy_discount_slat_permission = Permission::firstOrCreate(['name' => 'discount_slat']);

        $pharmacy_morning_sales_permission = Permission::firstOrCreate(['name' => 'morning_sales']);
        $pharmacy_night_sales_permission = Permission::firstOrCreate(['name' => 'night_sales']);
        $pharmacy_target_permission = Permission::firstOrCreate(['name' => 'target']);
        $pharmacy_minimum_target_permission = Permission::firstOrCreate(['name' => 'minimum_target']);
        $pharmacy_iterate_available_quantity_permission = Permission::firstOrCreate(['name' => 'iterate_available_quantity']);
        $pharmacy_all_permission = Permission::firstOrCreate(['name' => 'all']);
        $pharmacy_day_shift_permission = Permission::firstOrCreate(['name' => 'day_shift']);
        $pharmacy_call_shift_permission = Permission::firstOrCreate(['name' => 'call_shift']);
        $pharmacy_active_permission = Permission::firstOrCreate(['name' => 'active']);
        $pharmacy_client_permission = Permission::firstOrCreate(['name' => 'client_id']);
        $pharmacy_note_permission = Permission::firstOrCreate(['name' => 'note']);

        // $pharmacy_follow_up_permission = Permission::firstOrCreate(['name' => 'follow_up']);
        // $pharmacy_balance_permission = Permission::firstOrCreate(['name' => 'balance']);
        // $pharmacy_type_permission = Permission::firstOrCreate(['name' => 'type']);

        // *User Permission
        $listing_sales_permission = Permission::firstOrCreate(['name' => 'listing_sales']);
        $listing_receivers_auditor_permission = Permission::firstOrCreate(['name' => 'listing_receivers_auditor']);
        $listing_general_preparation_permission = Permission::firstOrCreate(['name' => 'listing_general_preparation']);
        $listing_retail_preparation_permission = Permission::firstOrCreate(['name' => 'listing_retail_preparation']);
        $listing_storing_workers_permission = Permission::firstOrCreate(['name' => 'listing_storing_workers']);
        $listing_retail_sales_reviewer_permission = Permission::firstOrCreate(['name' => 'listing_retail_sales_reviewer']);
        $listing_users_permission = Permission::firstOrCreate(['name' => 'listing_users']);

        // *Purchases Permission
        $purchases_manager_permission = Permission::firstOrCreate(['name' => 'purchases_manager']);
        $purchases_employee_permission = Permission::firstOrCreate(['name' => 'purchases_employee']);

        // *Distribution Permission
        $distribution_manager_permission = Permission::firstOrCreate(['name' => 'distribution_manager']);
        $distribution_employee_permission = Permission::firstOrCreate(['name' => 'distribution_employee']);

        // *Storekeeper Permissions (صلاحيات أمين المخزن)
        $storekeeper_permission = Permission::firstOrCreate(['name' => 'storekeeper']);
        $update_product_permission = Permission::firstOrCreate(['name' => 'update_product_permission']);
        $returns_orders_permission = Permission::firstOrCreate(['name' => 'returns_orders']);
        $product_movement_permission = Permission::firstOrCreate(['name' => 'product_movement_permission']);
        $transfers_between_warehouses_permission = Permission::firstOrCreate(['name' => 'transfers_between_warehouses_permission']);
        $audit_settlements_list_permission = Permission::firstOrCreate(['name' => 'audit_settlements_list_permission']);
        $updated_operations_permission = Permission::firstOrCreate(['name' => 'updated_operations_permission']);
        $inventory_permission = Permission::firstOrCreate(['name' => 'inventory_permission']);
        $storekeeper_settings_permission = Permission::firstOrCreate(['name' => 'storekeeper_settings_permission']);

        // *Delivery Permission
        $delivery_permission = Permission::firstOrCreate(['name' => 'delivery']);
        $listing_delivers_permission = Permission::firstOrCreate(['name' => 'listing_delivers']);

        // *Supplier Permission
        $suppliers_permission = Permission::firstOrCreate(['name' => 'supplier']);
        $listing_suppliers_permission = Permission::firstOrCreate(['name' => 'listing_suppliers']);

        // *Product Permission
        $listing_products_with_offer_permission = Permission::firstOrCreate(['name' => 'listing_products_with_offer']);
        $listing_products_permission = Permission::firstOrCreate(['name' => 'listing_products']);
        $update_products_permission = Permission::firstOrCreate(['name' => 'update_products']);
        $listing_manufacturers_permission = Permission::firstOrCreate(['name' => 'listing_manufacturers']);
        $prohibited_batches_permission = Permission::firstOrCreate(['name' => 'prohibited_batches']);
        $expired_batches_permission = Permission::firstOrCreate(['name' => 'expired_batches']);

        // *Purchases Permission
        $listing_purchases_orders_permission = Permission::firstOrCreate(['name' => 'listing_purchases_orders']);
        $reviewing_purchase_order_permission = Permission::firstOrCreate(['name' => 'reviewing_purchase_order']);
        $listing_purchases_returns_permission = Permission::firstOrCreate(['name' => 'listing_purchases_returns']);
        $purchases_returns_crud_permission = Permission::firstOrCreate(['name' => 'purchases_returns_crud']);
        $listing_received_purchases_returns_permission = Permission::firstOrCreate(['name' => 'listing_received_purchases_returns']);

        // *Complain Permission
        $complain_crud_permission = Permission::firstOrCreate(['name' => 'complain_crud']);

        // *Client Transaction Permission
        $client_transactions_permission = Permission::firstOrCreate(['name' => 'client_transactions']);

        // *Warehouse Permissions
        $listing_warehouses_permission = Permission::firstOrCreate(['name' => 'listing_warehouses']);
        $listing_corridors_permission = Permission::firstOrCreate(['name' => 'listing_corridors']);

        $get_main_warehouse_permission = Permission::firstOrCreate(['name' => 'get_main_warehouse']);
        $baskets_permission = Permission::firstOrCreate(['name' => 'baskets']);
        $packaging_permission = Permission::firstOrCreate(['name' => 'packaging']);

        // عامل توزيع الاستلامات
        $receiving_batches_permission = Permission::firstOrCreate(['name' => 'receiving_batches']);
        //عامل التسكين
        $storing_batches_permission = Permission::firstOrCreate(['name' => 'storing_batches']);
        // محضر الجملة
        $bulk_preparation_permission = Permission::firstOrCreate(['name' => 'bulk_preparation']);
        // محضر البيع القطاعي
        $retail_preparation_permission = Permission::firstOrCreate(['name' => 'retail_preparation']);

        // المحضر العام
        $general_preparation_permission = Permission::firstOrCreate(['name' => 'general_preparation']);

        // مراجع الجملة
        $bulk_reviewer_permission = Permission::firstOrCreate(['name' => 'bulk_reviewer']);
        // مراجع البيع القطاعي
        $retail_reviewer_permission = Permission::firstOrCreate(['name' => 'retail_reviewer']);
        // مراجع الاستلامات
        $receiving_reviewer_permission = Permission::firstOrCreate(['name' => 'receiving_reviewer']);

        // *Accounting
        $accountant_manager_permission = Permission::firstOrCreate(['name' => 'accountant_manager']);
        $accountant_employee_permission = Permission::firstOrCreate(['name' => 'accountant_employee']);

        // firstOrCreate super_admin Role & assign all Permissions to him
        $super_admin_permission = Permission::firstOrCreate(['name' => 'super_admin']);
        $super_admin_role = Role::firstOrCreate(['name' => 'super_admin']);
        $super_admin_role->givePermissionTo([
            $general_permission,
            $list_crud_permission,
            $listing_clients_permission,
            $listing_products_permission,
            $listing_manufacturers_permission,
            $create_client_permission,
            $super_admin_permission,
            $update_product_permission,
            $sales_manager_permission,
            $sales_employee_permission,
            $storing_batches_permission,
            $receiving_batches_permission,
            $bulk_preparation_permission,
            $retail_preparation_permission,
            $bulk_reviewer_permission,
            $retail_reviewer_permission,
            $receiving_reviewer_permission,
            $returns_orders_permission,
            $product_movement_permission,
            $transfers_between_warehouses_permission,
            $audit_settlements_list_permission,
            $updated_operations_permission,
            $inventory_permission,
            $storekeeper_settings_permission,
            $purchases_manager_permission,
            $purchases_employee_permission,
            $distribution_manager_permission,
            $distribution_employee_permission,
            $general_preparation_permission,
            $listing_pharmacies_permission,
            $add_pharmacy_permission,
            $edit_pharmacy_permission,
            $add_pharmacy_to_client_permission,
            $pharmacy_statics_permission,
            $pharmacy_crud_permission,
            $pharmacy_name_permission,
            $pharmacy_email_permission,
            $pharmacy_address_permission,
            $pharmacy_phone_permission,
            $pharmacy_optional_phone_permission,
            $pharmacy_extra_discount_permission,
            $pharmacy_status_permission,
            $pharmacy_debt_limit_permission,
            $pharmacy_payment_type_permission,
            $pharmacy_payment_period_permission,
            $pharmacy_track_permission,
            $pharmacy_city_permission,
            $pharmacy_area_permission,
            $pharmacy_delivery_permission,
            $pharmacy_discount_slat_permission,
            $pharmacy_target_permission,
            $pharmacy_minimum_target_permission,
            $pharmacy_iterate_available_quantity_permission,
            $pharmacy_all_permission,
            $pharmacy_day_shift_permission,
            $pharmacy_call_shift_permission,
            $pharmacy_active_permission,
            $pharmacy_commercial_registration_no_permission,
            $pharmacy_license_no_permission,
            $pharmacy_tax_card_no_permission,
            $pharmacy_doctor_permission,
            $pharmacy_location_url_permission,
            $pharmacy_pharmacy_media_permission,
            $pharmacy_morning_sales_permission,
            $pharmacy_night_sales_permission,
            $pharmacy_active_permission,
            $pharmacy_note_permission,
            $pharmacy_client_permission,
            $pharmacy_client_name_permission,

            $listing_warehouses_permission,
            $get_main_warehouse_permission,
            $baskets_permission,
            $packaging_permission,

            $listing_retail_sales_reviewer_permission,
            $listing_receivers_auditor_permission,
            $listing_delivers_permission,
            $listing_sales_permission,
            $listing_purchases_returns_permission,
            $listing_suppliers_permission,

            $listing_purchases_orders_permission,
            $reviewing_purchase_order_permission,
            $purchases_returns_crud_permission,
            $listing_received_purchases_returns_permission,

            $prohibited_batches_permission,
            $expired_batches_permission,
            $listing_corridors_permission,
            $listing_storing_workers_permission,
            $cart_crud_permission,
        ]);

        // firstOrCreate client Permission and Role & assign General
        $client_role = Role::firstOrCreate(['name' => 'free_delegate']);
        $client_role->givePermissionTo([
            $general_permission,
            $free_delegate_permission,

            $complain_crud_permission,
            $listing_manufacturers_permission,
            $listing_products_permission,
            $update_products_permission,
            $listing_products_with_offer_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,

            $pharmacy_client_permission,
            $pharmacy_morning_sales_permission,
            $pharmacy_night_sales_permission,

            $pharmacy_name_permission,
            $pharmacy_email_permission,
            $pharmacy_address_permission,
            $pharmacy_phone_permission,
            $pharmacy_optional_phone_permission,
            $pharmacy_doctor_permission,
            $pharmacy_city_permission,
            $pharmacy_area_permission,
            $pharmacy_location_url_permission,
            $pharmacy_commercial_registration_no_permission,
            $pharmacy_license_no_permission,
            $pharmacy_tax_card_no_permission,
            $pharmacy_pharmacy_media_permission,
            $pharmacy_note_permission,

            $listing_warehouses_permission,
            $listing_purchases_orders_permission,
            $listing_delivers_permission,
            $listing_sales_permission,
            $cart_crud_permission,
        ]);

        // firstOrCreate sales_employee Role.
        $sales_employee_role = Role::firstOrCreate(['name' => 'sales_employee']);
        $sales_employee_role->givePermissionTo([
            $general_permission,
            $sales_employee_permission,
            $listing_manufacturers_permission,
            $complain_crud_permission,
            $listing_products_with_offer_permission,
            $listing_products_permission,
            $update_products_permission,
            $listing_clients_permission,
            $create_client_permission,
            $returns_orders_permission,
            $listing_pharmacies_permission,
            $add_pharmacy_permission,
            $edit_pharmacy_permission,
            $add_pharmacy_to_client_permission,
            $pharmacy_statics_permission,

            $pharmacy_client_name_permission,
            $pharmacy_client_permission,
            $pharmacy_crud_permission,
            $pharmacy_morning_sales_permission,
            $pharmacy_night_sales_permission,

            $pharmacy_name_permission,
            $pharmacy_email_permission,
            $pharmacy_address_permission,
            $pharmacy_phone_permission,
            $pharmacy_optional_phone_permission,
            $pharmacy_doctor_permission,
            $pharmacy_city_permission,
            $pharmacy_area_permission,
            $pharmacy_location_url_permission,
            $pharmacy_commercial_registration_no_permission,
            $pharmacy_license_no_permission,
            $pharmacy_tax_card_no_permission,
            $pharmacy_pharmacy_media_permission,
            $pharmacy_note_permission,

            $listing_warehouses_permission,
            $get_main_warehouse_permission,
            $listing_purchases_orders_permission,
            $listing_delivers_permission,
            $listing_sales_permission,
            $cart_crud_permission,
        ]);

        // firstOrCreate sales_manager Role.
        $sales_manager_role = Role::firstOrCreate(['name' => 'sales_manager']);
        $sales_manager_role->givePermissionTo([
            $general_permission,
            $sales_manager_permission,
            $sales_employee_permission,
            $listing_purchases_orders_permission,
            $returns_orders_permission,
            $listing_warehouses_permission,
            $listing_suppliers_permission,
            $list_crud_permission,
            $create_client_permission,
            $listing_sales_permission,
            $listing_pharmacies_permission,
            $edit_pharmacy_permission,
            $add_pharmacy_permission,
            $add_pharmacy_to_client_permission,
            $pharmacy_statics_permission,
            $pharmacy_crud_permission,
            $listing_delivers_permission,
            $pharmacy_client_name_permission,
            $pharmacy_morning_sales_permission,
            $pharmacy_night_sales_permission,
            $pharmacy_target_permission,
            $pharmacy_minimum_target_permission,
            $pharmacy_iterate_available_quantity_permission,
            $pharmacy_all_permission,
            $pharmacy_day_shift_permission,
            $pharmacy_call_shift_permission,

            $pharmacy_name_permission,
            $pharmacy_email_permission,
            $pharmacy_address_permission,
            $pharmacy_phone_permission,
            $pharmacy_optional_phone_permission,
            $pharmacy_doctor_permission,
            $pharmacy_city_permission,
            $pharmacy_area_permission,
            $pharmacy_location_url_permission,
            $pharmacy_commercial_registration_no_permission,
            $pharmacy_license_no_permission,
            $pharmacy_tax_card_no_permission,
            $pharmacy_pharmacy_media_permission,
            $pharmacy_note_permission,
            $pharmacy_client_permission,
            $cart_crud_permission,
        ]);

        // firstOrCreate accountant_manager Role
        $accountant_manager_role = Role::firstOrCreate(['name' => 'accountant_manager']);
        $accountant_manager_role->givePermissionTo([
            $general_permission,
            $accountant_manager_permission,
            $accountant_employee_permission,
            $listing_sales_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $edit_pharmacy_permission,

            $pharmacy_extra_discount_permission,
            $pharmacy_status_permission,
            $pharmacy_debt_limit_permission,
            $pharmacy_payment_type_permission,
            $pharmacy_payment_period_permission,
            $pharmacy_track_permission,
            $pharmacy_delivery_permission,
            $pharmacy_discount_slat_permission,

            $pharmacy_name_permission,
            $pharmacy_email_permission,
            $pharmacy_address_permission,
            $pharmacy_phone_permission,
            $pharmacy_optional_phone_permission,
            $pharmacy_doctor_permission,
            $pharmacy_city_permission,
            $pharmacy_area_permission,
            $pharmacy_location_url_permission,
            $pharmacy_commercial_registration_no_permission,
            $pharmacy_license_no_permission,
            $pharmacy_tax_card_no_permission,
            $pharmacy_pharmacy_media_permission,
            $pharmacy_note_permission,
        ]);

        // firstOrCreate accountant_employee Role
        $accountant_role = Role::firstOrCreate(['name' => 'accountant_employee']);
        $accountant_role->givePermissionTo([
            $general_permission,
            $accountant_employee_permission
        ]);

        // delivery
        $delivery_role = Role::firstOrCreate(['name' => 'delivery']);
        $delivery_role->givePermissionTo([
            $general_permission,
            $delivery_permission
        ]);

        // supplier
        $supplier_role = Role::firstOrCreate(['name' => 'supplier']);
        $supplier_role->givePermissionTo([
            $general_permission,
            $suppliers_permission
        ]);

        // warehouse
        $general_preparation_role = Role::firstOrCreate(['name' => 'general_preparation']);
        $general_preparation_role->givePermissionTo([
            $general_permission,
            $general_preparation_permission,
            $receiving_batches_permission,
            $storing_batches_permission,
            $bulk_preparation_permission,
            $retail_preparation_permission,
            $listing_warehouses_permission,
            $listing_retail_preparation_permission,
            $listing_suppliers_permission,
        ]);

        $receiving_reviewer_role = Role::firstOrCreate(['name' => 'receiving_reviewer']);
        $receiving_reviewer_role->givePermissionTo([
            $general_permission,
            $receiving_reviewer_permission,

            // prohibited batches
            $prohibited_batches_permission,

            // expired batches
            $expired_batches_permission,

            // purchases
            $listing_purchases_orders_permission,
            $reviewing_purchase_order_permission,

            // purchase returns
            $listing_purchases_returns_permission,
            $purchases_returns_crud_permission,
            $listing_received_purchases_returns_permission,

            // users
            $listing_suppliers_permission,
            $listing_receivers_auditor_permission,

            // warehouse
            $listing_warehouses_permission,
            $listing_corridors_permission,

            // products
            $listing_products_permission,
            $listing_manufacturers_permission,
        ]);

        $bulk_reviewer_role = Role::firstOrCreate(['name' => 'bulk_reviewer']);
        $bulk_reviewer_role->givePermissionTo([
            $general_permission,
            $bulk_reviewer_permission,
            $update_products_permission,
        ]);

        $retail_sales_reviewer_role = Role::firstOrCreate(['name' => 'retail_sales_reviewer']);
        $retail_sales_reviewer_role->givePermissionTo([
            $general_permission,
            $retail_reviewer_permission,
            $baskets_permission,
            $returns_orders_permission,
            $packaging_permission,
            $listing_retail_sales_reviewer_permission,
        ]);

        $bulk_preparation_role = Role::firstOrCreate(['name' => 'bulk_preparation']);
        $bulk_preparation_role->givePermissionTo([
            $general_permission,
            $bulk_preparation_permission
        ]);

        $retail_preparation_role = Role::firstOrCreate(['name' => 'retail_preparation']);
        $retail_preparation_role->givePermissionTo([
            $general_permission,
            $retail_preparation_permission
        ]);

        $receiving_distributor_role = Role::firstOrCreate(['name' => 'receiving_distributor']);
        $receiving_distributor_role->givePermissionTo([
            $general_permission,
            $receiving_batches_permission,
            $listing_clients_permission,
            $listing_warehouses_permission,
            $listing_receivers_auditor_permission,
            $listing_suppliers_permission,
        ]);

        $storing_worker_role = Role::firstOrCreate(['name' => 'storing_worker']);
        $storing_worker_role->givePermissionTo([
            $general_permission,
            $storing_batches_permission,
            $listing_manufacturers_permission,
            $listing_warehouses_permission,
            $listing_suppliers_permission,
            $listing_receivers_auditor_permission,
        ]);

        # storekeeper roles an permissions & and each permission has role .......
        $storekeeper_role = Role::firstOrCreate(['name' => 'storekeeper']);
        $storekeeper_role->givePermissionTo([
            $general_permission,
            $update_product_permission,
            $returns_orders_permission,
            $product_movement_permission,
            $transfers_between_warehouses_permission,
            $audit_settlements_list_permission,
            $updated_operations_permission,
            $inventory_permission,
            $storekeeper_settings_permission,
            $listing_products_permission,
            $listing_manufacturers_permission,
            $listing_corridors_permission,
            $listing_warehouses_permission,
            $listing_retail_sales_reviewer_permission,
            $listing_receivers_auditor_permission,
            $listing_delivers_permission,
            $listing_sales_permission,
            $listing_purchases_returns_permission,
            $listing_suppliers_permission,
            $listing_purchases_orders_permission,
            $listing_received_purchases_returns_permission,
            $listing_storing_workers_permission,
            $listing_retail_preparation_permission,

        ]);

        $balance_and_stores_role = Role::firstOrCreate(['name' => 'balance_and_stores_role']);
        $balance_and_stores_role->givePermissionTo([
            $listing_products_permission,
            $listing_manufacturers_permission,
            $listing_warehouses_permission,
        ]);

        $returns_orders_role = Role::firstOrCreate(['name' => 'returns_orders_role']);
        $returns_orders_role->givePermissionTo([
            $returns_orders_permission,
            $listing_warehouses_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $listing_users_permission,
            $listing_delivers_permission,
            $listing_suppliers_permission,
            $listing_products_with_offer_permission,
        ]);

        $product_movement_role = Role::firstOrCreate(['name' => 'product_movement_role']);
        $product_movement_role->givePermissionTo([
            $product_movement_permission,
            $listing_warehouses_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $listing_users_permission,
            $listing_suppliers_permission,
            $listing_products_with_offer_permission,
        ]);

        $transfers_between_warehouses_role = Role::firstOrCreate(['name' => 'transfers_between_warehouses_role']);
        $transfers_between_warehouses_role->givePermissionTo([
            $transfers_between_warehouses_permission,
            $listing_manufacturers_permission,
            $listing_warehouses_permission,
            $listing_users_permission,
            $listing_products_with_offer_permission,
        ]);

        $audit_settlements_list_role = Role::firstOrCreate(['name' => 'audit_settlements_list_role']);
        $audit_settlements_list_role->givePermissionTo([
            $audit_settlements_list_permission,
            $listing_warehouses_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $listing_users_permission,
            $listing_products_with_offer_permission,
        ]);

        $updated_operations_role = Role::firstOrCreate(['name' => 'updated_operations_role']);
        $updated_operations_role->givePermissionTo([
            $updated_operations_permission,
            $listing_warehouses_permission,
            $listing_manufacturers_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $listing_users_permission,
            $listing_suppliers_permission,
            $listing_products_with_offer_permission,
            $listing_receivers_auditor_permission,
            $listing_storing_workers_permission,
            $listing_general_preparation_permission,
            $listing_sales_permission,
            $listing_retail_preparation_permission
        ]);

        $inventory_role = Role::firstOrCreate(['name' => 'inventory_role']);
        $inventory_role->givePermissionTo([
            $inventory_permission,
            $listing_warehouses_permission,
            $listing_clients_permission,
            $listing_pharmacies_permission,
            $listing_users_permission,
            $listing_products_with_offer_permission,
        ]);

        $storekeeper_settings_role = Role::firstOrCreate(['name' => 'storekeeper_settings_role']);
        $storekeeper_settings_role->givePermissionTo([
            $storekeeper_settings_permission,
            $listing_products_with_offer_permission,
            $listing_warehouses_permission,
        ]);
        # ........ End Storekeeper roles & permissions

        // distribution
        $distribution_manager_role = Role::firstOrCreate(['name' => 'distribution_manager']);
        $distribution_manager_role->givePermissionTo([$general_permission, $distribution_manager_permission]);

        $distributer_employee_role = Role::firstOrCreate(['name' => 'distributer_employee']);
        $distributer_employee_role->givePermissionTo([$general_permission, $distribution_employee_permission]);

        // purchases
        $purchases_manager_role = Role::firstOrCreate(['name' => 'purchases_manager']);
        $purchases_manager_role->givePermissionTo([$general_permission, $purchases_manager_permission]);

        $purchases_employee_role = Role::firstOrCreate(['name' => 'purchases_employee']);
        $purchases_employee_role->givePermissionTo([$general_permission, $purchases_employee_permission]);

        DB::commit();
    }
}

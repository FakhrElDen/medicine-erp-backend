<?php

namespace Modules\Setting\Repositories;

use App\Repositories\BaseRepository;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Pharmacy;
use Modules\Client\Enums\DayShifts;
use Modules\Client\Enums\DiscountSlatType;
use Modules\Client\Enums\ExtraDiscount;
use Modules\Client\Enums\PaymentPeriod;
use Modules\Client\Enums\PaymentType;
use Modules\Client\Enums\PharmaciesType;
use Modules\Client\Enums\PharmacyActive;
use Modules\Client\Enums\PharmacyShiftType;
use Modules\Client\Enums\PharmacyStatus;
use Modules\Listing\Enums\ListingType;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\ReturnsReasons;
use Modules\Order\Enums\ShippingType;
use Modules\Product\Entities\Manufacturer;
use Modules\Product\Enums\BatchRemainingExpiry;
use Modules\Product\Enums\OfferType;
use Modules\Product\Enums\ProductBuyingStatus;
use Modules\Product\Enums\ProductManufacturingType;
use Modules\Product\Enums\ProductSellingStatus;
use Modules\Product\Enums\ProductType;
use Modules\Product\Enums\QuantityType;
use Modules\Product\Enums\SlatType;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Setting\Entities\Setting;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Transfer;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Enums\WarehouseType;

class SettingRepository extends BaseRepository
{
    public function __construct(protected Setting $model)
    {
    }

    public function all()
    {
        return $this->model->get();
    }

    public function enums()
    {
        $stands = range(1, (int) $this->model->where('key', 'stand_limit')->first()->value);
        $data['stands'] = array_map(function ($stand) {
            return 'S' . $stand;
        }, $stands);
        
        $shelves = range(1, (int) $this->model->where('key', 'self_limit')->first()->value);
        $data['shelves'] = array_map(function ($shelf) {
            return 'R' . $shelf;
        }, $shelves);

        $data['product_manufacturing_types'] = ProductManufacturingType::all();
        $data['product_selling_status'] = ProductSellingStatus::all();
        $data['product_buying_status'] = ProductBuyingStatus::all();
        $data['quantity_more_than_zero'] = QuantityType::all();
        $data['cart_sub_batch_status'] = CartSubBatchStatus::all();
        $data['pharmacy_shifts'] = PharmacyShiftType::all();
        $data['discount_slats'] = DiscountSlatType::all();
        $data['purchase_status'] = PurchaseStatus::all();
        $data['pharmacy_status'] = PharmacyStatus::all();
        $data['returns_reasons'] = ReturnsReasons::all();
        $data['pharmacy_active'] = PharmacyActive::all();
        $data['pharmacy_types'] = PharmaciesType::all();
        $data['payment_periods'] = PaymentPeriod::all();
        $data['warehouse_type'] = WarehouseType::all();
        $data['extra_discount'] = ExtraDiscount::all();
        $data['pharmacy_follow_up'] = DayShifts::all();
        $data['shipping_types'] = ShippingType::all();
        $data['payment_types'] = PaymentType::all();
        $data['listing_type'] = ListingType::all();
        $data['product_type'] = ProductType::all();
        $data['order_status'] = OrderStatus::all();
        $data['offer_type'] = OfferType::all();
        $data['slat_types'] = SlatType::all();
        $data['batch_remaining_expiry'] = BatchRemainingExpiry::all();
        $data['transfer_by'] = Transfer::all()->map(fn ($transfer) => $transfer->user)->unique();

        return $data;
    }

    public function filters($input)
    {
        foreach ($input['filters'] as $filter) {
            $data[$filter['name']] = match ($filter['name']) {
                'clients' => Client::select('id', 'name')->get(),
                'pharmacies' => Pharmacy::select('id', 'name')->get(),
                'warehouses' => Warehouse::select('id', 'name')->get(),
                'manufacturers' => Manufacturer::select('id', 'name')->get(),
                'suppliers' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'supplier'))->get(),
                'deliveries' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'delivery'))->get(),
                'storekeepers' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'storekeeper'))->get(),
                'whole_auditors' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'whole_auditor'))->get(),
                'storing_workers' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'storing_worker'))->get(),
                'whole_preparations' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'whole_preparation'))->get(),
                'receiving_auditors' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'receiving_auditor'))->get(),
                'purchases_managers' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'purchases_manager'))->get(),
                'retail_preparations' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'retail_preparation'))->get(),
                'purchases_employees' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'purchases_employee'))->get(),
                'general_preparations' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'general_preparation'))->get(),
                'distribution_managers' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'distribution_manager'))->get(),
                'distributer_employees' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'distributer_employee'))->get(),
                'retail_sales_auditors' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'retail_sales_auditor'))->get(),
                'receiving_distributors' => User::select('id', 'name')->whereHas('roles', fn ($query) => $query->where('name', 'receiving_distributor'))->get(),

                default => null,
            };
        }

        return $data;
    }
}

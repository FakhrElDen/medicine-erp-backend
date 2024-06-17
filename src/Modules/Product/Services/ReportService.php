<?php

namespace Modules\Product\Services;

use DateTime;
use Modules\Cart\Repositories\CartRepository;
use Modules\Order\Repositories\ReturnableRepository;
use Modules\Product\DTOs\ProductReportsDTO;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\BatchHistoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Purchase\Repositories\CartPurchaseReturnRepository;
use Modules\Warehouse\Enums\WarehouseType;
use Modules\Warehouse\Repositories\CorridorRepository;

class ReportService
{
    public function __construct(
        protected CartRepository $cart_repository,
        protected ReturnableRepository $returnable_repository,
        protected BatchHistoryRepository $batch_history_repository,
        protected ProductRepository $product_repository,
        protected CorridorRepository $corridorRepository,
        protected CartPurchaseReturnRepository $cart_purchase_return_repository
    ) {}

    public function totals(
        int $product_id,
        int $warehouse_id = null,
        string|DateTime $from = null,
        string|DateTime $to = null
    ) {

        $input = new ProductReportsDTO($product_id, $warehouse_id, from: $from, to: $to);

        $sales_total = $this->batch_history_repository->salesTotalAmount($input);

        $purchases = $this->batch_history_repository->purchasesTotalAmount($input);

        $purchase_return_total = $this->cart_purchase_return_repository->totalOrders($input);

        $sales_returned_total = $this->batch_history_repository->salesReturnsTotalAmount($input);

        if ($input->has('warehouse_id')) {
            $inventory_excess = $this->batch_history_repository->correctionsTotalAmount($input, 'excess');
            $inventory_shortage = $this->batch_history_repository->correctionsTotalAmount($input, 'shortage');
            $transfers_incoming = $this->batch_history_repository->transfersTotalIncoming($input);
            $transfers_outgoing = $this->batch_history_repository->transfersTotalOutgoing($input);
        }

        return [
            'sales' => $sales_total,
            'sales_returns' => $sales_returned_total,
            'purchases' => $purchases,
            'purchases_returns' => $purchase_return_total,
            'inventory_excess' => $inventory_excess ?? null,
            'inventory_shortage' => $inventory_shortage ?? null,
            'transfers_incoming' => $transfers_incoming ?? null,
            'transfers_outgoing' => $transfers_outgoing ?? null,
        ];
    }

    public function warehouseTotals(Product $product, int|null $warehouse_id)
    {
        $totals = $warehouse_id
            ? $product->warehouses->where('id', $warehouse_id)->pluck('product_quantity', 'name')
            : $product->warehouses->pluck('product_quantity', 'name');

        return $totals;
    }

    public function getProductLocation(Product $product)
    {
        $warehouse = $product->warehouses->firstWhere('type', WarehouseType::MAIN);

        return [
            'corridor_id' => $warehouse->pivot->corridor_id,
            'number' => $warehouse->corridor_number,
            'stand' => $warehouse->pivot->stand,
            'shelf' => $warehouse->pivot->shelf,
        ];
    }
}

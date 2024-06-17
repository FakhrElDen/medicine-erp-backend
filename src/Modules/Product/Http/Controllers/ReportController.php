<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Product\DTOs\ProductReportsDTO;
use Modules\Product\Http\Requests\Reports;
use Modules\Product\Repositories\BatchHistoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\ReportService;
use Modules\Product\Transformers\BatchHistoryCorrectionsResource;
use Modules\Product\Transformers\BatchHistoryPurchasesResourceCollection;
use Modules\Product\Transformers\BatchHistorySalesResource;
use Modules\Product\Transformers\BatchHistorySalesReturnsResource;
use Modules\Product\Transformers\BatchHistoryTransfersResource;
use Modules\Product\Transformers\ProductResource;
use Modules\Purchase\Repositories\CartPurchaseReturnRepository;
use Modules\Purchase\Transformers\CartPurchaseReturnResourceCollection;

class ReportController extends BaseController
{
    public function __construct(
        protected ReportService $reports_service,
        protected ProductRepository $product_repository,
        protected BatchHistoryRepository $batch_history_repository,
        protected CartPurchaseReturnRepository $cart_purchase_return_repository,
    ) {
        $this->middleware('permission:product_movement_permission');
        if (request()->route()->parameter('all') == 'all') {
            $this->batch_history_repository->pagination = false;
        }
    }

    public function index(Reports\IndexRequest $request)
    {
        $totals = $this->reports_service->totals(...$request->only('product_id', 'warehouse_id', 'from', 'to', 'sort_by', 'direction'));

        $product = $this->product_repository->find($request->product_id);

        $quantity_per_warehouse = $this->reports_service->warehouseTotals($product, $request->warehouse_id)
            ->map(fn ($quantity, $name) => ['name' => $name, 'quantity' => (int) $quantity])
            ->values();

        $product_location = $this->reports_service->getProductLocation($product);

        return $this->apiResponse([
            'totals' => $totals,
            'quantity_per_warehouse' => $quantity_per_warehouse,
            'product' => new ProductResource($product->load('manufacturer')),
            'product_location' => $product_location,
        ]);
    }

    public function sales(Reports\SalesRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'pharmacy_id', 'from', 'to', 'sort_by', 'direction')
        );

        $sales = $this->batch_history_repository->sales($input);

        $total_quantity = $this->batch_history_repository->salesTotalAmount($input);

        $total_orders = $this->batch_history_repository->salesTotalOrders($input);

        return $this->respondResource(
            BatchHistorySalesResource::collection($sales),
            additional_data: ['total_quantity' => $total_quantity, 'total_orders' => $total_orders]
        );
    }

    public function salesReturns(Reports\SalesRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'pharmacy_id', 'from', 'to', 'sort_by', 'direction')
        );

        $sales = $this->batch_history_repository->salesReturns($input);

        $total_quantity = $this->batch_history_repository->salesReturnsTotalAmount($input);

        $total_orders = $this->batch_history_repository->salesReturnsTotalOrders($input);

        return $this->respondResource(
            BatchHistorySalesReturnsResource::collection($sales),
            additional_data: ['total_quantity' => $total_quantity, 'total_orders' => $total_orders]
        );
    }

    public function purchases(Reports\PurchaseRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'supplier_id', 'from', 'to', 'sort_by', 'direction')
        );

        $purchases = $this->batch_history_repository->purchases($input);

        $total_quantity = $this->batch_history_repository->purchasesTotalAmount($input);

        $total_orders = $this->batch_history_repository->purchasesTotalOrders($input);

        $product_prices = $this->batch_history_repository->getProductPrices($request->product_id);

        return $this->respondResource(
            new BatchHistoryPurchasesResourceCollection($purchases, $product_prices),
            additional_data: ['total_quantity' => $total_quantity, 'total_orders' => $total_orders]
        );
    }

    public function purchaseReturns(Reports\PurchaseRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'supplier_id', 'from', 'to', 'sort_by', 'direction')
        );

        $returns = $this->cart_purchase_return_repository->get($input);

        $total_quantity = $this->cart_purchase_return_repository->totalAmount($input);

        $total_orders = $this->cart_purchase_return_repository->totalOrders($input);

        $product_prices = $this->batch_history_repository->getProductPrices($request->product_id);

        return $this->respondResource(
            new CartPurchaseReturnResourceCollection($returns, $product_prices),
            additional_data: ['total_quantity' => $total_quantity, 'total_orders' => $total_orders]
        );
    }

    public function inventory(Reports\InventoryRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'user_id', 'from', 'to', 'sort_by', 'direction')
        );

        $corrections = $this->batch_history_repository->correctionsByProduct($input);

        return $this->respondResource(
            BatchHistoryCorrectionsResource::collection($corrections),
            additional_data: ['total_orders' => $corrections->total()]
        );
    }

    public function transfers(Reports\TransfersRequest $request)
    {
        $input = new ProductReportsDTO(
            ...$request->only('product_id', 'warehouse_id', 'user_id', 'from', 'to', 'sort_by', 'direction')
        );

        $transfers = $this->batch_history_repository->transfers($input);

        $total_amount = $this->batch_history_repository->transfersTotalAmount($input);

        $total_orders = $this->batch_history_repository->transfersTotalOrders($input);

        return $this->respondResource(
            BatchHistoryTransfersResource::collection($transfers),
            additional_data: ['total_quantity' => $total_amount, 'total_orders' => $total_orders]
        );
    }
}

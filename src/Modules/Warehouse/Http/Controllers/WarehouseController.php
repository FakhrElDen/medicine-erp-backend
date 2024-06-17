<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Events\BulkPreparationOrdersCount;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Cart\Repositories\CartSubBatchRepository;
use Modules\Cart\Repositories\CartRepository;
use Modules\Order\Exceptions\OrderException;
use Modules\Order\Repositories\InvoiceRepository;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Transformers\WarehouseOrderResource;
use Modules\Order\Transformers\WarehouseOrderResourceCollection;
use Modules\Order\Transformers\OrderResource;
use Modules\Product\Exceptions\BatchException;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\BatchResourceCollection;
use Modules\Warehouse\app\Http\Requests\PreparedOrderRequest;
use Modules\Warehouse\Enums\WarehouseType;
use Modules\Warehouse\Http\Requests\BatchInventoryRequest;
use Modules\Warehouse\Http\Requests\DuplicateBatchRequest;
use Modules\Warehouse\Http\Requests\FilterBatchesRequest;
use Modules\Warehouse\Http\Requests\InvoicesRequest;
use Modules\Warehouse\Http\Requests\UnpreparedInvoicesRequest;
use Modules\Warehouse\Http\Requests\UpdateRequest;
use Modules\Warehouse\Http\Requests\WarehouseIndexRequest;
use Modules\Warehouse\Repositories\BasketRepository;
use Modules\Warehouse\Repositories\CorridorRepository;
use Modules\Warehouse\Repositories\WarehouseRepository;
use Modules\Warehouse\Transformers\CorridorResourceCollection;
use Modules\Warehouse\Transformers\WarehouseResourceCollection;
use Modules\Warehouse\Http\Requests\CompleteInventoryingRequest;

class WarehouseController extends BaseController
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected BatchRepository $batchRepository,
        protected OrderRepository $orderRepository,
        protected BasketRepository $basketRepository,
        protected InvoiceRepository $invoiceRepository,
        protected ProductRepository $productRepository,
        protected CorridorRepository $corridorRepository,
        protected WarehouseRepository $warehouseRepository,
        protected CartSubBatchRepository $cartSubBatchRepository
    ) {
        $this->middleware('permission:listing_warehouses')->only(['index']);
        $this->middleware('permission:receiving_batches')->only(['receivingBatches', 'completeReceiving', 'receivedBatches']);
        $this->middleware('permission:storing_batches')->only(['storingBatches', 'completeStoring', 'storedBatches']);

        $this->middleware('permission:whole_preparation')->only(['completeWholeOrder', 'printWholeOrder', 'preparedOrder']);

        $this->middleware('permission:whole_preparation|whole_auditor')->only(['listingPreparedOrders']);
        $this->middleware('permission:retail_reviewer|whole_auditor')->only(['duplicate', 'inventorying', 'completeInventorying', 'inventoriedInvoiceContent']);
        $this->middleware('permission:retail_reviewer|whole_auditor|general_preparation')->only(['viewPreparedInvoice']);
        $this->middleware('permission:retail_reviewer')->only(['inventoriedInvoices']);

        $this->middleware('permission:storing_batches|whole_preparation|retail_preparation|receiving_batches|listing_corridors')->only(['corridors']);
        $this->middleware('permission:returns_orders')->only(['duplicate', 'inventoriedInvoices', 'inventoriedInvoiceContent']);
    }

    /**
     * Listing warehouses
     */
    public function index(WarehouseIndexRequest $request)
    {
        if ($request->has('product_id')) {
            $product = $this->productRepository->find($request->product_id);
        }

        $warehouses = $this->warehouseRepository->get($request->validated(), $product ?? null);

        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();
        if ($user->hasPermissionTo('sales_employee') && !$user->hasPermissionTo('sales_manager')) {
            $warehouses = $warehouses->where('type', WarehouseType::MAIN);
        }

        return $this->apiResponse(new WarehouseResourceCollection($warehouses));
    }

    /**
     * Listing orders for warehouse
     * because it has different logic from listing orders
     */
    public function invoices(InvoicesRequest $request)
    {
        $orders = $this->orderRepository->invoices($request->validated());

        return $this->apiResponse(new WarehouseOrderResourceCollection($orders));
    }

    /**
     * View order as invoice content same reason of listing
     * Unused but made to separates view method from listing method  
     */
    public function invoiceContent(InvoicesRequest $request)
    {
        $order = $this->orderRepository->invoices($request->validated())->first();

        return $this->apiResponse(new WarehouseOrderResource($order));
    }

    /**
     * Listing corridors
     */
    public function corridors()
    {
        $corridors = $this->corridorRepository->get();

        return $this->apiResponse(new CorridorResourceCollection($corridors));
    }

    /**
     * Receiving batches
     */
    public function completeReceiving(UpdateRequest $request)
    {
        try {
            $this->batchRepository->completeReceiving($request->validated());

            return $this->apiResponse(message: trans('warehouse::message.received_successfully'));
        } catch (BatchException $exception) {
            return $this->apiErrorResponse($exception->getMessage());
        }
    }

    /**
     * Listing batches will receive it
     */
    public function receivingBatches(FilterBatchesRequest $request)
    {
        $data = $this->batchRepository->receivingBatches($request->validated());

        return $this->apiResponse(
            data: new BatchResourceCollection($data['batches']),
            additional_data: ['counts' => $data['counts']]
        );
    }

    /**
     * Listing received batches
     */
    public function receivedBatches(FilterBatchesRequest $request)
    {
        $data = $this->batchRepository->receivedBatches($request->validated());

        return $this->apiResponse(
            data: new BatchResourceCollection($data['batches']),
            additional_data: ['counts' => $data['counts']]
        );
    }

    /**
     * Storing batches
     */
    public function completeStoring(UpdateRequest $request)
    {
        try {
            $this->batchRepository->completeStoring($request->validated());

            return $this->apiResponse(message: trans('warehouse::message.stored_successfully'));
        } catch (BatchException $exception) {
            return $this->apiErrorResponse($exception->getMessage());
        }
    }

    /**
     * Listing batches will store it.
     */
    public function storingBatches(FilterBatchesRequest $request)
    {
        $data = $this->batchRepository->storingBatches($request->validated());

        return $this->apiResponse(
            data: new BatchResourceCollection($data['batches']),
            additional_data: ['counts' => $data['counts']]
        );
    }

    /**
     * Listing stored batches
     */
    public function storedBatches(FilterBatchesRequest $request)
    {
        $data = $this->batchRepository->storedBatches($request->validated());

        return $this->apiResponse(
            data: new BatchResourceCollection($data['batches']),
            additional_data: ['counts' => $data['counts']]
        );
    }

    /**
     * Inventorying single item on two steps so you will need to duplicate this item
     * مراجع الجملة > مراجع الجملة we  قائمة التحضير < عرض محتويات الطلب
     * مراجع القطاعي >مراجع القطاعي we  قائمة المراجعة > عرض محتويات الطلب
     */
    public function duplicate(DuplicateBatchRequest $request)
    {
        DB::beginTransaction();
        $settlementWarehouse = $this->warehouseRepository->getSettlementWarehouse();
        $cart = $this->cartRepository->find(request()->cart_id);
        $newBatch = $this->batchRepository->duplicate($settlementWarehouse, $request->validated(), $cart);
        $this->cartSubBatchRepository->updateDuplicatedBatch($newBatch, $request->validated());
        DB::commit();

        return $this->apiResponse($newBatch);
    }

    /**
     * Inventorying single item in order
     * مراجع القطاعي > عرض محتويات الطلب > تأكيد
     * مراجع الجملة > عرض محتويات الطلب > تأكيد
     */
    public function inventorying(BatchInventoryRequest $request)
    {
        DB::beginTransaction();
        $this->cartSubBatchRepository->inventorying($request->validated());
        $inventoriedBatches = $this->cartSubBatchRepository->inventoriedBatches($request->validated());
        $nonInventoriedBatches = $this->cartSubBatchRepository->nonInventoriedBatches($request->validated());
        DB::commit();

        return $this->apiResponse([
            'inventoriedBatches' => $inventoriedBatches,
            'nonInventoriedBatches' => $nonInventoriedBatches,
        ]);
    }

    /**
     * Inventorying all order and settlement not inventorying batches
     * مراجع الجملة > عرض محتويات الطلب > طباعة وتسوية
     * مراجع القطاعي >عرض محتويات الطلب > طباعة وتسوية
     */
    public function completeInventorying(CompleteInventoryingRequest $request)
    {
        DB::beginTransaction();

        $order = $this->orderRepository->inventoryingOrder($request->validated());

        if (isset($order->invoice)) {
            $order->invoice->increment('printed_num');
        } else {
            $this->invoiceRepository->store($request->validated(), $request->packaging);
        }

        if ($request->has('non_inventoried_batches_ids')) {
            $settlementWarehouse = $this->warehouseRepository->getSettlementWarehouse();
            $cartSubBatch = $this->cartSubBatchRepository->addBatchesToSettlementWarehouse($settlementWarehouse, $request->validated());

            $this->cartRepository->updateCartAfterSettlement($cartSubBatch);

            $filteredCart = $order->cart->filter(function ($item) {
                return $item->batches()->where('cart_sub_batch.status', CartSubBatchStatus::INVENTORIED)->exists();
            });

            $totals = $this->cartRepository->totals($filteredCart, $order->pharmacy);

            $order = $this->orderRepository->updateOrderAfterSettlement($order, $totals);
        }

        $order->load([
            'client',
            'createdBy',
            'cart' => function ($query) {
                $query->whereHas('batches', function ($query) {
                    $query->where('cart_sub_batch.status', CartSubBatchStatus::INVENTORIED);
                });
            },
            'cart.product',
            'cart.batches.corridor',
            'pharmacy.city',
            'pharmacy.track',
            'invoice.printedBy',
            'delivery',
        ]);

        $filteredCart = $order->cart->filter(function ($item) {
            return $item->batches()->where('cart_sub_batch.status', CartSubBatchStatus::INVENTORIED)->exists();
        });

        $totals = $this->cartRepository->totals($filteredCart, $order->pharmacy);

        DB::commit();

        return $this->apiResponse(new OrderResource($order), null, 200, ['totals' => $totals]);
    }

    /**
     * preparing wholesales order but in retail way (زرار تم التحضير الجوا التحضير ألان)
     * It's a submit button
     *  تحضير الجملة > التحضير
     * التحضير القطاعي > قائمة التحضير > تم التحضير
     */
    public function prepared(PreparedOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->cartSubBatchRepository->updateBatchesInProgress($request->validated());
            $this->cartRepository->completeCart($request->validated());
            $order = $this->orderRepository->find($request->order_id);
            $this->orderRepository->updateWholeOrder($order);
            DB::commit();

            $orders_count = Redis::decrby('bulk_preparation', 1);
            event(new BulkPreparationOrdersCount($orders_count, $order, 'removed'));

            return $this->apiResponse();
        } catch (OrderException $exception) {
            return $this->apiErrorResponse($exception->getMessage());
        }
    }

    /**
     *  فى التحضير فى الجملة و القطاعي
     */
    public function unprepared(UnpreparedInvoicesRequest $request)
    {
        $data = $this->orderRepository->unpreparedInvoices($request->validated());

        return $this->apiResponse(
            data: new WarehouseOrderResourceCollection($data['orders']),
            additional_data: ['counts' => $data['counts']]
        );
    }
}

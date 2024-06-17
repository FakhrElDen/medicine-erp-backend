<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Repositories\CartSubBatchRepository;
use Modules\Cart\Repositories\CartRepository;
use Modules\Order\Exceptions\OrderException;
use Modules\Order\Repositories\InvoiceRepository;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Transformers\WarehouseOrderResource;
use Modules\Order\Transformers\WarehouseOrderResourceCollection;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Warehouse\Http\Requests\Retail\CompleteOrderRequest;
use Modules\Warehouse\Http\Requests\Retail\ViewPreparedRequest;
use Modules\Warehouse\Repositories\BasketRepository;
use Modules\Warehouse\Repositories\CorridorRepository;
use Modules\Warehouse\Repositories\WarehouseRepository;
use Modules\Warehouse\Http\Requests\Retail\ListingReviewedRequest;
use Modules\Warehouse\Http\Requests\Retail\ListingPreparedRequest;

class RetailController extends BaseController
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
        $this->middleware('permission:whole_preparation|retail_preparation|retail_reviewer')->only(['preparedOrders']);
        $this->middleware('permission:retail_reviewer|whole_auditor|general_preparation')->only(['viewPreparedInvoice']);
        $this->middleware('permission:retail_preparation')->only(['completeOrder']);
        $this->middleware('permission:retail_reviewer')->only(['inventoriedInvoices']);
        $this->middleware('permission:returns_orders')->only(['duplicate', 'inventoriedInvoices', 'inventoriedInvoiceContent']);
        $this->middleware('permission:retail_reviewer|whole_auditor')->only(['duplicate', 'inventorying', 'completeInventorying', 'inventoriedInvoiceContent']);
    }

    /**
     * تم التحضير فى محضر البيع القطاعي
     */
    public function listingPrepared(ListingPreparedRequest $request)
    {
        $invoices = $this->orderRepository->listingPreparedRetail($request->validated());

        return $this->apiResponse(new WarehouseOrderResourceCollection($invoices['orders']), additional_data: ['counts' => $invoices['counts']]);
    }

    /**
     * فى المراجعة فى مراجع البيع القطاعي
     */
    public function listingReviewing(ListingPreparedRequest $request)
    {
        $invoices = $this->orderRepository->listingReviewingRetail($request->validated());

        return $this->apiResponse(new WarehouseOrderResourceCollection($invoices));
    }

    /**
     * View prepared order (مراجع البيع القطاعي)
     */
    public function viewPrepared(ViewPreparedRequest $request)
    {
        $invoice = $this->orderRepository->viewPreparedRetail($request->validated());

        // Not throwing error message but handling Business logic message from BackEnd
        return $invoice ?
            $this->apiResponse(new WarehouseOrderResource($invoice)) :
            $this->apiErrorResponse(message: trans('warehouse::message.It_not_prepared_yet'));
    }

    /**
     * make order done for preparing for retail order (زرار تم التحضير فى تحضير قطاعي)
     */
    public function complete(CompleteOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $this->cartSubBatchRepository->updateBatchesInProgress($request->validated());
            $cart = $this->cartRepository->completeCart($request->validated());
            $corridor = $this->corridorRepository->find($request->corridor_id);
            $this->orderRepository->checkOrderTOComplete($request->validated(), $cart, $corridor);

            if ($request->has('basket_ids')) {
                $this->basketRepository->complete($request->validated());
            }

            DB::commit();

            return $this->apiResponse();
        } catch (OrderException $exception) {
            return $this->apiErrorResponse($exception->getMessage());
        }
    }

    /**
     * View unprepared order (تحضير البيع القطاعي)
     */
    public function viewUnprepared(ViewPreparedRequest $request)
    {
        $invoice = $this->orderRepository->viewUnpreparedRetail($request->validated());

        return $this->apiResponse(new WarehouseOrderResource($invoice));
    }

    /**
     * View order in preparing
     */
    public function viewPreparing(ListingPreparedRequest $request)
    {
        if ($request->has('basket_number')) {
            $invoice = $this->orderRepository->viewPreparingRetail($request->only('basket_number'));

            // Not throwing error message but handling Business logic message from BackEnd
            return $invoice ?
                $this->apiResponse(new WarehouseOrderResource($invoice)) :
                $this->apiErrorResponse(message: trans('warehouse::message.basket_not_found'));
        } else {
            $invoice = $this->orderRepository->viewPreparingRetail($request->validated());

            // Not throwing error message but handling Business logic message from BackEnd
            return $invoice ?
                $this->apiResponse(new WarehouseOrderResource($invoice)) :
                $this->apiErrorResponse(message: trans('warehouse::message.invoice_info_wrong'));
        }
    }

    /**
     *  تم المراجعة فى مراجع البيع القطعي
     */
    public function listingReviewed(ListingReviewedRequest $request)
    {
        $orders = $this->orderRepository->listingReviewedRetail($request->validated());

        return $this->apiResponse(new WarehouseOrderResourceCollection($orders));
    }

    /**
     * view content of Reviewed order
     */
    public function viewReviewed(ViewPreparedRequest $request)
    {
        $order = $this->orderRepository->viewReviewedRetail($request->validated());
        $totals = $this->cartRepository->totals($order->cart, $order->pharmacy);

        return $this->apiResponse(new WarehouseOrderResource($order), null, 200, ['totals' => $totals]);
    }
}

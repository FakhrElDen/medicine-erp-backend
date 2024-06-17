<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Events\BulkPreparationOrdersCount;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Repositories\CartSubBatchRepository;
use Modules\Cart\Repositories\CartRepository;
use Modules\Order\Exceptions\OrderException;
use Modules\Order\Repositories\InvoiceRepository;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Transformers\WarehouseOrderResource;
use Modules\Order\Transformers\WarehouseOrderResourceCollection;
use Modules\Order\Transformers\OrderResource;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Warehouse\Http\Requests\Bulk\ViewPreparedRequest;
use Modules\Warehouse\Repositories\BasketRepository;
use Modules\Warehouse\Repositories\CorridorRepository;
use Modules\Warehouse\Repositories\WarehouseRepository;
use Modules\Warehouse\Http\Requests\Bulk\ListingPreparedRequest;
use Modules\Warehouse\Http\Requests\Bulk\ListingReviewedRequest;
use Modules\Warehouse\Http\Requests\Bulk\CompleteOrderRequest;

class BulkController extends BaseController
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
        $this->middleware('permission:whole_preparation|whole_auditor')->only(['listingPreparedOrders']);
        $this->middleware('permission:whole_auditor')->only(['listingReviewedOrders']);
        $this->middleware('permission:whole_preparation')->only(['completeOrder', 'printOrder', 'preparedOrder']);
        $this->middleware('permission:retail_preparation')->only(['completeOrder']);
    }

    /**
     * تم التحضير فى الجملة
     */
    public function listingPrepared(ListingPreparedRequest $request)
    {
        $orders = $this->orderRepository->listingPreparedBulk($request->validated());

        return $this->respondResource(new WarehouseOrderResourceCollection($orders));
    }

    /**
     * فى المراجعة فى الجملة
     */
    public function listingReviewing(ListingPreparedRequest $request)
    {
        $orders = $this->orderRepository->listingReviewingBulk($request->validated());

        return $this->respondResource(new WarehouseOrderResourceCollection($orders));
    }

    /**
     * تم المراجعة فى الجملة
     */
    public function listingReviewed(ListingReviewedRequest $request)
    {
        $orders = $this->orderRepository->listingReviewedBulk($request->validated());

        return $this->respondResource(new WarehouseOrderResourceCollection($orders));
    }

    /**
     * return data of order for printing
     */
    public function print(CompleteOrderRequest $request)
    {
        $order = $this->orderRepository->find($request->order_id)->load([
            'createdBy',
            'invoice.printedBy',
            'client',
            'pharmacy' => [
                'city',
                'track',
            ],
            'cart' => [
                'product',
                'batches.corridor',
            ],
        ]);

        $order->invoice?->increment('printed_num');
        $cart = $order->cart;
        $totals = $this->cartRepository->totals($cart, $order->pharmacy);

        return $this->apiResponse(new OrderResource($order), null, 200, ['totals' => $totals]);
    }

    /**
     * make order done for preparing and printing for bulk order (زرار طباعة وتحضير)
     */
    public function complete(CompleteOrderRequest $request)
    {
        $order = $this->orderRepository->find($request->order_id);
        $cart = $order->cart;

        try {
            DB::beginTransaction();
     
            $cart->map(function ($cart) use ($order, $request) {
                $this->cartRepository->completeWholeCart($cart);
                $corridor = $order->corridors()->first();
                $this->orderRepository->checkOrderTOComplete($request->validated(), $cart, $corridor);

                return $cart->batches->map(fn ($batch) => $this->cartSubBatchRepository->updateBatchRecord($batch->pivot));
            });

            $this->orderRepository->updateWholeOrder($order);
            $this->invoiceRepository->store($request->validated(), []);

            DB::commit();

            $orders_count = Redis::decrby('bulk_preparation', 1);
            event(new BulkPreparationOrdersCount($orders_count, $order, 'removed'));

            $order->load([
                'createdBy',
                'client',
                'invoice.printedBy',
                'cart' => [
                    'product',
                    'batches.corridor',
                ],
                'pharmacy' => [
                    'city',
                    'track',
                ],
            ]);

            $totals = $this->cartRepository->totals($cart, $order->pharmacy);

            return $this->apiResponse(new OrderResource($order), null, 200, ['totals' => $totals]);
        } catch (OrderException $exception) {
            return $this->apiErrorResponse($exception->getMessage());
        }
    }

    public function viewPrepared(ViewPreparedRequest $request)
    {
        $order = $this->orderRepository->viewPreparedBulk($request->validated());

        // Not throwing error message but handling Business logic message from BackEnd
        return $order ?
            $this->apiResponse(new WarehouseOrderResource($order)) :
            $this->apiErrorResponse(message: trans('warehouse::message.invoice_not_found'));
    }
}

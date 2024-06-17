<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Repositories\CartRepository;
use Modules\Client\Repositories\ClientRepository;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Client\Repositories\WaitingListRepository;
use Modules\Order\DTOs\GetOrdersDTO;
use Modules\Order\Http\Requests\CreateOrderRequest;
use Modules\Order\Http\Requests\FollowUpRequest;
use Modules\Order\Http\Requests\invoiceContentRequest;
use Modules\Order\Http\Requests\OrdersRequest;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Transformers\OrderResource;
use Modules\Order\Transformers\OrderResourceCollection;
use Modules\Product\Repositories\BatchRepository;
use Modules\User\Repositories\UserRepository;

class OrderController extends BaseController
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected UserRepository $userRepository,
        protected OrderRepository $orderRepository,
        protected BatchRepository $batchRepository,
        protected ClientRepository $clientRepository,
        protected PharmacyRepository $pharmacyRepository,
        protected WaitingListRepository $waitingListRepository
    ) {
        $this->middleware('permission:sales_employee|free_delegate')->only(['index', 'store', 'invoiceContent']);
        $this->middleware('permission:returns_orders')->only(['inventoried', 'invoiceInventoriedContent']);
    }

    public function index(OrdersRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        $input = new GetOrdersDTO(
            ...$request->only([
                'order_id',
                'client_id',
                'warehouse_id',
                'pharmacy_id',
                'city_id',
                'area_id',
                'track_id',
                'status',
                'order_number',
                'created_at',
                'sales_id',
                'sort_by',
            ])
        );

        if ($user->hasPermissionTo('free_delegate')) {
            $input->client_id = $user->client_id;
        }
         
        $data = $this->orderRepository->get($input);

        return $this->respondResource(new OrderResourceCollection($data));
    }

    public function inventoried(OrdersRequest $request)
    {

        $data = $this->orderRepository->getInventoried($request->validated());

        return $this->respondResource(new OrderResourceCollection($data));
    }

    public function store(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        $this->orderRepository->checkout($request->validated());
        $this->pharmacyRepository->updateBalance($request->validated());
        $this->cartRepository->updateCart($request->validated());
        $pharmacy = $this->pharmacyRepository->find($request->pharmacy_id);
        $pharmacy->waitingList()->exists() ? $pharmacy->waitingList()->delete() : null;
        DB::commit();

        return $this->apiResponse(message: trans('order::message.create_order_message'));
    }

    public function invoiceContent(invoiceContentRequest $request)
    {
        $order = $this->orderRepository->find($request->id)->load('city', 'area', 'shift', 'track', 'sales', 'client', 'createdBy', 'pharmacy', 'warehouse', 'cart.batches', 'cart.product', 'delivery');
        $totals = $this->cartRepository->totals($order->cart, $order->pharmacy);

        return $this->apiResponse(new OrderResource($order), null, 200, ['totals' => $totals]);
    }

    public function invoiceInventoriedContent(invoiceContentRequest $request)
    {
        $order = $this->orderRepository->findInventoried($request->id);
        $totals = $this->cartRepository->totals($order->cart, $order->pharmacy);

        return $this->apiResponse(new OrderResource($order), null, 200, ['totals' => $totals]);
    }

    public function followUp(FollowUpRequest $request)
    {
        $order = $this->orderRepository->followUp($request->validated());

        // Not throwing error message but handling Business logic message from BackEnd
        if ($order) {
            return $this->apiResponse(new OrderResource($order));
        } else {
            return $this->apiErrorResponse(message: trans('order::message.order_not_found'));
        }
    }
}

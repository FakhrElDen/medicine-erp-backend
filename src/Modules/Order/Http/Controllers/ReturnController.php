<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Order\DTOs\ReturnsDTO;
use Modules\Order\Http\Requests\GetAllReturnRequest;
use Modules\Order\Http\Requests\GetReturnRequest;
use Modules\Order\Http\Requests\QuantityReturnRequest;
use Modules\Order\Http\Requests\StoreReturnRequest;
use Modules\Order\Http\Requests\ValidationReturnRequest; // unused 
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Repositories\ReturnableRepository;
use Modules\Order\Repositories\ReturnRepository;
use Modules\Order\Transformers\ReturnablesResourceCollection;
use Modules\Order\Transformers\ReturnResource;
use Modules\Order\Transformers\ReturnResourceCollection;

class ReturnController extends BaseController
{
    public function __construct(
        protected ReturnRepository $returnRepository,
        protected OrderRepository $orderRepository,
        protected PharmacyRepository $pharmacyRepository,
        protected ReturnableRepository $returnableRepository
    ) {
        $this->middleware('permission:returns_orders');
    }

    public function index(GetAllReturnRequest $request)
    {
        $data = $this->returnRepository->get($request->validated());

        return $this->respondResource(new ReturnResourceCollection($data));
    }

    public function returnables(GetAllReturnRequest $request)
    {
        $data = $this->returnableRepository->get($request->validated());

        return $this->respondResource(new ReturnablesResourceCollection($data));
    }

    public function print()
    {
        $data = $this->returnableRepository->print();

        return $this->respondResource(new ReturnablesResourceCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReturnRequest $request)
    {
        DB::beginTransaction();

        if (!is_null($request->order_id)) {
            $order = $this->orderRepository->find($request->order_id);
        } else {
            $order = null;
        }

        $pharmacy = $this->pharmacyRepository->find($request->pharmacy_id);

        $this->returnRepository->store(
            new ReturnsDTO($request->pharmacy_id, $request->warehouse_id, isset($order) ? $order->id : null, $request->products),
            $pharmacy,
            $order ?? null
        );

        DB::commit();

        return $this->apiResponse();
    }

    public function getReturn(GetReturnRequest $request)
    {
        $data = $this->returnRepository->find($request->return_id);

        return $this->respondResource(new ReturnResource($data));
    }

    //? why you didn't use Rule to validate?
    //? why you access model directly not using Repository
    public function validateQuantity(QuantityReturnRequest $request, $validQuantity = null, $quantity = null, $bonus = null)
    {
        $cart_sub_batch = CartSubBatch::with('cart.product.offers')->find($request->cart_sub_batch_id);
        if ($cart_sub_batch->cart->bonus > 0 || $cart_sub_batch->cart->product->has_bonus == true) {
            $bonus = $cart_sub_batch->cart->bonus;
            $quantity = $cart_sub_batch->cart->quantity;
            $validQuantity = $request->quantity % (($quantity + $bonus) / $bonus);
        }

        if ($request->quantity > $cart_sub_batch->quantity || $validQuantity != 0) {
            return $this->apiErrorResponse(trans('product::message.the_product_has_bonus') . $quantity . ' + ' . $bonus);
        }
    }
}

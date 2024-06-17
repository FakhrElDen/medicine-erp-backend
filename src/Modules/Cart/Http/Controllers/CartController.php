<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Cart\Http\Requests\AddToCartRequest;
use Modules\Cart\Http\Requests\ClientProductRequest;
use Modules\Cart\Http\Requests\DestroyAllRequest;
use Modules\Cart\Http\Requests\IndexRequest;
use Modules\Cart\Http\Requests\SalesProductRequest;
use Modules\Cart\Repositories\CartSubBatchRepository;
use Modules\Cart\Repositories\CartRepository;
use Modules\Cart\Transformers\CartResourceCollection;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Order\Repositories\OrderRepository;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SubBatchRepository;
use Modules\Warehouse\Repositories\WarehouseRepository;

class CartController extends BaseController
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected BatchRepository $batchRepository,
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository,
        protected PharmacyRepository $pharmacyRepository,
        protected SubBatchRepository $subBatchRepository,
        protected WarehouseRepository $warehouseRepository,
        protected CartSubBatchRepository $cartSubBatchRepository,
    ) {
        $this->middleware('permission:sales_employee|free_delegate')->only([
            'index',
            'store',
            'sales',
            'clientProduct',
            'deleteAll',
            'destroy'
        ]);
    }

    public function index(IndexRequest $request)
    {
        $carts = $this->cartRepository->getPendingCart($request->validated());

        $pharmacy = $this->pharmacyRepository->find($request->pharmacy_id);
        $totals = $this->cartRepository->totals($carts, $pharmacy);
        $cart = $carts->first();

        if ($request->has('sort_by')) {
            if ($request->direction == 'desc') {
                $carts = $carts->sortByDesc(function ($cart) {
                    return $cart->product->name;
                });
            } else {
                $carts = $carts->sortBy(function ($cart) {
                    return $cart->product->name;
                });
            }

            return $this->apiResponse(
                new CartResourceCollection($carts),
                additional_data: [
                    'cart_number' => $cart->cart_number ?? 0,
                    'order_id' => isset($cart) ? $cart->order?->id : null,
                ]
            );
        }

        if ($request->has('name')) {
            return $this->apiResponse(
                new CartResourceCollection($carts),
                additional_data: [
                    'cart_number' => $cart->cart_number ?? 0,
                    'order_id' => isset($cart) ? $cart->order?->id : null,
                ]
            );
        }

        return $this->apiResponse(
            new CartResourceCollection($carts),
            additional_data: [
                'totals' => $totals,
                'cart_number' => $cart->cart_number ?? 0,
                'order_id' => isset($cart) ? $cart->order?->id : null,
            ]
        );
    }

    /**
     * ? create order in add to cart?
     * so he can redirect to cart by order in the listing of orders and edit the cart as designed made
     */
    public function store(AddToCartRequest $request)
    {
        DB::beginTransaction();

        $pharmacy = $this->pharmacyRepository->find($request->pharmacy_id);

        $product = $this->productRepository->find($request->product_id);

        $pharmacy->waitingList()->exists() ? $pharmacy->waitingList()->delete() : null;

        $invoice_number = $this->cartRepository->generateCartNumber($pharmacy->id);

        $order = $this->orderRepository->store($request->validated(), $pharmacy, $invoice_number);

        $cartItem = $this->cartRepository->store($request->validated(), $product, $order->id, $invoice_number, $pharmacy);

        if ($cartItem->product->offers()->exists()) {
            $this->subBatchRepository->getOrderedQuantityFromBatchesForItemHasOffer($request->validated(), $cartItem);
        } else {
            $this->subBatchRepository->getOrderedQuantityFromBatches($request->validated(), $cartItem);
        }

        $cart = $this->cartRepository->getPendingCart($request->validated());
        $totals = $this->cartRepository->totals($cart, $pharmacy);

        DB::commit();

        return $this->apiResponse(
            new CartResourceCollection($cart),
            additional_data: [
                'totals' => $totals,
                'cart_number' => $cart->first()->cart_number,
                'order_id' => $order->id,
            ]
        );
    }

    public function destroy($cart_sub_batch_id)
    {
        DB::beginTransaction();

        $validatedData = Validator::make(['cart_sub_batch_id' => $cart_sub_batch_id], [
            'cart_sub_batch_id' => 'required|integer|exists:cart_sub_batch,id',
        ])->validate();

        $cartSubBatch = $this->cartSubBatchRepository->delete($validatedData['cart_sub_batch_id']);
        $pharmacy = $this->pharmacyRepository->find($cartSubBatch->cart->pharmacy_id);
        $cart = $this->cartRepository->getCartByOrderId($cartSubBatch->cart->order_id);

        $this->cartRepository->decrement([
            'cart_id' => $cartSubBatch->cart_id,
            'quantity' => $cartSubBatch->quantity,
        ]);

        $this->batchRepository->incrementBatchQuantity([
            'batch_id' => $cartSubBatch->batch_id,
            'quantity' => $cartSubBatch->quantity,
        ]);

        $totals = $this->cartRepository->totals($cart, $pharmacy);

        DB::commit();

        return $this->apiResponse($totals, trans('cart::message.delete_cart_item'));
    }

    public function deleteAll(DestroyAllRequest $request)
    {
        DB::beginTransaction();

        foreach ($request->cart_sub_batch_ids as $cart_sub_batch_id) {
            $this->destroy($cart_sub_batch_id);
        }

        DB::commit();

        return $this->apiResponse(message: trans('cart::message.delete_cart'));
    }

    public function sales(SalesProductRequest $request)
    {
        $sales_product = $this->cartRepository->sales($request->validated());

        return $this->apiResponse(new CartResourceCollection($sales_product));
    }

    public function clientProduct(ClientProductRequest $request)
    {
        $product_user = $this->cartRepository->clientProduct($request->validated());

        return $this->apiResponse(new CartResourceCollection($product_user));
    }
}

<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Purchase\DTOs\PurchaseReturnDTO;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Transformers\CartPurchaseResource;
use Modules\Purchase\Repositories\CartPurchaseRepository;
use Modules\Purchase\Transformers\PurchaseReturnResource;
use Modules\Purchase\Repositories\PurchaseReturnRepository;
use Modules\Purchase\Http\Requests\CancelPurchaseReturnRequest;
use Modules\Purchase\Http\Requests\CreatePurchaseReturnRequest;
use Modules\Purchase\Http\Requests\UpdatePurchaseReturnRequest;
use Modules\Purchase\Repositories\CartPurchaseReturnRepository;
use Modules\Purchase\Http\Requests\ListingPurchasesReturnsRequest;
use Modules\Purchase\Http\Requests\ReceivingPurchaseReturnRequest;
use Modules\Purchase\Transformers\PurchaseReturnResourceCollection;

class PurchaseReturnController extends BaseController
{
    public function __construct(
        protected PurchaseRepository $purchaseRepository,
        protected CartPurchaseRepository $cartPurchaseRepository,
        protected PurchaseReturnRepository $purchaseReturnRepository,
        protected CartPurchaseReturnRepository $cartPurchaseReturnRepository
    ) {
        $this->middleware('permission:listing_purchases_returns')->only(['index', 'paginated', 'view']);
        $this->middleware('permission:purchases_returns_crud')->only(['store', 'cancel', 'update']);
        $this->middleware('permission:listing_received_purchases_returns')->only(['receiving', 'receivingPaginated']);
    }

    public function index(ListingPurchasesReturnsRequest $request)
    {
        $returns = $this->purchaseReturnRepository->all($request->validated());

        return $this->apiResponse(new PurchaseReturnResourceCollection($returns));
    }

    public function paginated(ListingPurchasesReturnsRequest $request)
    {
        $returns = $this->purchaseReturnRepository->allPaginated($request->validated());

        $total_returned_items = $returns->sum(function ($return) {
            return $return->returnedItems->count();
        });

        return $this->respondResource(new PurchaseReturnResourceCollection($returns), additional_data: ['total_returned_items' => $total_returned_items]);
    }

    public function view(ListingPurchasesReturnsRequest $request)
    {
        $return = $this->purchaseReturnRepository->all($request->validated())->first()?->load('purchase.cart.product', 'purchase.supplier', 'purchase.cart.return');

        return $this->apiResponse(new PurchaseReturnResource($return));
    }

    /**
     * you need to check on cart item quantity to change status of this item if non inventoried or semi inventoried
     */
    public function store(CreatePurchaseReturnRequest $request)
    {
        $cartPurchaseItems = $this->cartPurchaseRepository->findBulk($request->cart_purchase_ids);

        foreach ($cartPurchaseItems as $cartPurchaseItem) {
            $purchaseReturn = $this->purchaseReturnRepository->all($request->only('purchase_id'))->first();
            $purchaseReturnDTO = new PurchaseReturnDTO(
                $request->reason,
                $cartPurchaseItem->quantity - $cartPurchaseItem->inventoried_quantity,
                $cartPurchaseItem->public_price,
                $cartPurchaseItem->id,
            );

            $this->purchaseReturnRepository->assignReturnToCartPurchase($purchaseReturnDTO, $purchaseReturn, $request->validated());
        }

        return $this->apiResponse(new CartPurchaseResource($cartPurchaseItem->load('return')));
    }

    public function cancel(CancelPurchaseReturnRequest $request)
    {
        $purchaseReturn = $this->purchaseReturnRepository->all(['id' => $request->purchases_return_id])->first();

        if (isset($purchaseReturn->returnedItems) && $purchaseReturn->returnedItems->count() == 1) {
            $cartPurchaseReturnId = $purchaseReturn->returnedItems()->where('cart_purchase_id', $request->cart_purchase_id)->first()->id;
            $this->cartPurchaseReturnRepository->deleteByCartPurchaseID($cartPurchaseReturnId);
            $purchaseReturn->delete();
        } else {
            $cartPurchaseReturnId = $purchaseReturn->returnedItems()->where('cart_purchase_id', $request->cart_purchase_id)->first()->id;
            $this->cartPurchaseReturnRepository->deleteByCartPurchaseID($cartPurchaseReturnId);
        }

        return $this->apiResponse();
    }

    /**
     * refactor this don't use except()
     */
    public function update(UpdatePurchaseReturnRequest $request)
    {
        $this->purchaseReturnRepository->update($request->return_id, $request->except('return_id'));

        return $this->apiResponse();
    }

    public function receiving(ReceivingPurchaseReturnRequest $request)
    {
        $returns = $this->purchaseReturnRepository->receiving($request->validated());

        return $this->apiResponse(new PurchaseReturnResourceCollection($returns));
    }

    public function receivingPaginated(ReceivingPurchaseReturnRequest $request)
    {
        $returns = $this->purchaseReturnRepository->receivingPaginated($request->validated());

        $total_returned_items = $returns->sum(function ($return) {
            return $return->returnedItems->count();
        });

        return $this->respondResource(new PurchaseReturnResourceCollection($returns), additional_data: ['total_returned_items' => $total_returned_items]);
    }
}

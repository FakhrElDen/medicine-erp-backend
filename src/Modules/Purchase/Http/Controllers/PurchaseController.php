<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Support\Carbon;
use Modules\Purchase\Entities\Purchase;
use App\Http\Controllers\BaseController;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Http\Requests\PurchaseRequest;
use Modules\Purchase\Transformers\PurchaseResource;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Repositories\CartPurchaseRepository;
use Modules\Purchase\Repositories\PurchaseReturnRepository;
use Modules\Purchase\Http\Requests\ReceivingReviewerRequest;
use Modules\Purchase\Http\Requests\ReviewingPurchaseRequest;
// you must use this validation in show method but handle it first
use Modules\Purchase\Http\Requests\ShowPurchaseRequest;
use Modules\Purchase\Transformers\PurchaseResourceCollection;

class PurchaseController extends BaseController
{
    public function __construct(
        protected PurchaseRepository $purchaseRepository,
        protected PurchaseReturnRepository $purchaseReturnRepository,
        protected CartPurchaseRepository $cartPurchaseRepository
    ) {
        $this->middleware('permission:listing_purchases_orders')->only(['index', 'show', 'print']);
        $this->middleware('permission:reviewing_purchase_order')->only(['receivingReviewer', 'reviewing']);
    }

    public function index(PurchaseRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $purchases = $this->purchaseRepository->index(['client_id' => $user->client_id] + $request->validated());
        } else {
            $purchases = $this->purchaseRepository->index($request->validated());
        }

        return $this->respondResource(new PurchaseResourceCollection($purchases));
    }

    public function receivingReviewer(ReceivingReviewerRequest $request)
    {
        $purchases = $this->purchaseRepository->receivingReviewer($request->validated());
        return $this->respondResource(new PurchaseResourceCollection($purchases));
    }

    public function print()
    {
        $purchases = $this->purchaseRepository->all([])->where('status', PurchaseStatus::Unreviewed)->load('supplier', 'warehouse', 'createdBy');
        return $this->respondResource(new PurchaseResourceCollection($purchases));
    }

    public function show(Purchase $purchase)
    {
        return $this->apiResponse(new PurchaseResource($purchase->load('cart.product', 'supplier', 'batches.product', 'batches.cartPurchaseItem', 'warehouse', 'cart.return')));
    }

    /**
     * *Important: update total in model when quantity change
     * لازالة المرتجع من قيمة الفتورة
     */
    public function reviewing(ReviewingPurchaseRequest $request)
    {
        $this->purchaseRepository->update($request->purchase_id, [
            'status'        => PurchaseStatus::Reviewed,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => Carbon::now(),
        ]);

        return $this->apiResponse();
    }
}

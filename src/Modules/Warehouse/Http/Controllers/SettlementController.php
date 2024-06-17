<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Collection;
use Modules\Warehouse\DTOs\SettlementIndexDTO;
use Modules\Warehouse\Http\Requests\SettlementRequest;
use Modules\Warehouse\Http\Requests\SettlementUpdateRequest;
use Modules\Warehouse\Repositories\SettlementRepository;
use Modules\Warehouse\Transformers\SettlementResource;

class SettlementController extends BaseController
{
    public function __construct(
        protected SettlementRepository $settlement_repository
    ) {
        $this->middleware('permission:audit_settlements_list_permission');
    }

    public function index(SettlementRequest $request)
    {
        $input = new SettlementIndexDTO(
            ...$request->only('product_id', 'warehouse_id', 'reviewed_by', 'pharmacy_id', 'from', 'to')
        );

        /** @var Collection $batches */
        $batches = $this->settlement_repository->index($input);

        $batches->load([
            'warehouse',
            'cartSubBatch' => fn ($q) => $q->with([
                'batch',
                'cart' => fn ($q) => $q->with(['order.pharmacy', 'order.reviewedBy', 'product', 'warehouse']),
            ]),
        ]);

        return $this->respondResource(SettlementResource::collection($batches), additional_data: [
            'total_batches' => $batches->total(),
            'total_orders' => $this->settlement_repository->ordersCount($input, false),
        ]);
    }

    public function finished(SettlementRequest $request)
    {
        $input = new SettlementIndexDTO(
            ...$request->only('product_id', 'warehouse_id', 'reviewer_id', 'pharmacy_id', 'from', 'to')
        );

        /** @var Collection $batches */
        $batches = $this->settlement_repository->finished($input);

        $batches->load([
            'warehouse',
            'cartSubBatch' => fn ($q) => $q->with([
                'batch',
                'cart' => fn ($q) => $q->with(['order.pharmacy', 'order.reviewedBy', 'product', 'warehouse']),
            ]),
        ]);

        return $this->respondResource(SettlementResource::collection($batches), additional_data: [
            'total_batches' => $batches->total(),
            'total_orders' => $this->settlement_repository->ordersCount($input, true),
        ]);
    }

    public function update(SettlementUpdateRequest $request)
    {
        $this->settlement_repository->update($request->id, $request->quantity);

        return $this->apiResponse();
    }
}

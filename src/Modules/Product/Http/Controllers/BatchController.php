<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Product\Http\Requests\AlmostExpiredBatchRequest;
use Modules\Product\Http\Requests\BatchRequest;
use Modules\Product\Http\Requests\ListingProhibitedBatchesRequest;
use Modules\Product\Http\Requests\StoreProhibitedBatchRequest;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProhibitedBatchRepository;
use Illuminate\Support\Facades\DB;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Product\Http\Requests\GetUpdatedBatchesRequest;
use Modules\Product\Http\Requests\UpdateBatchRequest;
use Modules\Product\Repositories\BatchHistoryRepository;
use Modules\Product\Transformers\BatchHistoryResourceCollection;
use Modules\Product\Transformers\BatchResource;
use Modules\Product\Transformers\BatchResourceCollection;
use Modules\Product\Transformers\ProhibitedBatchResource;
use Modules\Product\Transformers\ProhibitedBatchResourceCollection;

class BatchController extends BaseController
{
    public function __construct(
        protected BatchRepository $batchRepository,
        protected ProhibitedBatchRepository $prohibitedBatchRepository,
        protected BatchHistoryRepository $batchHistoryRepository,
    ) {
        $this->middleware('permission:listing_products_with_offer|sales_employee|free_delegate')->only(['index']);
        $this->middleware('permission:prohibited_batches')->only(['prohibitedBatches', 'storeProhibitedBatch']);
        $this->middleware('permission:updated_operations_permission|listing_products')->except(['index']);
        $this->middleware('permission:expired_batches')->only(['almostExpired']);
        if (request()->route()->parameter('all') == 'all') {
            $this->batchRepository->pagination = false;
            $this->batchHistoryRepository->pagination = false;
        }
    }

    public function index(BatchRequest $request)
    {
        $batches = $this->batchRepository->get($request->validated());

        return $this->apiResponse(new BatchResourceCollection($batches));
    }

    public function indexPaginate(BatchRequest $request)
    {
        $batches = $this->batchRepository->getAll($request->validated());

        return $this->respondResource(resource: new BatchResourceCollection($batches));
    }

    public function updateBatchOperatingNumber(UpdateBatchRequest $request)
    {
        DB::beginTransaction();
        $oldBatch = $this->batchRepository->find($request->batch_id);
        $new_batch = $this->batchRepository->updateBatchOperatingNumber($request->validated(), $oldBatch);
        DB::commit();

        return $this->apiResponse(new BatchResource($new_batch));
    }

    public function getBatchesOperatingNumberUpdated(GetUpdatedBatchesRequest $request)
    {
        $batches_paginated = $this->batchHistoryRepository->getBatchesHistory(input: $request->validated(), type: BatchHistoryType::EDIT);

        $batches = $this->batchHistoryRepository->getBatchesHistory(input: [$request->validated()], type: BatchHistoryType::EDIT, not_paginated: true);

        $total_quantity = $batches->sum(function ($batch) {
            return $batch->batch->quantity;
        });

        return $this->respondResource(resource: new BatchHistoryResourceCollection($batches_paginated), metaData: ['total_quantity' => $total_quantity]);
    }

    public function almostExpired(AlmostExpiredBatchRequest $request)
    {
        $batches = $this->batchRepository->almostExpired(['language' => $request->header('Accept-Language')] + $request->validated());

        return $this->respondResource(new BatchResourceCollection($batches));
    }

    public function prohibitedBatches(ListingProhibitedBatchesRequest $request)
    {
        $prohibitedBatches = $this->prohibitedBatchRepository->allPaginated($request->validated());

        return $this->respondResource(new ProhibitedBatchResourceCollection($prohibitedBatches));
    }

    public function storeProhibitedBatch(StoreProhibitedBatchRequest $request)
    {
        $prohibitedBatch = $this->prohibitedBatchRepository->store($request->validated());

        return $this->apiResponse(new ProhibitedBatchResource($prohibitedBatch));
    }
}

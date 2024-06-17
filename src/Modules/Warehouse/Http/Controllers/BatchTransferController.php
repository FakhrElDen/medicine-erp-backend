<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Services\ReportService;
use Modules\Product\Transformers\BatchTransferResourceCollection;
use Modules\Warehouse\Http\Requests\ConfirmTransferRequest;
use Modules\Warehouse\Http\Requests\GetTransferBatchRequest;
use Modules\Warehouse\Repositories\BatchTransferRepository;
use Modules\Warehouse\Repositories\TransferRepository;

class BatchTransferController extends BaseController
{
    public function __construct(
        protected BatchTransferRepository $batchTransferRepository,
        protected TransferRepository $transferRepository,
        protected ReportService $reports_service,
    ) {
        $this->middleware('permission:storekeeper|transfers_between_warehouses_permission');
        if (request()->route()->parameter('all') == 'all') {
            $this->batchTransferRepository->pagination = false;
        }
    }

    public function confirmedProductsTransferred(GetTransferBatchRequest $request)
    {
        $batch_transfers = $this->batchTransferRepository->confirmedProductsTransferred($request->validated());
        $total_batches = $this->batchTransferRepository->getConfirmedBatchesTransferred($request->validated());

        return $this->respondResource(new BatchTransferResourceCollection($batch_transfers), ['total_batches' => $total_batches]);
    }

    public function unconfirmedTransfers(GetTransferBatchRequest $request)
    {
        $count = $this->batchTransferRepository->getUnconfirmedBatchesCount();
        $batch_transfers = $this->batchTransferRepository->getUnconfirmedBatches($request->validated());
        Redis::set('transfers', $count);

        $batch_transfers->transform(function ($batch_transfer) {
            $product = $batch_transfer->batch->product;
            $product_location = $this->reports_service->getProductLocation($product);
            $batch_transfer->main_location = $product_location;

            return $batch_transfer;
        });

        return $this->respondResource(new BatchTransferResourceCollection($batch_transfers));
    }

    public function confirmTransfers(ConfirmTransferRequest $request)
    {
        $this->batchTransferRepository->confirmTransfers($request->validated());

        return $this->apiResponse();
    }
}

<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Warehouse\Http\Requests\GetTransferBatchRequest;
use Modules\Warehouse\Repositories\TransferRepository;
use Modules\Warehouse\Transformers\TransferResourceCollection;

class TransferController extends BaseController
{
    public function __construct(
        protected TransferRepository $transferRepository,
    ) {
        $this->middleware('permission:storekeeper|transfers_between_warehouses_permission');
        if (request()->route()->parameter('all') == 'all') {
            $this->transferRepository->pagination = false;
        }
    }

    public function confirmedOrdersTransferred(GetTransferBatchRequest $request)
    {
        $transfers = $this->transferRepository->confirmedOrdersTransferred($request->validated());

        return $this->respondResource(new TransferResourceCollection($transfers));
    }
}

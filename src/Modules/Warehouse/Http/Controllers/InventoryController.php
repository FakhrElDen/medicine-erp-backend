<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Events\InventoryCount;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Repositories\BatchHistoryRepository;
use Modules\Warehouse\DTOs\InventoryIndexDTO;
use Modules\Warehouse\Http\Requests\InventoryIndexRequest;
use Modules\Warehouse\Transformers\CorrectionResource;

class InventoryController extends BaseController
{
    public function __construct(
        protected BatchHistoryRepository $batch_history_repository
    ) {
        if (request()->route()->parameter('all') == 'all') {
            $this->batch_history_repository->pagination = false;
        }
    }

    public function index(InventoryIndexRequest $request)
    {
        $input = new InventoryIndexDTO(
            ...$request->only('product_id', 'warehouse_id', 'from', 'to', 'sort_by', 'direction')
        );

        /** @var Collection $corrections */
        $corrections = $this->batch_history_repository->corrections($input);

        $corrections->load('batch.product.warehouses', 'batch.warehouse', 'user');

        Redis::set('inventory', 0);
        broadcast(new InventoryCount(0, 'removed'));

        return $this->respondResource(CorrectionResource::collection($corrections));
    }
}

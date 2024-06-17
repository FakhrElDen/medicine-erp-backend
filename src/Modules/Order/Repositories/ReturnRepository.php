<?php

namespace Modules\Order\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Order\DTOs\ReturnsDTO;
use Modules\Order\Entities\Returns;
use Modules\Product\Repositories\BatchRepository;

class ReturnRepository extends BaseRepository
{
    public function __construct(
        protected Returns $model,
        protected BatchRepository $batchRepository
    ) {
    }

    public function get($input)
    {
        return $this->model->query()->applyFilters($input)
            ->when(isset($input['sort_by']), function ($query) use ($input) {
                $direction = isset($input['direction']) ? $input['direction'] : 'asc';
                switch ($input['sort_by']) {
                    case 'corridor_id':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->orderBy('batches.corridor_id', $direction);
                        break;
                    case 'product_name_en':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->en', $direction);
                        break;
                    case 'product_name_ar':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->ar', $direction);
                        break;
                    case 'manufacturer_en':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->en', $direction);
                        break;
                    case 'manufacturer_ar':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->ar', $direction);
                        break;
                    case 'warehouse_id':
                        $query->join('returnables', 'returnables.returns_id', '=', 'returns.id')
                            ->join('cart_sub_batch', 'cart_sub_batch.id', '=', 'returnables.returnable_id')
                            ->join('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
                            ->orderBy('batches.warehouse_id', $direction);
                        break;
                }
            })
            ->select('returns.*')
            ->with(['user',
                'pharmacy',
                'order.warehouse',
                'order.delivery',
                'products',
                'warehouse',
                'cartSubBatches.batch.product.manufacturer',
                'cartSubBatches.batch.corridor',
                'returnables.returnable' => fn ($q) => $q->morphWith([CartSubBatch::class => 'cart.product']),
            ])
            ->paginate(10);
    }

    public function find($id)
    {
        return $this->model->with([
            'user',
            'pharmacy',
            'order' ,
            'warehouse',
            'returnables.returnable' => fn ($q) => $q->morphWith([CartSubBatch::class => 'cart.product']),
        ])->find($id);
    }

    /**
     * Store returns products.
     */
    public function store(ReturnsDTO $data, $pharmacy, $order = null)
    {
        DB::beginTransaction();

        $return = $this->model->create([
            'warehouse_id' => $data->warehouse_id,
            'order_id' => $order->id ?? null,
            'pharmacy_id' => $pharmacy->id,
            'created_by' => Auth::id(),
        ]);

        $returnables = $return->returnables()->createMany($data->returnables->toArray());

        $returnables->load([
            'parentBatch',
            'returnable' => fn ($q) => $q->morphWith([CartSubBatch::class => 'cart.product']),
        ]);

        $returnables->where('returnable_type', CartSubBatch::class)->map(function ($returnable) {
            $cart_sub_batch = CartSubBatch::find($returnable->returnable_id);
            $cart_sub_batch->increment('returned_quantity', $returnable->quantity);
        });

        $returnables->each(function ($returnable) use ($data) {
            $this->batchRepository->createFromReturnable($returnable, $returnable->parentBatch, $data->warehouse_id);
        });

        DB::commit();

        return $return;
    }
}

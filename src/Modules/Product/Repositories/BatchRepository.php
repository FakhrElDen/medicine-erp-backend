<?php

namespace Modules\Product\Repositories;

use App\Events\HousingBatchesCount;
use App\Events\ReceiptBatchesCount;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Entities\Cart;
use Modules\Order\Entities\Returnables;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\SubBatch;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Product\Enums\BatchRemainingExpiry;
use Modules\Product\Exceptions\BatchException;
use Modules\Setting\Entities\Setting;
use Modules\Warehouse\Entities\Corridor;

class BatchRepository extends BaseRepository
{
    public bool $pagination = true;

    public function __construct(protected Batch $model)
    {
        //
    }

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate(10) : $query->get();
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->get();
    }

    public function get($input)
    {
        $startDate = isset($input['created_at']) ? Carbon::createFromFormat('Y-m-d', $input['created_at'])->startOfDay() : Carbon::now()->subDay();
        $endDate = Carbon::now();

        return $this->model->whereBetween('created_at', [$startDate, $endDate])->with('warehouse', 'product')
            ->when(isset($input['batch_id']), function ($query) use ($input) {
                $query->find($input['batch_id']);
            })->get();
    }

    public function almostExpired($input)
    {
        $now = Carbon::now();
        $setting_remaining_months = Setting::getValue('remaining_months_for_expiration');
        $remaining_months = $now->copy()->addMonths($setting_remaining_months)->format('Y-m-d');
        $local = app()->getLocale();

        return $this->model->query()->applyFilters($input)
            ->when(!isset($input['remaining_expiry']), function ($query) use ($remaining_months) {
                $query->whereDate('expired_at', '<=', $remaining_months);
            })->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where("name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['remaining_expiry']), function ($query) use ($input, $now) {
                switch ($input['remaining_expiry']) {
                    case BatchRemainingExpiry::PROHIBITED:
                        $query->whereDate('expired_at', '<=', $now->format('Y-m-d'));
                        break;
                    case BatchRemainingExpiry::LESS_THAN_TWO_MONTHS:
                        $startDate = $now->format('Y-m-d');
                        $endDate = $now->copy()->addMonths(2);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_THREE_TO_SIX:
                        $startDate = $now->copy()->addMonths(2);
                        $endDate = $now->copy()->addMonths(6);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_SEVEN_TO_TWELVE:
                        $startDate = $now->copy()->addMonths(6);
                        $endDate = $now->copy()->addMonths(12);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_THIRTEEN_TO_EIGHTEEN:
                        $startDate = $now->copy()->addMonths(12);
                        $endDate = $now->copy()->addMonths(18);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_NINETEEN_TO_TWENTY_FOUR:
                        $startDate = $now->copy()->addMonths(18);
                        $endDate = $now->copy()->addMonths(24);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_TWENTY_FIVE_TO_THIRTY:
                        $startDate = $now->copy()->addMonths(24);
                        $endDate = $now->copy()->addMonths(30);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                    case BatchRemainingExpiry::FROM_THIRTY_ONE_TO_THIRTY_SIX:
                        $startDate = $now->copy()->addMonths(31);
                        $endDate = $now->copy()->addMonths(36);
                        $query->whereBetween('expired_at', [$startDate, $endDate]);
                        break;
                }
            })->when(isset($input['sort_by']), function ($query) use ($input) {
                switch ($input['sort_by']) {
                    case 'product_name':
                        $input['language'] == 'ar' ?
                            $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->ar', $input['direction'])->select('batches.*') :
                            $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->en', $input['direction'])->select('batches.*');
                        break;
                    case 'manufacturer_name':
                        $input['language'] == 'ar' ?
                            $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->ar', $input['direction'])->select('batches.*') :
                            $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->en', $input['direction'])->select('batches.*');
                        break;
                    case 'location':
                        $query->join('corridors', 'corridors.id', '=', 'batches.corridor_id')
                            ->orderBy('corridors.number', $input['direction'])->select('batches.*');
                        break;
                    default:
                        $query->orderBy('expired_at', 'asc');
                }
            })->with('warehouse', 'product.manufacturer', 'supplier', 'corridor')->paginate();
    }

    public function createFromReturnable(Returnables $returnable, ?Batch $sourceBatch, int $warehouse_id)
    {
        if (!$sourceBatch) {
            $product = $returnable->getProduct();
        }

        $returnable->warehouse_id = $warehouse_id;
        $batch = $this->create($returnable, $sourceBatch, $product ?? null);

        $batch->recordChangeInQuantity($returnable->quantity, BatchHistoryType::SALES_RETURN, $returnable);
    }

    public function store($input, $product)
    {
        $sourceBatch = $this->returnSourceBatch($input['expired_at'], $input['operating_number']);

        return $this->create($input, $sourceBatch, $product);
    }

    public function returnSourceBatch($expired_at, $operating_number): ?Batch
    {
        return $this->model->query()->applyFilters($expired_at)
            ->where('operating_number', $operating_number)
            ->whereNull('parent_batch_id')
            ->first();
    }

    public function create($input, $sourceBatch, $product)
    {
        if (!is_array($input)) {
            $input->toArray();
        }

        $warehouse_product = $product?->warehouses->find($input['warehouse_id'])->pivot;

        return $this->model->create([
            'quantity'              => $input['quantity'],
            'expired_at'            => isset($sourceBatch->expired_at) ? $sourceBatch->expired_at : $input['expired_at'],
            'operating_number'      => isset($sourceBatch->operating_number) ? $sourceBatch->operating_number : $input['operating_number'],
            'current_quantity'         => $input['quantity'],
            'warehouse_id'          => $input['warehouse_id'],
            'product_id'            => $input['product_id'],
            'cart_purchase_id'      => $input['cart_purchase_id'] ?? null,
            'purchase_id'           => $input['purchase_id'] ?? null,
            'discount'              => $input['discount'] ?? null,
            'supplier_id'           => $input['supplier_id'] ?? null,
            'receiver_reviewer_id'   => Auth::id(),
            'created_by'            => Auth::id(),
            'supplied_at'           => $input['supplier_id'] ? Carbon::now() : $input['supplied_at'] ?? null,
            'corridor_id'           => $sourceBatch->corridor_id ?? $warehouse_product->corridor_id,
            'warehouse_id'          => $sourceBatch->warehouse_id ?? $warehouse_product->warehouse_id,
            'product_id'            => $sourceBatch->product_id ?? $product->id,
            'parent_batch_id'       => $sourceBatch->id ?? null,
            'package'               => $sourceBatch->package ?? $product->packets_number_in_package,
            'packet'                => $sourceBatch->packet ?? $product->items_number_in_packet,
            'stand'                 => $sourceBatch->stand ?? $warehouse_product->stand,
            'shelf'                 => $sourceBatch->shelf ?? $warehouse_product->shelf,
        ]);
    }

    /**
     * Get batches and update operating number of each batch.
     * when update operating number of batch that will be take a new operating number.
     * If new operating number founded on other batch, the new one will take it as parent batch.
     * If not will create a new batch with data of product.
     *
     * In the all cases all changes will be saved on batch history table by make new record with type (edit) and increase or decrease tha quantity.
     */
    public function getAll($input)
    {
        $query = $this->model->query()->applyFilters()
            ->when(isset($input['sort_by']), function ($query) use ($input) {
                $direction = isset($input['direction']) ? $input['direction'] : 'asc';
                switch ($input['sort_by']) {
                    case 'product_name_ar':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->ar', $direction)->select('batches.*');
                        break;
                    case 'product_name_en':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->en', $direction)->select('batches.*');
                        break;
                    case 'manufacturer_en':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->en', $direction)->select('batches.*');
                        break;
                    case 'manufacturer_ar':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->ar', $direction)->select('batches.*');
                        break;
                }
            })->whereHas('product', function ($query) use ($input) {
                $query->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                    $query->where('products.manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['old_operating_number']), function ($query) use ($input) {
                $query->whereHas('originalBatch', function ($query) use ($input) {
                    $query->where('laravel_reserved_0.operating_number', $input['old_operating_number']);
                });
            })
            ->when(isset($input['old_expired_at']), function ($query) use ($input) {
                $query->whereHas('originalBatch', function ($query) use ($input) {
                    $query->whereRaw("DATE_FORMAT(expired_at, '%Y-%m') = ?", [Carbon::parse($input['old_expired_at'])->format('Y-m')]);
                });
            })

            ->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 1, function ($query) {
                $query->where('current_quantity', '!=', 0);
            })
            //? quantity_more_than_zero == 0 ?!
            ->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 0, function ($query) {
                $query->where('current_quantity', '==', 0);
            })->with([
                'supplier',
                'receiverReviewer',
                'receiverDistributor',
                'storingWorker',
                'warehouse',
                'corridor',
                'cart',
                'batchHistories',
                'createdBy',
                'originalBatch',
                'product' => [
                    'manufacturer',
                    'warehouses',
                ],
            ]);

        return $this->execute($query);
    }

    public function updateBatchOperatingNumber($input, Batch $oldBatch, $warehouse = null, $preparedBy = null)
    {
        DB::beginTransaction();
        // *Refactor
        // 1- you can reuse this method returnSourceBatch() instead of this query
        // 2- Batch:: we don't use model like that we use $this->model
        $batchExists = Batch::query()->applyFilters(['expired_at' => $input['expired_at']])
            ->where('operating_number', $input['operating_number'])
            ->whereNull('parent_batch_id')
            ->first();

        if ($warehouse == null) {
            $warehouse_id = $batchExists ? $batchExists->warehouse_id : $oldBatch->warehouse_id;
        }

        $newBatch = [
            'quantity' => $input['quantity'],
            'expired_at' => $input['expired_at'],
            'operating_number' => $input['operating_number'],
            'current_quantity' => $input['quantity'],
            'created_by' => Auth::user()->id,

            'parent_batch_id' => $batchExists ? $batchExists->id : null,
            'discount' => $batchExists ? $batchExists->discount : $oldBatch->discount,
            'corridor_id' => $batchExists ? $batchExists->corridor_id : $oldBatch->corridor_id,
            'warehouse_id' => $warehouse->id ?? $warehouse_id,
            'product_id' => $batchExists ? $batchExists->product_id : $oldBatch->product_id,
            'package' => $batchExists ? $batchExists->package : $oldBatch->package,
            'packet' => $batchExists ? $batchExists->packet : $oldBatch->packet,
            'stand' => $batchExists ? $batchExists->stand : $oldBatch->stand,
            'shelf' => $batchExists ? $batchExists->shelf : $oldBatch->shelf,
            'supplier_id' => $batchExists ? $batchExists->supplier_id : $oldBatch->supplier_id,
            'supplied_at' => $batchExists ? $batchExists->supplied_at : $oldBatch->supplied_at,
            'storing_worker_id' => $batchExists ? $batchExists->storing_worker_id : $oldBatch->storing_worker_id,
            'receiver_distributor_id' => $batchExists ? $batchExists->receiver_distributor_id : $oldBatch->receiver_distributor_id,
            'receiver_reviewer_id' => $batchExists ? $batchExists->receiver_reviewer_id : $oldBatch->receiver_reviewer_id,
            'updated_by' => $preparedBy != null ? Auth::user()->id : null,
        ];

        $createdBatch = Batch::create($newBatch)->recordChangeInQuantity($newBatch['current_quantity'], BatchHistoryType::EDIT, subject: $oldBatch, second_user_id: $preparedBy);

        $oldBatch->updateQuantity(
            ($oldBatch->current_quantity - $newBatch['current_quantity']),
            BatchHistoryType::EDIT,
            $createdBatch
        );

        DB::commit();

        return $createdBatch->load([
            'product' => [
                'warehouses',
                'manufacturer',
            ],
            'originalBatch',
            'supplier',
            'createdBy'
        ]) ?? '';
    }

    public function completeReceiving(array $input)
    {
        $batches = $this->model->whereIn('id', $input['batch_ids'])->get();

        $batches->each(function ($value) {
            if ($value->receiver_distributor_id != null || $value->distributor_received_at != null) {
                throw new BatchException('There is a batch already received.');

                return false;
            }
        });

        $this->updateRealtimeBatchesCount($batches, 'receipt', 'removed');
        $this->updateRealtimeBatchesCount($batches, 'housing', 'added');

        return $batches->each->update([
            'receiver_distributor_id' => Auth::id(),
            'distributor_received_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function receivingBatches($input)
    {
        $local = app()->getLocale();

        $query = $this->model->query()->where([
            ['purchase_id', '!=', null],
            ['receiver_distributor_id', null],
            ['distributor_received_at', null],
        ])->whereRelation('purchase', 'reviewed_by', '!=', null)
            ->applyFilters($input->except('corridor_id'))->applySorts($input)
            ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where("name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['product_type']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('type', $input['product_type']);
                });
            })->when(isset($input['price']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('price', $input['price']);
                });
            });

        $counts = $this->getBatchesCountPerCorridor($query->get());
        $this->setRealtimeBatchesCountPerCorridor('receipt', $counts);

        $batches = $query->applyFilters($input->only('corridor_id'))
            ->with('receiverReviewer', 'warehouse', 'product.manufacturer', 'corridor', 'supplier', 'purchase')
            ->get();

        return ['batches' => $batches, 'counts' => $counts];
    }

    public function receivedBatches($input)
    {
        $local = app()->getLocale();

        $query = $this->model->query()->where([
            ['receiver_distributor_id', '!=', null],
            ['distributor_received_at', '!=', null],
        ])->applyFilters(input: $input, exclude: 'corridor_id')->applySorts($input)
            ->when(!isset($input['distributor_received_at']), function ($query) {
                $query->whereDate('distributor_received_at', Carbon::today()->startOfDay());
            })->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where(" name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['product_type']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('type', $input['product_type']);
                });
            })->when(isset($input['price']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('price', $input['price']);
                });
            });

        $counts = $this->getBatchesCountPerCorridor($query->get());

        $batches = $query->applyFilters(filters: 'corridor_id')
            ->with('receiverReviewer', 'warehouse', 'product', 'corridor', 'supplier', 'purchase')
            ->get();

        return ['batches' => $batches, 'counts' => $counts];
    }

    public function completeStoring(array $input)
    {
        $batches = $this->model->whereIn('id', $input['batch_ids'])->get();

        $batches->each(function ($value) {
            if ($value->storing_worker_id != null || $value->stored_at != null) {
                throw new BatchException('There is a batch already stored.');

                return false;
            }
        });

        $this->updateRealtimeBatchesCount($batches, 'housing', 'removed');

        return $batches->each->update([
            'storing_worker_id' => Auth::id(),
            'stored_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function storingBatches($input)
    {
        $local = app()->getLocale();

        $query = $this->model->query()
            ->where([
                ['stored_at', null],
                ['storing_worker_id', null],
                ['distributor_received_at', '!=', null],
            ])->applyFilters(input: $input, exclude: 'corridor_id')
            ->applySorts($input)
            ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where("name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['product_type']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('type', $input['product_type']);
                });
            })->when(isset($input['price']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('price', $input['price']);
                });
            });

        $counts = $this->getBatchesCountPerCorridor($query->get());
        $this->setRealtimeBatchesCountPerCorridor('housing', $counts);

        $batches = $query->applyFilters(filters: 'corridor_id')
            ->with('receiverReviewer', 'warehouse', 'product', 'corridor', 'supplier', 'purchase')
            ->get();

        return ['batches' => $batches, 'counts' => $counts];
    }

    public function storedBatches($input)
    {
        $local = app()->getLocale();

        $query = $this->model->query()->where([
            ['purchase_id', '!=', null],
            ['stored_at', '!=', null],
            ['storing_worker_id', '!=', null],
        ])->applyFilters(input: $input, exclude: 'corridor_id')
            ->applySorts($input)
            ->when(!isset($input['stored_at']) || $input['stored_at'] == 'null', function ($query) {
                $query->whereDate('stored_at', Carbon::today()->startOfDay());
            })->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where("name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['product_type']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('type', $input['product_type']);
                });
            })->when(isset($input['price']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('price', $input['price']);
                });
            });

        $counts = $this->getBatchesCountPerCorridor($query->get());

        $batches = $query->applyFilters(filters: 'corridor_id')
            ->with('receiverReviewer', 'warehouse', 'product', 'corridor', 'supplier', 'purchase', 'storingWorker')
            ->get();

        return ['batches' => $batches, 'counts' => $counts];
    }

    public function find($batch_id)
    {
        return $this->model->find($batch_id);
    }

    public function duplicate($scarceWarehouse, $input, $cart)
    {
        $oldBatch = $this->find($input['batch_id']);
        $input['quantity'] = $input['order_quantity'];
        $cartSubBatch = $cart->subBatches->where('batch_id', $oldBatch->id)->first();
        $oldBatch->updateQuantity(
            ($oldBatch->current_quantity + $input['order_quantity']),
            BatchHistoryType::SALES,
            $cartSubBatch
        );
        $newBatch = $this->updateBatchOperatingNumber($input, $oldBatch, $scarceWarehouse, $cart->prepared_by);
        $cartSubBatch->update(['batch_id', $newBatch->id]);
        $newBatch->updateQuantity(0, BatchHistoryType::SALES, $cartSubBatch);

        return $newBatch;
    }

    public function incrementBatchQuantity($input)
    {
        return $this->model->where('id', $input['batch_id'])->increment('current_quantity', $input['quantity']);
    }

    private function getBatchesCountPerCorridor($batches)
    {
        $counts = $batches->groupBy('corridor_id')->map->count();

        return Corridor::get()->map(function ($corridor) use ($counts) {
            return [
                'corridor_id' => $corridor->id,
                'count' => $corridor->is_main_corridor
                    ? $counts->sum()
                    : $counts[$corridor->id] ?? 0,
            ];
        });
    }

    private function setRealtimeBatchesCountPerCorridor($stage, $counts)
    {
        return $counts->each(
            fn ($item) => Redis::set("$stage." . $item['corridor_id'], $item['count'])
        );
    }

    private function updateRealtimeBatchesCount(Collection $batches, string $stage, string $action)
    {
        $totals_corridor_id = Corridor::where('is_main_corridor', 1)->first()?->id;

        $method = $action == 'added' ? 'incrby' : 'decrby';

        $batches_count = $batches
            ->load('receiverReviewer', 'warehouse', 'product.manufacturer', 'corridor', 'supplier')
            ->groupBy('corridor_id')
            ->put($totals_corridor_id, $batches)
            ->map(function ($group, $corridor_id) use ($method, $stage) {
                return [
                    'count' => Redis::$method("{$stage}.{$corridor_id}", $group->count()),
                    'batches' => $group,
                    'corridor_id' => $corridor_id,
                ];
            })->values()->toArray();

        $stage == 'receipt'
            ? event(new ReceiptBatchesCount($batches_count, $action))
            : event(new HousingBatchesCount($batches_count, $action));
    }
}

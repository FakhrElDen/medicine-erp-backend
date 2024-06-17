<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Order\Entities\Returnables;
use Modules\Product\DTOs\ProductReportsDTO;
use Modules\Product\Entities\BatchHistory;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Purchase\Entities\CartPurchase;
use Modules\Warehouse\DTOs\InventoryIndexDTO;
use Modules\Warehouse\Entities\BatchTransfer;

class BatchHistoryRepository extends BaseRepository
{
    public bool $pagination = true;

    public function __construct(protected BatchHistory $model)
    {
    }

    protected function execute(Builder $query, $pagination = null): Collection|LengthAwarePaginator
    {
        if ($pagination == true) {
            return $query->get();
        }
        return $this->pagination ? $query->paginate(10) : $query->get();
    }

    public function findBySubject(Model $subject): ?BatchHistory
    {
        return $this->model->query()->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->first();
    }

    public function corrections(InventoryIndexDTO $input)
    {
        $query = $this->basicQuery($input)
            ->where('batch_histories.type', BatchHistoryType::CORRECTION);

        return $this->execute($query);
    }

    protected function purchasesQuery(ProductReportsDTO $input, $with_sorting = true): Builder
    {
        return $this->basicQuery($input, $with_sorting)
            ->where('batch_histories.type', BatchHistoryType::PURCHASE)
            ->when(
                $input->has('supplier_id'),
                fn (Builder $query) => $query->whereHasMorph(
                    'subject',
                    CartPurchase::class,
                    fn (Builder $q) => $q->whereRelation('purchase', 'supplier_id', $input->supplier_id)
                )
            );
    }

    public function purchases(ProductReportsDTO $input)
    {
        $query = $this->purchasesQuery($input)
            ->with([
                'user',
                'batch.product',
                'batch.warehouse',
                'subject.purchase.supplier',
            ]);

        return $this->execute($query);
    }

    public function purchasesTotalAmount(ProductReportsDTO $input): int
    {
        return $this->purchasesQuery($input, false)
            ->sum('amount');
    }

    public function purchasesTotalOrders(ProductReportsDTO $input): int
    {
        return (int) $this->purchasesQuery($input, false)
            ->join('cart_purchases', 'batch_histories.subject_id', 'cart_purchases.id')
            ->select(DB::raw('COUNT(cart_purchases.purchase_id) as count'))
            ->first()->count;
    }

    protected function salesQuery(ProductReportsDTO $input, $with_sorting = true): Builder
    {
        return $this->basicQuery($input, $with_sorting)
            ->where('batch_histories.type', BatchHistoryType::SALES)
            ->when(
                $input->has('pharmacy_id'),
                fn (Builder $query) => $query->whereHasMorph(
                    'subject',
                    CartSubBatch::class,
                    fn (Builder $q) => $q->whereRelation('cart.order', 'pharmacy_id', $input->pharmacy_id)
                )
            );
    }

    public function sales(ProductReportsDTO $input)
    {
        $query = $this->salesQuery($input)
            ->with([
                'user',
                'batch.product',
                'batch.warehouse',
                'subject.cart.order.pharmacy',
            ]);

        return $this->execute($query);
    }

    public function salesTotalAmount(ProductReportsDTO $input): int
    {
        $amount = $this->salesQuery($input, false)
            ->sum('amount');

        return abs($amount);
    }

    public function salesTotalOrders(ProductReportsDTO $input): int
    {
        return (int) $this->salesQuery($input, false)
            ->join('cart_sub_batch', 'batch_histories.subject_id', 'cart_sub_batch.id')
            ->join('carts', 'cart_sub_batch.cart_id', 'carts.id')
            ->select(DB::raw('COUNT(carts.order_id) as count'))
            ->first()->count;
    }

    protected function salesReturnsQuery(ProductReportsDTO $input, $with_sorting = true): Builder
    {
        return $this->basicQuery($input, $with_sorting)
            ->where('batch_histories.type', BatchHistoryType::SALES_RETURN)
            ->when(
                $input->has('pharmacy_id'),
                fn (Builder $query) => $query->whereHasMorph(
                    'subject',
                    Returnables::class,
                    fn (Builder $q) => $q->whereRelation('return', 'pharmacy_id', $input->pharmacy_id)
                )
            );
    }

    public function salesReturns(ProductReportsDTO $input)
    {
        $query = $this->salesReturnsQuery($input)
            ->with([
                'user',
                'batch.product',
                'batch.warehouse',
                'subject.return.pharmacy',
            ]);

        return $this->execute($query);
    }

    public function salesReturnsTotalAmount(ProductReportsDTO $input): int
    {
        return (int) $this->salesReturnsQuery($input, false)
            ->select(DB::raw('SUM(ABS(amount)) as amount'))->first()->amount ?? 0;
    }

    public function salesReturnsTotalOrders(ProductReportsDTO $input): int
    {
        return (int) $this->salesReturnsQuery($input, false)
            ->join('returnables', 'batch_histories.subject_id', 'returnables.id')
            ->select(DB::raw('COUNT(returnables.returns_id) as count'))
            ->first()->count;
    }

    protected function correctionsQuery(ProductReportsDTO $input, $with_sorting = true): Builder
    {
        return $this->basicQuery($input, $with_sorting)
            ->where('batch_histories.type', BatchHistoryType::CORRECTION)
            ->when(
                $input->has('user_id'),
                fn ($q) => $q->where('batch_histories.user_id', $input->user_id)
            );
    }

    public function correctionsByProduct(ProductReportsDTO $input)
    {
        $query = $this->correctionsQuery($input)
            ->with([
                'batch.product',
                'batch.warehouse',
                'user',
            ]);

        return $this->execute($query);
    }

    public function correctionsTotalAmount(ProductReportsDTO $input, $state = null): int
    {
        return (int) $this->correctionsQuery($input, false)
            ->when($state == 'excess', fn ($q) => $q->where('amount', '>', 0))
            ->when($state == 'shortage', fn ($q) => $q->where('amount', '<', 0))
            ->select(DB::raw('SUM(ABS(amount)) as amount'))->first()->amount ?? 0;
    }

    protected function transfersQuery(ProductReportsDTO $input, $with_sorting = true): Builder
    {
        return $this->basicQuery($input, $with_sorting)
            ->where('batch_histories.type', BatchHistoryType::TRANSFER)
            ->where('amount', '>', 0)
            ->when(
                $input->has('user_id'),
                fn ($q) => $q->where('batch_histories.user_id', $input->user_id)
            );
    }

    public function transfers(ProductReportsDTO $input)
    {
        $query = $this->transfersQuery($input)
            ->with([
                'user',
                'batch.product',
                'batch.warehouse',
                'subject.transfer.fromWarehouse',
            ]);

        return $this->execute($query);
    }

    public function transfersTotalAmount(ProductReportsDTO $input): int
    {
        return $this->transfersQuery($input, false)
            ->where('amount', '>', 0)
            ->sum('amount');
    }

    public function transfersTotalIncoming(ProductReportsDTO $input): int
    {
        if (!$input->warehouse_id) {
            throw new \InvalidArgumentException('total incoming transfers without warehouse_id will always be zero');
        }

        return $this->transfersQuery($input, false)
            ->sum('amount');
    }

    public function transfersTotalOutgoing(ProductReportsDTO $input): int
    {
        return $this->transfersQuery($input->except('warehouse_id'), false)
            ->whereHasMorph(
                'subject',
                BatchTransfer::class,
                fn ($q) => $q->whereRelation('transfer', 'transfer_from_warehouse_id', $input->warehouse_id)
            )->sum('amount');
    }

    public function transfersTotalOrders(ProductReportsDTO $input): int
    {
        return (int) $this->transfersQuery($input, false)
            ->join('batch_transfer', 'batch_histories.subject_id', '=', 'batch_transfer.id')
            ->select(DB::raw('COUNT(batch_transfer.transfer_id) as count'))
            ->first()->count;
    }

    public function getProductPrices(int $product_id)
    {
        return $this->model->query()
            ->join('batches', 'batches.id', '=', 'batch_histories.batch_id')
            ->where('batches.product_id', $product_id)
            ->whereIn('type', [BatchHistoryType::PURCHASE, BatchHistoryType::SALES, BatchHistoryType::SALES_RETURN])
            ->leftJoin('cart_purchases', function (JoinClause $join) {
                $join->on('cart_purchases.id', 'batch_histories.subject_id')
                    ->where('batch_histories.subject_type', CartPurchase::class);
            })->leftJoin('returnables', function (JoinClause $join) {
                $join->on('returnables.id', 'batch_histories.subject_id')
                    ->where('batch_histories.subject_type', Returnables::class);
            })->leftJoin('cart_sub_batch', function (JoinClause $join) {
                $join->on('cart_sub_batch.id', 'batch_histories.subject_id')
                    ->where('batch_histories.subject_type', CartSubBatch::class);
            })->leftJoin('carts', function (JoinClause $join) {
                $join->on('carts.id', 'cart_sub_batch.cart_id')
                    ->where('batch_histories.subject_type', CartSubBatch::class);
            })->selectRaw('batch_histories.type, COALESCE(cart_purchases.public_price, returnables.price, carts.price) as price, MAX(batch_histories.created_at) as created_at')
            ->groupBy('batch_histories.type', 'price')
            ->get()
            ->sortBy('created_at');
    }

    protected function basicQuery($input, $with_sorting = true): Builder
    {
        return $this->model->query()
            ->select('batch_histories.*')
            ->join('batches', 'batch_histories.batch_id', '=', 'batches.id')
            ->when($with_sorting, fn ($q) => $q->addQuantityBefore())
            ->when(
                $input->has('product_id'),
                fn ($q) => $q->where('batches.product_id', $input->product_id)
            )->when(
                $input->has('warehouse_id'),
                fn ($q) => $q->where('batches.warehouse_id', $input->warehouse_id)
            )->when(
                $input->has('from'),
                fn ($q) => $q->whereDate('batch_histories.created_at', '>=', $input->from)
            )->when(
                $input->has('to'),
                fn ($q) => $q->whereDate('batch_histories.created_at', '<=', $input->to)
            )->when($with_sorting,
                fn ($query) => $query->when(
                    $input->has('sort_by'),
                    fn ($q) => $this->applySorts($q, $input),
                    fn ($q) => $q->orderBy('batch_histories.id', 'desc')
                )
            );
    }

    public function applySorts(Builder $query, $input): void
    {
        match ($input->sort_by) {
            'user_name' => $query->join('users', 'batch_histories.user_id', '=', 'users.id'),
            'warehouse_name', 'to_warehouse_name' => $query->join('warehouses', 'batches.warehouse_id', 'warehouses.id'),
            'from_warehouse_name' => $query->addFromWarehouseName(),
            'excess', 'shortage' => $query->addExcessAndShortage(),
            'pharmacy_name' => $query->addPharmacyName(),
            'supplier_name' => $query->addSupplierName(),
            default => null
        };

        $sorts = [
            'user_name' => 'users.name',
            'warehouse_name' => 'warehouses.name',
            'to_warehouse_name' => 'warehouses.name',
            'current_quantity' => 'batches.current_quantity',
            'amount' => DB::raw('ABS(amount)'),
            'quantity_before' => 'warehouse_product_quantity_before',
            'quantity_after' => 'warehouse_product_quantity_after',
        ];

        $query->orderBy($sorts[$input->sort_by] ?? $input->sort_by, $input->direction);
    }

    public function buildQueryforGetBatchesHistory($query, $input, $type)
    {
        return $query->select('batch_histories.*')
            ->with('subject', 'secondUser', 'batch.product.manufacturer', 'batch.corridor', 'batch.warehouse', 'user',
                'batch.cart', 'batch.storingWorker', 'batch.supplier', 'batch.receiverReviewer', 'batch.createdBy', 'batch.updatedBy')
            ->where('amount', '>', 0)
            ->where('batch_histories.type', $type)
            ->join('batches', 'batches.id', '=', 'batch_histories.batch_id')
            ->when(isset($input['second_user_id']), function ($query) use ($input) {
                $query->where('second_user_id', $input['second_user_id']);
            })
            ->when(isset($input['warehouse_id']), function ($query) use ($input) {
                $query->where('batches.warehouse_id', $input['warehouse_id']);
            })
            ->when(isset($input['corridor_id']), function ($query) use ($input) {
                $query->where('batches.corridor_id', $input['corridor_id']);
            })
            ->when(isset($input['stand']), function ($query) use ($input) {
                $query->where('batches.stand', $input['stand']);
            })
            ->when(isset($input['shelf']), function ($query) use ($input) {
                $query->where('batches.shelf', $input['shelf']);
            })
            ->when(isset($input['supplied_at']), function ($query) use ($input) {
                $query->where('batches.supplied_at', $input['supplied_at']);
            })
            ->when(isset($input['supplier_id']), function ($query) use ($input) {
                $query->where('batches.supplier_id', $input['supplier_id']);
            })
            ->when(isset($input['storing_worker_id']), function ($query) use ($input) {
                $query->where('batches.storing_worker_id', $input['storing_worker_id']);
            })
            ->when(isset($input['receiver_reviewer_id']), function ($query) use ($input) {
                $query->where('batches.receiver_reviewer_id', $input['receiver_reviewer_id']);
            })
            ->when(isset($input['created_by']), function ($query) use ($input) {
                $query->where('batches.created_by', $input['created_by']);
            })
            ->when(isset($input['updated_by']), function ($query) use ($input) {
                $query->where('batches.updated_by', $input['updated_by']);
            })
            ->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 1, function ($query) {
                $query->where('batches.current_quantity', '!=', 0);
            })
            ->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 0, function ($query) {
                $query->where('batches.current_quantity', '==', 0);
            })
            ->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('products.manufacturer_id', $input['manufacturer_id']);
                });
            })
            ->when(isset($input['price_from']), function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('price', '>=', $input['price_from']);
                });
            })
            ->when(isset($input['price_to']), function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('price', '<=', $input['price_to']);
                });
            })
            ->when(isset($input['name']) && $input['name'] != 'null', function ($query) use ($input) {
                $query->whereHas('batch.product', function ($query) use ($input) {
                    $query->where('name->ar', 'like', '%' . $input['name'] . '%')->orWhere('name->en', 'like', '%' . $input['name'] . '%');
                });
            })
            ->when(isset($input['sort_by']), function ($query) use ($input) {
                $direction = isset($input['direction']) ? $input['direction'] : 'asc';
                switch ($input['sort_by']) {
                    case 'location':
                        $query->join('corridors', 'corridors.id', '=', 'batches.corridor_id')
                            ->orderBy('corridors.number', $direction);
                        break;
                    case 'product_name_ar':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->ar', $direction);
                        break;
                    case 'product_name_en':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.name->en', $direction);
                        break;
                    case 'current_quantity':
                        $query->orderBy('batches.current_quantity', $direction);
                        break;
                    case 'price':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->orderBy('products.price', $direction);
                        break;
                    case 'manufacturer_en':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
                            ->orderBy('manufacturers.name->en', $direction);
                        break;
                    case 'manufacturer_ar':
                        $query->join('products', 'products.id', '=', 'batches.product_id')
                            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
                            ->orderBy('manufacturers.name->ar', $direction);
                        break;
                }
            });
    }

    public function getBatchesHistory($input, $type, $not_paginated = null)
    {
        $query = $this->model->query();
        $query = $this->buildQueryforGetBatchesHistory($query, $input, $type);
        return $this->execute($query, $not_paginated  ?? null);
    }

    public function getTotalQuantity($input, $type)
    {
        $query = $this->model->query();
        $query = $this->buildQueryforGetBatchesHistory($query, $input, $type);
        $total_quantity = $query->get()->sum('batch.current_quantity');

        return $total_quantity;
    }
}

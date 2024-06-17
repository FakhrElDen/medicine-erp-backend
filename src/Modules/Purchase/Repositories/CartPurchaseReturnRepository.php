<?php

namespace Modules\Purchase\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Product\DTOs\ProductReportsDTO;
use Modules\Purchase\Entities\CartPurchasesReturn;

class CartPurchaseReturnRepository extends BaseRepository
{
    public bool $pagination = true;

    public function __construct(protected CartPurchasesReturn $model)
    {
        //
    }

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate() : $query->get();
    }

    public function get(ProductReportsDTO $input)
    {
        $query = $this->basicQuery($input)
            ->with([
                'purchasesReturn.createdBy',
                'cartPurchase.product',
                'cartPurchase.purchase.supplier',
                'cartPurchase.purchase.warehouse',
            ]);

        return $this->execute($query);
    }

    public function totalAmount(ProductReportsDTO $input): int
    {
        return $this->basicQuery($input, false)
            ->sum('quantity');
    }

    public function totalOrders(ProductReportsDTO $input): int
    {
        return (int) $this->basicQuery($input, false)
            ->select(DB::raw('COUNT(purchases_return_id) as count'))
            ->first()->count;
    }

    protected function basicQuery(ProductReportsDTO $input, $with_sorting = true)
    {
        return $this->model->query()
            ->when(
                $input->has('product_id'),
                fn ($query) => $query->whereRelation('cartPurchase', 'product_id', $input->product_id)
            )->when(
                $input->has('supplier_id'),
                fn ($query) => $query->whereRelation('cartPurchase.purchase', 'supplier_id', $input->supplier_id)
            )->when(
                $input->has('warehouse_id'),
                fn ($query) => $query->whereRelation('cartPurchase.purchase', 'warehouse_id', $input->warehouse_id)
            )->when(
                $input->has('user_id'),
                fn ($query) => $query->where('purchasesReturn.created_by', $input->user_id)
            )->when(
                $input->has('from'),
                fn ($q) => $q->whereDate('cart_purchases_returns.created_at', '>=', $input->from)
            )->when(
                $input->has('to'),
                fn ($q) => $q->whereDate('cart_purchases_returns.created_at', '<=', $input->to)
            )->when($with_sorting,
                fn ($query) => $query->when(
                    $input->has('sort_by'),
                    fn ($q) => $this->applySorts($q, $input),
                    fn ($q) => $q->orderBy('cart_purchases_returns.id', 'desc')
                )
            );
    }

    public function applySorts(Builder $query, $input)
    {
        match ($input->sort_by) {
            'user_name' => $query->addUserName(),
            'warehouse_name' => $query->addWarehouseName(),
            'supplier_name' => $query->addSupplierName(),
            default => null
        };

        $sorts = [
            'amount' => 'quantity',
            'quantity_before' => 'quantity',
            'quantity_after' => 'quantity',
        ];

        $query->orderBy($sorts[$input->sort_by] ?? $input->sort_by, $input->direction);
    }

    public function deleteByCartPurchaseID($cart_purchase_id)
    {
        return $this->model->where('cart_purchase_id', $cart_purchase_id)->delete();
    }
}

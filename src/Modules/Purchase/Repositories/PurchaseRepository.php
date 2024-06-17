<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Carbon;
use App\Repositories\BaseRepository;
use Modules\Purchase\Entities\Purchase;
use Modules\Setting\Entities\Setting;

class PurchaseRepository extends BaseRepository
{
    public function __construct(protected Purchase $model)
    {
    }

    public function index($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts()
            ->when(!isset($input['from']), function ($query) {
                $query->where('purchases.created_at', '>=', Carbon::today()->startOfMonth());
            })->when(isset($input['from']), function ($query) use ($input) {
                $query->whereBetween('created_at', [
                    Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(),
                    Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()
                ]);
            })->when(isset($input['code']), function ($query) use ($input) {
                $query->whereHas('client', function ($query) use ($input) {
                    $query->where('clients.code', $input['code']);
                });
            })->with('pharmacy', 'createdBy', 'supplier', 'warehouse')
            ->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10, ['*'], 'purchase_page');
    }

    public function receivingReviewer($input)
    {
        $date_filter_by_day = intval(Setting::getValue('date_filter_by_day'));
        
        if (isset($input['to']) && !isset($input['from'])) {
            $startDate = Carbon::createFromFormat('Y-m-d', $input['to'])->subDay($date_filter_by_day)->startOfDay();
        } else {
            $startDate = isset($input['from']) ? Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay() : Carbon::now()->subDay($date_filter_by_day)->startOfDay();
        }

        $endDate = isset($input['to']) ? Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay() : Carbon::now()->endOfDay();

        return $this->model->query()->applyFilters()->applySorts()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('pharmacy', 'createdBy', 'supplier', 'warehouse', 'cart', 'reviewedBy', 'batches.product', 'batches.cartPurchaseItem')
            ->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function update($id, $input)
    {
        return $this->model->where('id', $id)->update($input);
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts()->get();
    }
}

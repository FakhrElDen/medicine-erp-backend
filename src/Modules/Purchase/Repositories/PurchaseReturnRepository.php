<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Carbon;
use App\Repositories\BaseRepository;
use Modules\Purchase\DTOs\PurchaseReturnDTO;
use Modules\Purchase\Entities\PurchasesReturn;
use Modules\Setting\Entities\Setting;

class PurchaseReturnRepository extends BaseRepository
{
    public function __construct(protected PurchasesReturn $model)
    {
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->with('purchase', 'createdBy')->get();
    }

    public function allPaginated($input)
    {
        $date_filter_by_day = intval(Setting::getValue('date_filter_by_day'));

        if (isset($input['to']) && !isset($input['from'])) {
            $startDate = Carbon::createFromFormat('Y-m-d', $input['to'])->subDay($date_filter_by_day)->startOfDay();
        } else {
            $startDate = isset($input['from']) ? Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay() : Carbon::now()->subDay($date_filter_by_day)->startOfDay();
        }

        $endDate = isset($input['to']) ? Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay() : Carbon::now()->endOfDay();

        return $this->model->query()->where([
            ['supplier_id_number', null],
            ['supplier_name', null],
        ])->applyFilters($input)
            ->when(isset($input['from']) || isset($input['to']), function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->when(isset($input['supplier_id']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('supplier_id', $input['supplier_id']);
                });
            })->when(isset($input['warehouse_id']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('warehouse_id', $input['warehouse_id']);
                });
            })->when(isset($input['reviewed_by']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('reviewed_by', $input['reviewed_by']);
                });
            })->when(isset($input['purchase_number']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('purchase_number', $input['purchase_number']);
                });
            })->with('purchase.warehouse', 'purchase.reviewedBy', 'purchase.supplier', 'createdBy')->paginate();
    }

    public function store($input)
    {
        return $this->model->create([
            'purchase_id' => $input['purchase_id'],
            'created_by' => auth()->id(),
        ]);
    }

    public function assignReturnToCartPurchase(PurchaseReturnDTO $input, $purchaseReturn, $validatedRequest)
    {
        if ($purchaseReturn) {
            return $purchaseReturn->returnedItems()->attach($input->cart_purchase_id, [
                'quantity'      => $input->quantity,
                'reason'        => $input->reason,
                'total'         => $input->quantity * $input->public_price,
                'created_at'    => Carbon::now(),
            ]);
        } else {
            $purchaseReturn = $this->store($validatedRequest);
            return $purchaseReturn->returnedItems()->attach($input->cart_purchase_id, [
                'quantity'      => $input->quantity,
                'reason'        => $input->reason,
                'total'         => $input->quantity * $input->public_price,
                'created_at'    => Carbon::now(),
            ]);
        }
    }

    public function update($id, $input)
    {
        return $this->model->where('id', $id)->update($input);
    }

    public function receiving($input)
    {
        return $this->model->query()->where([
            ['supplier_id_number', '!=', null],
            ['supplier_name', '!=', null],
        ])->applyFilters($input)->with('purchase')->get();
    }

    public function receivingPaginated($input)
    {
        $date_filter_by_day = intval(Setting::getValue('date_filter_by_day'));

        if (isset($input['to']) && !isset($input['from'])) {
            $startDate = Carbon::createFromFormat('Y-m-d', $input['to'])->subDay($date_filter_by_day)->startOfDay();
        } else {
            $startDate = isset($input['from']) ? Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay() : Carbon::now()->subDay($date_filter_by_day)->startOfDay();
        }

        $endDate = isset($input['to']) ? Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay() : Carbon::now()->endOfDay();

        return $this->model->query()->where([
            ['supplier_id_number', '!=', null],
            ['supplier_name', '!=', null],
        ])->applyFilters($input)
            ->when(isset($input['from']) || isset($input['to']), function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->when(isset($input['supplier_id']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('supplier_id', $input['supplier_id']);
                });
            })->when(isset($input['purchase_number']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('purchase_number', $input['purchase_number']);
                });
            })->when(isset($input['warehouse_id']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('warehouse_id', $input['warehouse_id']);
                });
            })->when(isset($input['reviewed_by']), function ($query) use ($input) {
                $query->whereHas('purchase', function ($query) use ($input) {
                    $query->where('reviewed_by', $input['reviewed_by']);
                });
            })->with('returnedItems', 'purchase.warehouse', 'purchase.supplier', 'purchase.reviewedBy', 'createdBy')->paginate();
    }
}

<?php

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Order\Entities\Order;

class TransactionsRepository extends BaseRepository
{
    public function __construct(protected Order $model)
    {
    }

    public function sales($input)
    {
        return $this->model->when(! isset($input['from']), function ($query) {
            $query->where('orders.created_at', '>=', Carbon::today()->startOfMonth());
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->when(isset($input['code']), function ($query) use ($input) {
            $query->whereHas('client', function ($query) use ($input) {
                $query->where('clients.code', $input['code']);
            });
        })->when(isset($input['client_id']), function ($query) use ($input) {
            $query->where('client_id', $input['client_id']);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('pharmacy_id', $input['pharmacy_id']);
        })->applySorts($input)->with('pharmacy', 'createdBy')->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10, ['*'], 'sales_page');
    }
}

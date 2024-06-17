<?php

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Transaction\Entities\TransferredBalance;

class TransferredBalanceRepository extends BaseRepository
{
    public function __construct(protected TransferredBalance $model)
    {
    }

    public function transferred($input)
    {
        return $this->model->when(isset($input['client_id']), function ($query) use ($input) {
            $query->where('client_id', $input['client_id']);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('from_pharmacy_id', $input['pharmacy_id']);
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->applySorts($input)->with('user', 'from_pharmacy')->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10, ['*'], 'balance_transferred_page');
    }

    public function received($input)
    {
        return $this->model->when(isset($input['client_id']), function ($query) use ($input) {
            $query->where('client_id', $input['client_id']);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('to_pharmacy_id', $input['pharmacy_id']);
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->applySorts($input)->with('user', 'to_pharmacy')->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10, ['*'], 'balance_received_page');
    }
}

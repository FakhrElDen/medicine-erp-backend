<?php

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Transaction\Entities\CashPayment;

class CashPaymentRepository extends BaseRepository
{
    public function __construct(protected CashPayment $model)
    {
    }

    public function get($input)
    {
        return $this->model->when(isset($input['client_id']), function ($query) use ($input) {
            $query->where('client_id', $input['client_id']);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('pharmacy_id', $input['pharmacy_id']);
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->applySorts($input)->with('user', 'pharmacy')->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10, ['*'], 'cash_payment_page');
    }
}

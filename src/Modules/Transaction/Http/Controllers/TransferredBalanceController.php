<?php

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Transaction\Http\Requests\TransactionFilterRequest;
use Modules\Transaction\Repositories\TransferredBalanceRepository;
use Modules\Transaction\Transformers\BalanceResourceCollection;

class TransferredBalanceController extends BaseController
{
    protected $TransferredBalanceRepository;

    public function __construct(protected TransferredBalanceRepository $transferredBalanceRepository)
    {
        $this->middleware('permission:client_transactions|sales_employee')->only(['transferredIndex', 'receivedIndex']);
    }

    public function transferredIndex(TransactionFilterRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $data = $this->transferredBalanceRepository->transferred(['client_id' => $user->client_id] + $request->validated());
        } else {
            $data = $this->transferredBalanceRepository->transferred($request->validated());
        }

        return $this->respondResource(new BalanceResourceCollection($data));
    }

    public function receivedIndex(TransactionFilterRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $data = $this->transferredBalanceRepository->received(['client_id' => $user->client_id] + $request->validated());
        } else {
            $data = $this->transferredBalanceRepository->received($request->validated());
        }

        return $this->respondResource(new BalanceResourceCollection($data));
    }
}

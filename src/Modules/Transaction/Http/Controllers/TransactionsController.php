<?php

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Transaction\Http\Requests\NotificationsRequest;
use Modules\Transaction\Http\Requests\ReportsTransactionRequest;
use Modules\Transaction\Http\Requests\TransactionRequest;
use Modules\Transaction\Repositories\NotificationRepository;
use Modules\Transaction\Repositories\TransactionsRepository;
use Modules\Transaction\Transformers\NotificationResourceCollection;
use Modules\Transaction\Transformers\ReportsResource;
use Modules\Transaction\Transformers\TransactionsResourceCollection;

class TransactionsController extends BaseController
{
    public function __construct(
        protected PharmacyRepository $pharmacyRepository,
        protected TransactionsRepository $transactionsRepository,
        protected NotificationRepository $notificationRepository
    ) {
        $this->middleware('permission:client_transactions|sales_employee')->only(['sales', 'notifications', 'reports', 'reportsOwe']);
    }

    public function sales(TransactionRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $sales = $this->transactionsRepository->sales(['client_id' => $user->client_id] + $request->validated());
        } else {
            $sales = $this->transactionsRepository->sales($request->validated());
        }

        return $this->respondResource(new TransactionsResourceCollection($sales));
    }

    public function notifications(NotificationsRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $notifications = $this->notificationRepository->get(['client_id' => $user->client_id] + $request->validated());
        } else {
            $notifications = $this->notificationRepository->get($request->validated());
        }

        return $this->respondResource(new NotificationResourceCollection($notifications));
    }

    public function reports(ReportsTransactionRequest $request)
    {
        $report = $this->pharmacyRepository->reports($request->validated());

        return $this->apiResponse(new ReportsResource($report));
    }

    public function reportsOwe(ReportsTransactionRequest $request)
    {
        $report = $this->pharmacyRepository->reportsOwe($request->validated());

        return $this->apiResponse(new ReportsResource($report));
    }
}

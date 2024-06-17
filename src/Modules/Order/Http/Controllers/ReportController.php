<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Carbon;
use Modules\Client\Repositories\PharmacyRepository;
use Modules\Client\Transformers\PharmacyResourceCollection;
use Modules\Order\Http\Requests\CityCustomerSalesReport;
use Modules\Order\Http\Requests\CitySalesRequest;
use Modules\Order\Http\Requests\ClientSalesReportRequest;
use Modules\Order\Http\Requests\PharmacySalesFilterRequest;
use Modules\Order\Http\Requests\ProductivityRequest;
use Modules\Order\Http\Requests\SalesFilterRequest;
use Modules\Order\Http\Requests\SellerSalesWithHisClientsRequest;
use Modules\Order\Http\Requests\SellerSalesWithNonHisClientsRequests;
use Modules\Order\Http\Requests\TrackProductivityRequest;
use Modules\Order\Http\Requests\TrackSalesRequest;
use Modules\Order\Repositories\ReportRepository;
use Modules\Order\Transformers\ReportResourceCollection;
use Modules\User\Repositories\UserRepository;

class ReportController extends BaseController
{
    protected $from;
    protected $to;

    public function __construct(
        protected UserRepository $userRepository,
        protected ReportRepository $reportRepository,
        protected PharmacyRepository $pharmacyRepository
    ) {
        if (request()->has('to') && !request()->has('from')) {
            $this->from = Carbon::parse(request()->to)->startOfMonth()->format('Y-m-d');
        } else {
            $this->from = request()->has('from') ? request()->from : Carbon::now()->startOfMonth()->format('Y-m-d');
        }

        $this->to = request()->has('to') ? request()->to : Carbon::now()->format('Y-m-d');
    }

    public function salesmanProductivityReport(SalesFilterRequest $request)
    {
        $orders = $this->reportRepository->getSalesmanProductivity(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function salesmanSalesReport(PharmacySalesFilterRequest $request)
    {
        $orders = $this->reportRepository->salesmanSalesReport(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function pharmacySalesReport(ClientSalesReportRequest $request)
    {
        $orders = $this->reportRepository->pharmacySalesReport(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->respondResource(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function sellerSalesWithHisClients(SellerSalesWithHisClientsRequest $request)
    {
        $user_clients = $this->userRepository->getUserClients($request->sales_id);
        $orders = $this->reportRepository->sellerSalesWithHisClients(['from' => $this->from, 'to' => $this->to] + $request->validated(), $user_clients);

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function sellerSalesWithNonHisClients(SellerSalesWithNonHisClientsRequests $request)
    {
        $user_clients = $this->userRepository->getUserClients($request->sales_id);
        $userNonClients = $this->pharmacyRepository->getUserNonClients($user_clients);
        $orders = $this->reportRepository->sellerSalesWithNonHisClients(['from' => $this->from, 'to' => $this->to] + $request->validated(), $userNonClients);

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function governorateProductivity(ProductivityRequest $request)
    {
        $orders = $this->reportRepository->governorateProductivity(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function cityProductivity(ProductivityRequest $request)
    {
        $orders = $this->reportRepository->cityProductivity(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function citySalesReport(CitySalesRequest $request)
    {
        $order = $this->reportRepository->citySalesReport(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($order), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function trackProductivity(TrackProductivityRequest $request)
    {
        $orders = $this->reportRepository->trackProductivity(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function trackSales(TrackSalesRequest $request)
    {
        $order = $this->reportRepository->trackSales(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($order), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function cityCustomerSalesReport(CityCustomerSalesReport $request)
    {
        $orders = $this->reportRepository->cityCustomerSalesReport(['from' => $this->from, 'to' => $this->to] + $request->validated());

        return $this->apiResponse(new ReportResourceCollection($orders), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }

    public function cityCustomerSalesNonDealReport(CityCustomerSalesReport $request)
    {
        $pharmacies_ids = $this->reportRepository->cityCustomerSalesNonDealReport(['from' => $this->from, 'to' => $this->to] + $request->validated());
        $pharmacies = $this->pharmacyRepository->returnPharmaciesWhereNotIn($pharmacies_ids);

        return $this->apiResponse(new PharmacyResourceCollection($pharmacies), additional_data: ['from' => $this->from, 'to' => $this->to]);
    }
}

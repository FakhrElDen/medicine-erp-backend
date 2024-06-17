<?php

namespace Modules\Order\Repositories;

use Modules\Order\Entities\Order;
use App\Repositories\BaseRepository;

class ReportRepository extends BaseRepository
{
    public function __construct(protected Order $model)
    {
    }

    public function getSalesmanProductivity($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereSalesId(isset($input['user_id']) ? $input['user_id'] : null)
            ->selectSalesRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectPercentageSalesRaw()
            ->with('pharmacy', 'createdBy', 'sales')
            ->leftJoin('users', 'users.id', '=', 'orders.sales_id')
            ->leftJoin('listing_user', 'orders.sales_id', '=', 'listing_user.user_id')
            ->leftJoin('listings', 'listings.id', 'listing_user.listing_id')
            ->leftJoin('listing_pharmacy', function ($join) {
                $join->on('listing_pharmacy.pharmacy_id', 'orders.pharmacy_id')
                    ->on('listing_pharmacy.listing_id', 'listings.id');
            })
            ->selectRelatedClientsRaw()
            ->selectNonRelatedClientsRaw()
            ->groupBySales()
            ->get();
    }

    public function salesmanSalesReport($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereSalesId($input['user_id'])
            ->selectPharmacyRaw()
            ->selectSalesRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectPercentageSalesRaw()
            ->selectClientSalesPercentageRaw($input['user_id'])
            ->groupByPharmacy()
            ->groupBySales()
            ->with('pharmacy', 'sales', 'createdBy')
            ->get();
    }

    public function pharmacySalesReport($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->wherePharmacyId(isset($input['pharmacy_id']) ? $input['pharmacy_id'] : null)
            ->with('client', 'pharmacy')
            ->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function sellerSalesWithHisClients($input, $user_clients)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereIn('pharmacy_id', $user_clients)
            ->whereSalesId($input['sales_id'])
            ->selectPharmacyRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->groupByPharmacy()
            ->with('pharmacy')
            ->get();
    }

    public function sellerSalesWithNonHisClients($input, $userNonClients)
    {

        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereIn('pharmacy_id', $userNonClients)
            ->whereSalesId($input['sales_id'])
            ->selectPharmacyRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->groupByPharmacy()
            ->with('pharmacy')
            ->get();
    }

    public function governorateProductivity($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereCityId(isset($input['city_id']) ? $input['city_id'] : null)
            ->selectCityRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectPercentageCitySalesRaw()
            ->groupByCity()
            ->with('city')
            ->get();
    }

    public function cityProductivity($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereCityId(isset($input['city_id']) ? $input['city_id'] : null)
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectAreaRaw()
            ->selectCityRaw()
            ->selectPercentageCitySalesRaw()
            ->selectPercentageSalesRaw()
            ->groupByArea()
            ->groupByCity()
            ->with('area', 'city')
            ->get();
    }

    public function citySalesReport($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereAreaId($input['area_id'])
            ->selectPharmacyRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectPercentageAreaSalesRaw($input['area_id'])
            ->selectPercentageSalesRaw()
            ->groupByArea()
            ->groupByPharmacy()
            ->with('pharmacy')
            ->get();
    }

    public function trackProductivity($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereTrackId(isset($input['track_id']) ? $input['track_id'] : null)
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectTrackRaw()
            ->selectCityRaw()
            ->selectPercentageCitySalesRaw()
            ->selectPercentageSalesRaw()
            ->groupByTrack()
            ->groupByCity()
            ->with('track')
            ->get();
    }

    public function trackSales($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereTrackId($input['track_id'])
            ->selectPharmacyRaw()
            ->selectOrdersCountRaw()
            ->selectTotalSumRaw()
            ->selectReturnSumRaw()
            ->selectPercentageSalesRaw()
            ->selectPercentageTrackSalesRaw($input['track_id'])
            ->groupByTrack()
            ->groupByPharmacy()
            ->with('pharmacy')
            ->get();
    }

    public function cityCustomerSalesReport($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->whereTrackId(isset($input['track_id']) ? $input['track_id'] : null)
            ->whereAreaId(isset($input['area_id']) ? $input['area_id'] : null)
            ->whereCityId(isset($input['city_id']) ? $input['city_id'] : null)
            ->selectPharmacyRaw()
            ->selectOrdersCountRaw()
            ->groupByPharmacy()
            ->with('pharmacy', 'pharmacy.area', 'pharmacy.lastInvoice')
            ->get();
    }

    public function cityCustomerSalesNonDealReport($input)
    {
        return $this->model
            ->whereCreatedAt($input['from'], $input['to'])
            ->groupByPharmacy()
            ->pluck('pharmacy_id');
    }
}

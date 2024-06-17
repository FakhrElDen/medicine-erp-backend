<?php

namespace Modules\Order\Traits;

trait ReportQueryBuilder
{
    public function scopeWhereCreatedAt($query, $from, $to)
    {
        return $query->when(isset($from) || isset($to), function ($query) use ($from, $to) {
            $query->whereBetween('orders.created_at', [$from, $to]);
        });
    }

    public function scopeWherePharmacyId($query, $pharmacy_id)
    {
        return $query->when(isset($pharmacy_id) && $pharmacy_id != null, function ($query) use ($pharmacy_id) {
            $query->where('pharmacy_id', $pharmacy_id);
        });
    }

    public function scopeSelectPharmacyRaw($query)
    {
        return $query->selectRaw('pharmacy_id');
    }

    public function scopeGroupByPharmacy($query)
    {
        return $query->groupBy('pharmacy_id');
    }

    public function scopeWhereSalesId($query, $sales_id)
    {
        if ($sales_id == 'without-salesman') {
            $query->whereNull('orders.sales_id')
                ->whereDoesntHave(
                    'createdBy.roles',
                    fn ($q) => $q->where('roles.name', '=', 'sales_employee')
                );
        } elseif (!empty($sales_id)) {
            $query->where('sales_id', $sales_id)->orWhere(function ($query) use ($sales_id) {
                $query->whereNull('sales_id')->where('created_by', $sales_id);
            });
        }
    }

    public function scopeSelectSalesRaw($query)
    {
        return $query->selectRaw('sales_id');
    }

    public function scopeSelectRelatedClientsRaw($query)
    {
        return $query->selectRaw('COUNT(DISTINCT listing_pharmacy.pharmacy_id) AS related_clients_count');
    }

    public function scopeSelectNonRelatedClientsRaw($query)
    {
        return $query->selectRaw('COUNT(DISTINCT orders.pharmacy_id) AS non_related_clients_count');
    }

    public function scopeGroupBySales($query)
    {
        return $query->groupBy('sales_id');
    }

    public function scopeWhereCityId($query, $city_id)
    {
        return $query->when(isset($city_id) && $city_id != null, function ($query) use ($city_id) {
            $query->where('city_id', $city_id);
        });
    }

    public function scopeSelectCityRaw($query)
    {
        return $query->selectRaw('city_id');
    }

    public function scopeGroupByCity($query)
    {
        return $query->groupBy('city_id');
    }

    public function scopeWhereAreaId($query, $area_id)
    {
        return $query->when(isset($area_id) && $area_id != null, function ($query) use ($area_id) {
            $query->where('area_id', $area_id);
        });
    }

    public function scopeSelectAreaRaw($query)
    {
        return $query->selectRaw('area_id');
    }

    public function scopeGroupByArea($query)
    {
        return $query->groupBy('area_id');
    }

    public function scopeWhereTrackId($query, $track_id)
    {
        return $query->when(isset($track_id) && $track_id != null, function ($query) use ($track_id) {
            $query->where('track_id', $track_id);
        });
    }

    public function scopeSelectTrackRaw($query)
    {
        return $query->selectRaw('track_id');
    }

    public function scopeGroupByTrack($query)
    {
        return $query->groupBy('track_id');
    }

    public function totalSales()
    {
        return $this->sum('total');
    }

    public function scopeSelectOrdersCountRaw($query)
    {
        return $query->selectRaw('COUNT(*) AS orders_count');
    }

    public function scopeSelectTotalSumRaw($query)
    {
        return $query->selectRaw('SUM(total) AS total');
    }

    public function scopeSelectReturnSumRaw($query)
    {
        return $query->selectRaw('SUM(returns) AS returns');
    }

    public function scopeTotalWhereSalesId($query, $salesId)
    {
        return $query->whereSalesId($salesId)->sum('total');
    }

    public function scopeTotalWhereAreaId($query, $areaId)
    {
        return $query->whereAreaId($areaId)->sum('total');
    }

    public function scopeTotalWhereTrackId($query, $trackId)
    {
        return $query->whereTrackId($trackId)->sum('total');
    }

    public function scopeSelectPercentageSalesRaw($query)
    {
        $totalSales = $this->totalSales();

        return $query->selectRaw('SUM(total) * 100 / ' . $totalSales . ' AS percentage_sales');
    }

    public function scopeSelectClientSalesPercentageRaw($query, $salesId)
    {
        $totalWhereSalesId = $this->totalWhereSalesId($salesId);

        return $query->selectRaw('SUM(total) * 100 / ' . $totalWhereSalesId . ' AS client_sales_percentage');
    }

    public function scopeSelectPercentageCitySalesRaw($query)
    {
        $totalCitySales = 'SELECT SUM(`total`) FROM `orders` AS city_orders WHERE `city_orders`.`city_id` = `orders`.`city_id`';

        return $query->selectRaw('SUM(total) * 100 / (' . $totalCitySales . ') AS percentage_city_sales');
    }

    public function scopeSelectPercentageAreaSalesRaw($query, $area_id)
    {
        $totalSales = $this->totalWhereAreaId($area_id);

        return $query->selectRaw('SUM(total) * 100 / (' . $totalSales . ') AS percentage_area_sales');
    }

    public function scopeSelectPercentageTrackSalesRaw($query, $track_id)
    {
        $totalSales = $this->totalWhereTrackId($track_id);

        return $query->selectRaw('SUM(total) * 100 / (' . $totalSales . ') AS percentage_track_sales');
    }
}

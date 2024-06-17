<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Order\Transformers\ReportResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $city_count = $this->collection->pluck('city_id')->unique()->count();
        if ($city_count == null || $city_count == 0) {
            $city_count = 1;
        }
        // $returns_sum = $this->collection->sum('returns');
        $total_price = $this->collection->sum('total');
        $total_after_extra_discount_sum = $this->collection->sum('total_after_extra_discount');
        $orders_count_sum = $this->collection->sum('orders_count');
        $percentage_sales_sum = $this->collection->sum('percentage_sales');
        $client_sales_percentage_sum = $this->collection->sum('client_sales_percentage');
        $percentage_city_sales_sum = $this->collection->sum('percentage_city_sales') / $city_count;
        $percentage_area_sales_sum = $this->collection->sum('percentage_area_sales');
        $percentage_track_sales_sum = $this->collection->sum('percentage_track_sales');
        $related_clients_count_sum = $this->collection->sum('related_clients_count');
        $non_related_clients_count_sum = $this->collection->sum('non_related_clients_count');
        $target_sum = $this->collection->sum('target');
        $target_percentage_sum = $this->collection->sum('percentage_target');
        $salesmen_count = $this->collection->count('*');

        // $this->collection->transform(function ($group) {
        //     if ($group->sales_id) {
        //         return $group;
        //     }
        //     $sales = $group->sales()->getRelated()->setAttribute('name', trans('order::other.without-salesman'));

        //     return $group->setRelation('sales', $sales);
        // });

        return [
            'data'                              => $this->collection,
            'salesmen_count'                    => $salesmen_count,
            // 'returns_sum'                       => $returns_sum,
            'sales_sum'                         => round($total_price + $total_after_extra_discount_sum, 2),
            // 'net_sales_sum'                     => round($total_price - $returns_sum, 2),
            'percentage_sales_sum'              => round($percentage_sales_sum, 2),
            'client_sales_percentage_sum'       => round($client_sales_percentage_sum, 2),
            'percentage_city_sales_sum'         => round($percentage_city_sales_sum, 2),
            'percentage_area_sales_sum'         => round($percentage_area_sales_sum, 2),
            'percentage_track_sales_sum'        => round($percentage_track_sales_sum, 2),
            'orders_count_sum'                  => $orders_count_sum,
            'related_clients_count_sum'         => $related_clients_count_sum,
            'non_related_clients_count_sum'     => $non_related_clients_count_sum,
            'target_sum'                        => $target_sum,
            'target_percentage_sum'             => $target_percentage_sum,
        ];
    }
}

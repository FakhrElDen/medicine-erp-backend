<?php

namespace Modules\Order\Transformers;

use Modules\Area\Transformers\AreaResource;
use Modules\Area\Transformers\CityResource;
use Modules\User\Transformers\UserResource;
use Modules\Track\Transformers\TrackResource;
use Modules\Client\Transformers\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Transformers\PharmacyResource;

class ReportResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'                              => $this->id,
      'total_price'                     => $this->total_price,
      'total_quantity'                  => $this->total_quantity,
      'total_taxes'                     => $this->total_taxes,
      'total'                           => round($this->total + $this->total_after_extra_discount, 1),
      // 'returns'                         => intval($this->returns),
      // 'net_sales'                       => $this->total - $this->returns,
      'orders_count'                    => $this->orders_count ?? null,
      'order_number'                    => $this->order_number ?? null,
      'pharmacies_count'                => $this->pharmacies_count ?? null,
      'related_clients_count'           => $this->related_clients_count ?? null,
      'non_related_clients_count'       => $this->non_related_clients_count ?? null,
      'percentage_sales'                => round($this->percentage_sales, 2) ?? null,
      'percentage_area_sales'           => round($this->percentage_area_sales, 2) ?? null,
      'percentage_track_sales'          => round($this->percentage_track_sales, 2) ?? null,
      'percentage_city_sales'           => round($this->percentage_city_sales, 2) ?? null,
      'client_sales_percentage'         => round($this->client_sales_percentage, 2) ?? null,
      'percentage_target'               => intval($this->percentage_target),
      'created_at'                      => $this->created_at,
      'pharmacy'                        => new PharmacyResource($this->whenLoaded('pharmacy')),
      'city'                            => new CityResource($this->whenLoaded('city')),
      'area'                            => new AreaResource($this->whenLoaded('area')),
      'track'                           => new TrackResource($this->whenLoaded('track')),
      'sales'                           => new UserResource($this->whenLoaded('sales')),
      'client'                          => new ClientResource($this->whenLoaded('client')),
      'created_by'                      => new UserResource($this->whenLoaded('createdBy')),
    ];
  }
}

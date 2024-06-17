<?php

namespace Modules\Order\Repositories;

use App\Events\BulkPreparationOrdersCount;
use App\Events\PreparationOrdersCount;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Order\DTOs\GetOrdersDTO;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Warehouse\Entities\Corridor;
use Modules\Warehouse\Enums\WarehouseType;

class OrderRepository extends BaseRepository
{
    public function __construct(protected Order $model)
    {
    }

    /**
     * if the pharmacy has open order will return it
     * else will create one
     */
    public function store($input, $pharmacy, $invoice_number)
    {
        $order = $this->returnOpenOrder($pharmacy->id);

        if ($order) {
            return $order;
        }

        $input['city_id'] = $pharmacy->city_id;
        $input['track_id'] = $pharmacy->track_id;
        $input['area_id'] = $pharmacy->area_id;
        $input['latitude'] = $pharmacy->latitude;
        $input['longitude'] = $pharmacy->longitude;
        $input['status'] = OrderStatus::EDITABLE;
        $input['order_number'] = $invoice_number;

        $order = $this->model->create($input);

        return $order;
    }

    public function returnOpenOrder($pharmacy_id)
    {
        return $this->model->where('pharmacy_id', $pharmacy_id)
            ->where('status', OrderStatus::EDITABLE)->first();
    }

    /**
     * update an order and send a notification to the warehouse to prepare it.
     */
    public function checkout($input)
    {
        $order = $this->model->where('id', $input['order_id']);
        $input['closed_at'] = Carbon::now();
        unset($input['order_id']);

        $input['status'] = OrderStatus::IN_PREPARING;
        $order->update($input);

        $order = $order->with('pharmacy')->first();
        $corridors_ids = $order->cart->pluck('corridor_id');
        $order->corridors()->attach($corridors_ids);

        if ($order->warehouse->type == WarehouseType::SALES) {
            $orders_count = Redis::incrby('bulk_preparation', 1);
            event(new BulkPreparationOrdersCount($orders_count, $order, 'added'));
        } elseif ($order->warehouse->type == WarehouseType::MAIN) {
            $totals_corridor_id = Corridor::where('is_main_corridor', 1)->first()?->id;

            foreach ($corridors_ids->push($totals_corridor_id) as $corridor_id) {
                $orders_count[$corridor_id] = Redis::incrby("preparation.{$corridor_id}", 1);
            }

            event(new PreparationOrdersCount($orders_count, $order, 'added'));
        }
    }

    public function get(GetOrdersDTO $input)
    {
        return $this->model->query()->applyFilters($input->whereNotNull())->applySorts($input)
            ->when($input->has('from') && $input->has('to'), function ($query) use ($input) {
                $query->whereBetween('created_at', [
                    $input->from->startOfDay(),
                    $input->to->endOfDay()
                ]);
            })->when(isset($input->payment_type), function ($query) use ($input) {
                $query->whereHas('pharmacy', function ($query) use ($input) {
                    $query->where('pharmacies.payment_type', $input->payment_type);
                });
            })->when(isset($input->warehouse_id), function ($query) use ($input) {
                $query->whereHas('cart', function ($query) use ($input) {
                    $query->where('carts.warehouse_id', $input->warehouse_id);
                });
            })->when(isset($input->product_id), function ($query) use ($input) {
                $query->whereHas('cart', function ($query) use ($input) {
                    $query->where('carts.product_id', $input->product_id);
                });
            })->when(isset($input->operating_number), function ($query) use ($input) {
                $query->whereHas('cart.batches', function ($query) use ($input) {
                    $query->where('batches.operating_number', $input->operating_number);
                });
            })->when(isset($input->expired_at), function ($query) use ($input) {
                $query->whereHas('cart.batches', function ($query) use ($input) {
                    $query->whereRaw("DATE_FORMAT(expired_at, '%Y-%m') = ?", $input->expired_at);
                });
            })->with([
                'city',
                'area',
                'shift',
                'track',
                'sales',
                'client',
                'createdBy',
                'pharmacy',
                'delivery',
                'cart' => [
                    'batches',
                    'product',
                ],
            ])->paginate();
    }

    public function getInventoried($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->withWhereHas('cart.batches', function ($query) use ($input) {
                $query->where('cart_sub_batch.status', CartSubBatchStatus::INVENTORIED)
                    ->when(isset($input['operating_number']) && $input['operating_number'] != 'null', function ($query) use ($input) {
                        $query->whereHas('cart.batches', function ($query) use ($input) {
                            $query->where('operating_number', $input['operating_number']);
                        });
                    })->when(isset($input['expired_at']) && $input['expired_at'] != 'null', function ($query) use ($input) {
                        $query->whereHas('cart', function ($query) use ($input) {
                            $query->where('expired_at', $input['expired_at']);
                        });
                    });
            })->when(isset($input['from']) && isset($input['to']), function ($query) use ($input) {
                $query->whereBetween('created_at', [
                    Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(),
                    Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()
                ]);
            })->when(isset($input['client_id']), function ($query) use ($input) {
                $query->where('client_id', $input['client_id']);
            })->when(isset($input['payment_type']), function ($query) use ($input) {
                $query->whereHas('pharmacy', function ($query) use ($input) {
                    $query->where('pharmacies.payment_type', $input['payment_type']);
                });
            })->when(isset($input['warehouse_id']), function ($query) use ($input) {
                $query->whereHas('cart', function ($query) use ($input) {
                    $query->where('carts.warehouse_id', $input['warehouse_id']);
                });
            })->when(isset($input['product_id']), function ($query) use ($input) {
                $query->whereHas('cart', function ($query) use ($input) {
                    $query->where('carts.product_id', $input['product_id']);
                });
            })->with([
                'city',
                'area',
                'shift',
                'track',
                'sales',
                'client',
                'createdBy',
                'pharmacy',
                'cart.product',
                'delivery'
            ])->paginate();
    }

    public function invoices($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->when(isset($input['basket_number']), function ($query) use ($input) {
                $query->whereHas('baskets', function ($query) use ($input) {
                    $query->where('number', $input['basket_number']);
                });
            })->when(isset($input['sorted_by']), function ($query) use ($input) {
                $query->orderBy($input['sorted_by'], 'desc');
            })->with(['cart.product', 'client', 'pharmacy'])->get();
    }

    public function listingPreparedRetail($input)
    {
        $query = $this->model->query()->applyFilters($input)->applySorts($input)->where(function ($query) {
            $query->where('orders.status', OrderStatus::PREPARED_AND_NON_INVENTORY)
                ->orWhere('orders.status', OrderStatus::INVENTORIED);
        })->whereHas('warehouse', function ($query) {
            $query->where('type', WarehouseType::MAIN);
        })->when(empty($input), function ($query) {
            $query->whereDate('orders.completed_at', Carbon::today()->startOfDay());
        })->when(isset($input['basket_number']), function ($query) use ($input) {
            $query->withWhereHas('baskets', function ($query) use ($input) {
                $query->where('number', $input['basket_number']);
            });
        })->when(isset($input['prepared_by']), function ($query) use ($input) {
            $query->withWhereHas('cart', function ($query) use ($input) {
                $query->where('prepared_by', $input['prepared_by']);
            });
        });

        $counts = $this->getOrderCountsPerCorridor($query);

        $orders = $query->when(isset($input['corridor_id']), function ($query) use ($input) {
            $query->whereHas('cart.batches', function ($query) use ($input) {
                $query->where('corridor_id', $input['corridor_id']);
            })->with([
                'corridors' => function ($query) use ($input) {
                    $query->where('corridor_id', $input['corridor_id']);
                },
            ]);
        })->when(!isset($input['corridor_id']), function ($query) {
            $query->with(['corridors' => function ($query) {
                $query->whereNotNull('completed_at');
            }, 'corridors.baskets']);
        })->with([
            'pharmacy',
            'cart' => [
                'batches.corridor',
                'product.manufacturer',
                'preparedBy'
            ],
        ])->get();

        return ['orders' => $orders, 'counts' => $counts];
    }

    public function viewPreparingRetail($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->where(function ($query) {
                $query->where('orders.status', OrderStatus::PREPARED_AND_NON_INVENTORY)
                    ->orWhere('orders.status', OrderStatus::IN_PREPARING);
            })->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::MAIN);
            })->when(empty($input), function ($query) {
                $query->whereDate('orders.completed_at', Carbon::today()->startOfDay());
            })->when(isset($input['basket_number']), function ($query) use ($input) {
                $query->WhereHas('baskets', function ($query) use ($input) {
                    $query->where('baskets.number', $input['basket_number']);
                });
            })->when(isset($input['prepared_by']), function ($query) use ($input) {
                $query->withWhereHas('cart', function ($query) use ($input) {
                    $query->where('prepared_by', $input['prepared_by']);
                });
            })->with([
                'pharmacy',
                'cart' => [
                    'batches.corridor',
                    'product.manufacturer',
                    'preparedBy',
                    'preparedBy',
                ],
                'corridors.baskets',
                'baskets'
            ])->first();
    }

    public function viewPreparedRetail($input)
    {
        $local = app()->getLocale();

        return $this->model->where('id', $input['invoice_id'])
            ->with([
                'pharmacy',
                'invoice.printedBy',
                'baskets',
                'corridors.baskets' => function ($query) use ($input) {
                    if (isset($input['invoice_id'])) {
                        $query->where('order_id', $input['invoice_id']);
                    }
                },
                'cart' => function ($query) use ($input, $local) {
                    match ($input['sort_by'] ?? null) {
                        'product_name' => $query->sortByProductName($local),
                        'manufacturer_name' => $query->sortByManufacturerName($local),
                        'corridor' => $query->sortByCorridor(),
                        default => null,
                    };
                    $query->with([
                        'corridor',
                        'product.manufacturer',
                        'batches' => function ($query) use ($input) {
                            if (isset($input['corridor_id'])) {
                                $query->where('corridor_id', $input['corridor_id']);
                            } else {
                                $query->with('corridor');
                            }
                        },
                    ]);
                },
            ])->first();
    }

    public function viewPreparedBulk($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::SALES);
            })->with([
                'pharmacy',
                'warehouse',
                'invoice.printedBy',
                'cart' => [
                    'corridor',
                    'product.manufacturer',
                    'batches.corridor',
                ],
            ])->first();
    }

    public function unpreparedInvoices($input)
    {
        $query = $this->model->where('orders.status', OrderStatus::IN_PREPARING)
            ->whereHas('warehouse', function ($query) use ($input) {
                $query->where('type', $input['warehouse_type']);
            })->when(isset($input['basket_number']), function ($query) use ($input) {
                $query->whereHas('baskets', function ($query) use ($input) {
                    $query->where('number', $input['basket_number']);
                });
            });

        $counts = match ((int) $input['warehouse_type']) {
            WarehouseType::MAIN => $this->getOrderCountsPerCorridor($query),
            WarehouseType::SALES => $query->count(),
        };

        $this->setRealtimeOrdersCountsPerCorridor($counts, $input['warehouse_type']);

        $orders = $query->when(isset($input['corridor_id']), function ($query) use ($input) {
            $query->whereHas('cart.batches', function ($query) use ($input) {
                $query->where('corridor_id', $input['corridor_id'])->where('completed_at', null);
            });
        })->when(isset($input['warehouse_id']), function ($query) use ($input) {
            $query->whereHas('warehouse', function ($query) use ($input) {
                $query->where('warehouse_id', $input['warehouse_id'])->where('completed_at', null);
            });
        })->with('pharmacy')->get();

        return ['orders' => $orders, 'counts' => $counts];
    }

    public function viewUnpreparedRetail($input)
    {
        return $this->model->where('status', OrderStatus::IN_PREPARING)->where('id', $input['invoice_id'])
            ->with([
                'pharmacy',
                'invoice',
                'cart.corridor',
                'cart.product.manufacturer',
                'cart' => function ($query) use ($input) {
                    if (isset($input['sort_by'])) {
                        if ($input['sort_by'] == 'product_name') {
                            $query->leftJoin('products', 'products.id', '=', 'carts.product_id')
                                ->select('carts.*', 'products.name')
                                ->orderBy('products.name->ar', $input['direction'] ?? 'desc');
                        } elseif ($input['sort_by'] == 'manufacturer_name') {
                            $query->leftJoin('products', 'products.id', '=', 'carts.product_id')
                                ->leftJoin('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
                                ->select('carts.*', 'manufacturers.name')
                                ->orderBy('manufacturers.name->ar', $input['direction'] ?? 'desc');
                        } elseif ($input['sort_by'] == 'corridor') {
                            $query->leftJoin('corridors', 'corridors.id', '=', 'carts.corridor_id')
                                ->select('carts.*', 'corridors.number')
                                ->orderBy('corridors.number', $input['direction'] ?? 'desc');
                        }
                    }
                },
                'cart.batches' => function ($query) use ($input) {
                    if (isset($input['corridor_id'])) {
                        $query->where('corridor_id', $input['corridor_id']);
                    }
                },
                'corridors.baskets' => function ($query) use ($input) {
                    $query->where('order_id', $input['invoice_id']);
                },
            ])->first();
    }

    public function viewReviewedRetail($input)
    {
        return $this->model->where('status', OrderStatus::INVENTORIED)
            ->where('id', $input['invoice_id'])
            ->with([
                'createdBy',
                'client',
                'invoice.printedBy',
                'pharmacy' => [
                    'city',
                    'track',
                ],
                'cart' => [
                    'product',
                    'batches',
                    'corridor',
                ],
            ])->first();
    }

    public function listingReviewedRetail()
    {
        return $this->model->query()->applyFilters()->applySorts()
            ->where('status', OrderStatus::INVENTORIED)
            ->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::MAIN);
            })->with(['cart', 'invoice.printedBy', 'pharmacy', 'reviewedBy'])->get();
    }

    public function listingReviewingRetail($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->where('orders.status', OrderStatus::PREPARED_AND_NON_INVENTORY)
            ->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::MAIN);
            })->with(['invoice.printedBy', 'pharmacy'])->get();
    }

    public function inventoryingOrder($input)
    {
        $order = $this->find($input['order_id']);
        $order->baskets()->delete();
        $order->update([
            'status' => OrderStatus::INVENTORIED,
            'reviewed_by' => auth()->id(),
        ]);

        return $order;
    }

    public function followUp($input)
    {
        return $this->model->where('order_number', $input['order_number'])
            ->where('pharmacy_id', $input['pharmacy_id'])
            ->whereDate('created_at', $input['created_at'])
            ->with('city', 'area', 'shift', 'track', 'sales', 'client', 'createdBy', 'pharmacy')
            ->first();
    }

    public function checkOrderTOComplete($input, $cart, $corridor = null)
    {
        $order = $this->model->where('id', $input['order_id'])->first()->load('cart.batches');

        $orders_count = [];

        if ($corridor->is_main_corridor == 1 && $order->warehouse->type == WarehouseType::MAIN) {
            return $this->prepareOrderFromMainCorridor($order, $cart, $corridor->id);
        } elseif ($order->warehouse->type == WarehouseType::SALES) {
            return $this->prepareBulkOrder($order, $corridor->id);
        }

        $orders_count[$input['corridor_id']] = Redis::decrby("preparation.{$input['corridor_id']}", 1);

        $order->corridors()->updateExistingPivot($input['corridor_id'], [
            'completed_at' => Carbon::now(),
            'completed_by' => auth()->id(),
        ]);

        $all_corridors_prepared = true;
        foreach ($order->cart as $cart) {
            foreach ($cart->batches as $batch) {
                if ($batch->pivot->status == CartSubBatchStatus::IN_PROGRESS || $batch->pivot->completed_at == null) {
                    $all_corridors_prepared = false;
                    break;
                }
            }
        }

        if (!$all_corridors_prepared) {
            event(new PreparationOrdersCount($orders_count, $order, 'removed'));

            return false;
        }

        $totals_corridor_id = Corridor::where('is_main_corridor', 1)->first()?->id;
        $orders_count[$totals_corridor_id] = Redis::decrby("preparation.{$totals_corridor_id}", 1);

        event(new PreparationOrdersCount($orders_count, $order, 'removed'));

        return $this->updateWholeOrder($order);
    }

    private function prepareOrderFromMainCorridor($order, $cart, $main_corridor_id)
    {
        $corridors_ids = $cart->pluck('corridor_id')->unique();
        $corridors_ids->push($main_corridor_id);

        foreach ($corridors_ids as $id) {
            $orders_count[$id] = Redis::decrby("preparation.$id", 1);
        }

        $order->corridors()->whereIn('corridor_id', $corridors_ids)
            ->get()->map(function ($item) use ($main_corridor_id) {
                $pivot = $item->pivot;
                $pivot->completed_at = Carbon::now();
                $pivot->corridor_id = $main_corridor_id;

                return $pivot->save();
            });

        event(new PreparationOrdersCount($orders_count, $order, 'removed'));

        return $this->updateWholeOrder($order);
    }

    private function prepareBulkOrder($order, $corridor_id)
    {
        $orders_count[$corridor_id] = Redis::decrby("preparation.$corridor_id", 1);

        $order->corridors()->where('corridor_id', $corridor_id)
            ->get()->map(function ($item) {
                $pivot = $item->pivot;
                $pivot->completed_at = Carbon::now();

                return $pivot->save();
            });

        event(new BulkPreparationOrdersCount(Arr::first($orders_count), $order, 'added'));

        return $this->updateWholeOrder($order);
    }

    public function updateWholeOrder($order)
    {
        return $order->update([
            'status' => OrderStatus::PREPARED_AND_NON_INVENTORY,
            'completed_at' => Carbon::now(),
            'prepared_by' => auth()->id(),
        ]);
    }

    public function listingPreparedBulk($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->where(function ($query) {
                $query->where('orders.status', OrderStatus::PREPARED_AND_NON_INVENTORY)
                    ->orWhere('orders.status', OrderStatus::INVENTORIED);
            })->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::SALES);
            })->with(['pharmacy', 'invoice'])->get();
    }

    public function listingReviewingBulk($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->where('orders.status', OrderStatus::PREPARED_AND_NON_INVENTORY)
            ->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::SALES);
            })->with(['pharmacy', 'invoice'])->get();
    }

    public function listingReviewedBulk($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->where('status', OrderStatus::INVENTORIED)
            ->whereHas('warehouse', function ($query) {
                $query->where('type', WarehouseType::SALES);
            })->with(['invoice.printedBy', 'pharmacy'])->paginate();
    }

    public function find($order_id)
    {
        return $this->model->find($order_id);
    }

    public function findInventoried($order_id)
    {
        return $this->model->with('city', 'area', 'shift', 'track', 'sales', 'client', 'createdBy', 'pharmacy', 'warehouse', 'cart.product', 'delivery')
            ->withWhereHas('cart.batches', fn ($query) => $query->where('cart_sub_batch.status', CartSubBatchStatus::INVENTORIED))
            ->find($order_id);
    }

    private function getOrderCountsPerCorridor($query)
    {
        $orders = $query->clone()
            ->join('carts', 'carts.order_id', '=', 'orders.id')
            ->distinct(['orders.id', 'carts.corridor_id'])
            ->get(['orders.id', 'carts.corridor_id']);

        $orders_by_corridor = $orders->groupBy('corridor_id');

        return Corridor::get()->map(function ($corridor) use ($orders, $orders_by_corridor) {
            return [
                'corridor_id' => $corridor->id,
                'count' => $corridor->is_main_corridor
                    ? $orders->unique('id')->count()
                    : ($orders_by_corridor[$corridor->id] ?? collect())->count(),
            ];
        });
    }

    private function setRealtimeOrdersCountsPerCorridor($counts, $warehouse_type)
    {
        return match ((int) $warehouse_type) {
            WarehouseType::MAIN => $counts->map(
                fn ($item) => Redis::set('preparation.' . $item['corridor_id'], $item['count'])
            ),
            WarehouseType::SALES => Redis::set('bulk_preparation', $counts)
        };
    }

    public function updateOrderAfterSettlement($order, $totals)
    {
        $order->update([
            'total_price' => $totals['total'],
            'total_taxes' => $totals['taxes'],
            'extra_discount' => $totals['extra_discount'],
            'extra_discount_condition' => $totals['extra_discount_condition'],
            'total_after_extra_discount' => $totals['extra_discount_value'],
            'total' => $totals['price'],
        ]);

        return $order;
    }
}

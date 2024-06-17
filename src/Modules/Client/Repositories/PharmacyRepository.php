<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Client\Entities\Pharmacy;
use Modules\Client\Enums\DayShifts;
use Modules\Client\Enums\DiscountSlatType;
use Modules\Client\Enums\ExtraDiscount;
use Modules\Client\Enums\PaymentPeriod;
use Modules\Client\Enums\PaymentType;
use Modules\Client\Enums\PharmacyShiftType;
use Modules\Client\Enums\PharmacyStatus;
use Modules\Listing\Enums\ListingType;
use Modules\Order\Enums\ReturnsReasons;
use Modules\Product\Enums\ProductManufacturingType;

class PharmacyRepository extends BaseRepository
{
    public function __construct(protected Pharmacy $model)
    {
    }

    public function get($input)
    {
        $data = $this->model->query()->applyFilters($input)->applySorts($input)
            ->when(isset($input['client_id']), function ($query) use ($input) {
                $query->whereHas('clients', function ($query) use ($input) {
                    $query->where('clients.id', $input['client_id']);
                });
            })->when(isset($input['client_type']), function ($query) use ($input) {
                $query->whereHas('clients', function ($query) use ($input) {
                    $query->where('type', $input['client_type']);
                });
            })->when(isset($input['client_code']), function ($query) use ($input) {
                $query->whereHas('clients', function ($query) use ($input) {
                    $query->where('code', $input['client_code']);
                });
            })->when(isset($input['listing_id']), function ($query) use ($input) {
                $query->whereHas('lists', function ($query) use ($input) {
                    $query->where('listings.id', $input['listing_id']);
                });
            })->when(isset($input['night_sales_id']), function ($query) use ($input) {
                $query->whereHas('lists.users', function ($query) use ($input) {
                    $query->where('listing_user.user_id', $input['night_sales_id']);
                });
            })->when(isset($input['morning_sales_id']), function ($query) use ($input) {
                $query->whereHas('lists.users', function ($query) use ($input) {
                    $query->where('listing_user.user_id', $input['morning_sales_id']);
                });
            })->with('clients', 'track', 'track.shifts', 'city', 'area', 'lists', 'lists.users', 'track.users', 'delivery');

        if (isset($input['pagination_number'])) {
            return $data->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
        } else {
            return $data->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * increment pharmacy balance by the total price
     * the balance is the debt on the pharmacy
     */
    public function updateBalance($input)
    {
        return $this->model->where('id', $input['pharmacy_id'])->increment('balance', $input['total_price']);
    }

    public function getUserNonClients($user_clients)
    {
        if ($user_clients == [0]) {
            return $this->model->pluck('id');
        } else {
            return $this->model->whereNotIn('id', $user_clients)->pluck('id');
        }
    }

    public function returnPharmaciesWhereNotIn($pharmacies_ids)
    {
        return $this->model->whereNotIn('id', $pharmacies_ids)->with('area', 'lists.users')->get();
    }

    public function create($input)
    {
        return $this->model->create($input);
    }

    public function deletingMedia($input)
    {
        $pharmacy = $this->find($input['pharmacy_id']);

        return $this->deleteMedia($input['media_id'], $pharmacy);
    }

    public function updatePharmacy($input, $id)
    {
        $pharmacy = $this->model->find($id);

        $pharmacy->update($input);

        return $pharmacy;
    }

    public function addExtraDiscount($input)
    {
        return $this->model->query()->update([
            'extra_discount' => $input['extra_discount'],
            'expiration_extra_discount' => $input['expiration_extra_discount'],
            'minimum_for_extra_discount' => $input['minimum_for_extra_discount'],
        ]);
    }

    public function reports($input)
    {
        $pharmacies = $this->model->whereHas('clients', function ($query) use ($input) {
            $query->where('pharmacy_client.client_id', $input['client_id']);
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('id', $input['pharmacy_id']);
        })->select(
            DB::raw('SUM(balance) AS debt'),
        )->with(['orders:pharmacy_id,total', 'cashPayments:pharmacy_id,paid_amount', 'purchases:pharmacy_id,total_price', 'transferredBalanceTo:to_pharmacy_id,amount', 'notification:pharmacy_id,type,notification_value'])->get();

        $ordersTotal = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->orders->sum('total');
        });

        $cashPaymentsTotal = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->cashPayments->sum('paid_amount');
        });

        $purchasesTotal = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->purchases->sum('total_price');
        });

        $transferredBalanceTo = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->transferredBalanceTo->sum('amount');
        });

        $notificationAdd = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->notification->where('type', 1)->sum('notification_value');
        });

        $debt = $pharmacies->toArray()[0]['debt'];

        return compact('ordersTotal', 'cashPaymentsTotal', 'purchasesTotal', 'transferredBalanceTo', 'notificationAdd', 'debt');
    }

    public function reportsOwe($input)
    {
        $pharmacies = $this->model->when(isset($input['client_id']), function ($query) use ($input) {
            $query->whereHas('clients', function ($query) use ($input) {
                $query->where('pharmacy_client.client_id', $input['client_id']);
            });
        })->when(isset($input['from']), function ($query) use ($input) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $input['from'])->startOfDay(), Carbon::createFromFormat('Y-m-d', $input['to'])->endOfDay()]);
        })->when(isset($input['pharmacy_id']), function ($query) use ($input) {
            $query->where('id', $input['pharmacy_id']);
        })->select(
            DB::raw('SUM(balance) AS debt'),
        )->with(['cashReceives:pharmacy_id,received_amount', 'transferredBalanceFrom:from_pharmacy_id,amount', 'notification:pharmacy_id,type,notification_value'])->get();

        $cashReceivesTotal = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->cashReceives->sum('received_amount');
        });

        $transferredBalanceFrom = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->transferredBalanceFrom->sum('amount');
        });

        $notificationDiscount = $pharmacies->sum(function ($pharmacy) {
            return $pharmacy->notification->where('type', 0)->sum('notification_value');
        });

        $debt = $pharmacies->toArray()[0]['debt'];

        return compact('cashReceivesTotal', 'transferredBalanceFrom', 'notificationDiscount', 'debt');
    }

    public function settings()
    {
        $data['payment_periods'] = PaymentPeriod::all();
        $data['discount_slats'] = DiscountSlatType::all();
        $data['pharmacy_status'] = PharmacyStatus::all();
        $data['pharmacy_follow_up'] = DayShifts::all();
        $data['payment_types'] = PaymentType::all();
        $data['pharmacy_shifts'] = PharmacyShiftType::all();
        $data['extra_discount'] = ExtraDiscount::all();
        $data['listing_type'] = ListingType::all();
        $data['returns_reasons'] = ReturnsReasons::all();
        $data['product_manufacturing_types'] = ProductManufacturingType::all();

        return $data;
    }
}

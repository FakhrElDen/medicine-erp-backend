<?php

namespace Modules\Cart\Repositories;

use Alkoumi\LaravelArabicNumbers\Numbers;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Cart\Entities\Cart;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Cart\Enums\CartStatus;

class CartRepository extends BaseRepository
{
    public function __construct(protected Cart $model)
    {
    }

    public function getPendingCart($input)
    {
        return $this->model->where('status', CartStatus::PENDING)
            ->where('pharmacy_id', $input['pharmacy_id'])
            ->when(isset($input['name']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('name->ar', 'like', '%' . $input['name'] . '%')->orWhere('name->en', 'like', '%' . $input['name'] . '%');
                });
            })->with('product', 'order', 'product.batches', 'batches')->get();
    }

    public function getCart($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->with('product', 'order', 'product.batches', 'batches')->get();
    }

    public function getCartByOrderId($order_id)
    {
        return $this->model->where('order_id', $order_id)->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * update cart status to in progress and return cart
     */
    public function updateCart($input)
    {
        $items = $this->model->where('order_id', $input['order_id']);
        $items->update(['status' => CartStatus::IN_PROGRESS]);

        return $items->get();
    }

    public function completeCart($input)
    {
        $cart = $this->model->whereIn('id', array_column($input['batch_ids'], 'cart_id'));
        $this->completeWholeCart($cart);

        return $cart->get();
    }

    public function completeWholeCart($cart)
    {
        return $cart->update([
            'status' => CartStatus::COMPLETED,
            'prepared_by' => auth()->id(),
            'completed_at' => Carbon::now(),
        ]);
    }

    /**
     * *info: all product's batches in same corridor
     * so getting @param corridor_id from first product's batch
     * calculate @param subtotal by multiply product's price with ordered quantity
     * *info: discount_value is product's discount
     * calculate @param discount_value by multiply subtotal with (discount / 100)
     * *info: client_discount_difference set in frontend and sent in request
     * calculate @param client_discount_difference_value by multiply subtotal with (client_discount_difference / 100)
     * calculate @param total by subtract discount_value from subtotal
     */
    public function store($input, $product, $order_id, $invoice_number, $pharmacy)
    {
        $input['track_id'] = $pharmacy->track_id;
        $input['taxes'] = $product->taxes;
        $input['price'] = $product->price;
        $input['order_id'] = $order_id;
        $input['product_discount'] = $product->normal_discount;
        $input['subtotal'] = round($product->price * $input['quantity'], 2);
        $input['cart_number'] = $invoice_number;
        $input['discount_value'] = $input['subtotal'] * ($input['discount'] / 100);
        $input['client_discount_difference_value'] = $input['subtotal'] * ($input['client_discount_difference'] / 100);
        $input['total'] = round($input['subtotal'] - $input['discount_value'], 2);

        $cartItem = $this->model->create($input)->load('product');

        return $cartItem;
    }

    public function destroy($cart_id)
    {
        return $this->model->where('id', $cart_id)->delete();
    }

    public function deleteAll($input)
    {
        return $this->model->where('client_id', $input['client_id'])
            ->where('pharmacy_id', $input['pharmacy_id'])
            ->where('status', CartStatus::PENDING)->delete();
    }

    /**
     * (cart's total price but without taxes) @param net_price = cart's total - taxes
     * *INFO: every pharmacy has extra discount or not has if it has:
     * TODO: Calculated by calling calculateExtraDiscountValue() method.
     */
    // needs to refactor
    public function totals($cart, $pharmacy)
    {
        $netPrice = round($cart->sum('total') + $cart->sum('taxes'), 2);
        $extraDiscount = $this->calculateExtraDiscount($pharmacy, $cart->sum('total'), $netPrice);

        $itemsNumber = $this->calculateItemsNumber($cart);

        if ($itemsNumber == 0) {
            return [
                'price' => 0,
                'quantity' => 0,
                'taxes' => 0,
                'items_number' => 0,
                'inventoried_items_number' => 0,
                'subtotal' => 0,
                'total' => 0,
                'net_price' => 0,
                'client_discount_difference_value' => 0,
                'extra_discount' => $pharmacy->extra_discount,
                'extra_discount_value' => 0,
                'extra_discount_condition' => 0,
                'previous_balance' => 0,
                'current_balance' => 0,
            ];
        }

        return [
            'price' => round($cart->sum('price'), 2),
            'quantity' => $cart->sum('quantity') + round($cart->sum('bonus'), 1),
            'taxes' => round($cart->sum('taxes'), 2),
            'items_number' => $itemsNumber,
            'subtotal' => round($cart->sum('subtotal'), 2),
            'total' => round($cart->sum('total'), 2),
            'total_text' => Numbers::TafqeetNumber(round($cart->sum('total'), 2)),
            'net_price' => $netPrice,
            'inventoried_items_number' => $cart->count(),
            'client_discount_difference_value' => round($cart->sum('client_discount_difference_value'), 2),
            'extra_discount' => $pharmacy->extra_discount,
            'extra_discount_value' => $extraDiscount['extra_discount_value'],
            'extra_discount_condition' => $extraDiscount['extra_discount_condition'],
            'client_net_discount_difference_value' => $extraDiscount['extra_discount_value'] - round($cart->sum('client_discount_difference_value'), 2),
            'previous_balance' => $pharmacy->balance,
            'current_balance' => $this->calculateBalance($cart->sum('total'), $pharmacy->balance),
        ];
    }

    public function calculateExtraDiscount($pharmacy, $cartTotal, $netPrice)
    {
        if (isset($pharmacy->extra_discount) && $pharmacy->extra_discount != 0) {
            return $this->calculateExtraDiscountValue($pharmacy, $cartTotal, $netPrice);
        }

        return [
            'extra_discount_condition' => 0,
            'extra_discount_value' => 0,
        ];
    }

    public function calculateItemsNumber($cart)
    {
        return $cart->sum(function ($item) {
            return $item->subBatches->count();
        });
    }

    /**
     * *Unused method
     */
    public function calculateInventoriedItemsNumberInBatches($cart)
    {
        return $cart->sum(function ($item) {
            $item->batches->map(function ($batch) {
                return $batch->pivot->where('status', CartSubBatchStatus::INVENTORIED)->sum('quantity');
            });
        });
    }

    public function calculateCartItemTotal($product_price, $quantity, $discount)
    {
        $subtotal = $product_price * $quantity;
        $discount_value = ($subtotal * ($discount / 100));
        $cart_item_total = $subtotal - $discount_value;
        return $cart_item_total;
    }

    public function calculateBalance($cartTotal, $pharmacyBalance)
    {
        return round($cartTotal + $pharmacyBalance, 2);
    }

    /**
     * will calculate extra discount if pharmacy's @param expiration_extra_discount is greater than or equal today
     * will check @param total (cart's net_price) is greater than or equal @param minimum_for_extra_discount of the pharmacy
     */
    public function calculateExtraDiscountValue($pharmacy, $total, $net_price)
    {
        if (Carbon::parse($pharmacy->expiration_extra_discount)->gt(Carbon::now()->format('Y-m-d')) || Carbon::parse($pharmacy->expiration_extra_discount)->eq(Carbon::now()->format('Y-m-d'))) {
            if ($pharmacy->minimum_for_extra_discount <= $total) {
                return [
                    'extra_discount_condition' => 1,
                    'extra_discount_value' => round($total - ($net_price * ($pharmacy->extra_discount / 100)), 2),
                ];
            }
        }

        return [
            'extra_discount_condition' => 0,
            'extra_discount_value' => round($total - ($net_price * ($pharmacy->extra_discount / 100)), 2),
        ];
    }

    /**
     * generate (invoice_number OR cart_number OR order_number)
     * by getting last cart of the pharmacy and check on its status
     * if not has cart will return 1
     * if status is pending will return same number
     * if more than or equal 190 will return 1 else will return same number plus 1
     */
    public function generateCartNumber($pharmacy_id)
    {
        $pharmacy_cart = $this->model->where('pharmacy_id', $pharmacy_id)->latest()->first();

        if ($pharmacy_cart) {
            if ($pharmacy_cart->status == CartStatus::PENDING) {
                return $pharmacy_cart->cart_number;
            }

            return $pharmacy_cart->cart_number >= 190 ? 1 : $pharmacy_cart->cart_number + 1;
        } else {
            return 1;
        }
    }

    public function sales($input)
    {
        return $this->model->where('client_id', $input['client_id'])
            ->where('pharmacy_id', $input['pharmacy_id'])
            ->where('product_id', $input['product_id'])
            ->where('status', CartStatus::COMPLETED)
            ->applySorts($input)->with('order')->get();
    }

    public function clientProduct($input)
    {
        $startDate = isset($input['date']) ? Carbon::createFromFormat('Y-m-d', $input['date'])->startOfDay() : Carbon::now()->subMonthNoOverflow(1, Carbon::now()->day)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->where('client_id', $input['client_id'])
            ->where('status', CartStatus::COMPLETED)
            ->applySorts($input)
            ->with('product', 'order')
            ->get();
    }

    public function decrement($input)
    {
        $cart_item = $this->model->find($input['cart_id']);
        if ($cart_item->quantity + $cart_item->bonus == $input['quantity']) {
            return $cart_item->delete();
        } else {
            return $cart_item->decrement('quantity', $input['quantity']);
        }
    }

    public function updateCartAfterSettlement($cartSubBatches)
    {
        foreach ($cartSubBatches as $cartSubBatch) {
            $cart = $this->model->where('id', $cartSubBatch->cart_id)->first();
            $input['subtotal'] = round($cart->price * ($cart->quantity - $cartSubBatch->quantity), 2);
            $input['discount_value'] = $input['subtotal'] * ($cart->product_discount / 100);
            $input['total'] = round($input['subtotal'] - $input['discount_value'], 2);
            $input['client_discount_difference_value'] = $input['subtotal'] * ($cart->client_discount_difference / 100);
            $cart->decrement('quantity', $cartSubBatch->quantity);
            $cart->update($input);
        }
    }

    // *Refactor change name of method
    public function quantity(
        int $product_id = null,
        int $warehouse_id = null,
        string $from = null,
        string $to = null
    ) {
        return $this->model
            ->when($product_id, fn ($q) => $q->where('product_id', $product_id))
            ->when($warehouse_id, fn ($q) => $q->where('warehouse_id', $warehouse_id))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('quantity');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Purchase\Enums\CartPurchaseStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('inventoried_quantity')->default(0);
            $table->float('inventoried_quantity_price')->default(0);
            $table->float('public_price');
            $table->float('supply_price');
            $table->float('taxes');
            $table->float('discount')->nullable();
            $table->float('discount_value')->nullable();
            $table->integer('status')->default(CartPurchaseStatus::NON_INVENTORIED);
            $table->float('subtotal');
            $table->float('total');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_purchases');
    }
};

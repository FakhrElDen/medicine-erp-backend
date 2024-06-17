<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('corridor_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('prepared_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->integer('cart_number');
            $table->integer('quantity');
            $table->float('price');
            $table->integer('status')->default(0);
            $table->string('color')->nullable();
            $table->float('taxes');
            $table->float('subtotal');
            $table->float('total');
            $table->integer('client_discount_difference')->nullable()->default(0);
            $table->integer('client_discount_difference_value')->nullable()->default(0);
            $table->text('note')->nullable();
            $table->integer('bonus')->nullable();
            $table->float('product_discount');
            $table->float('discount')->nullable();
            $table->float('discount_value')->nullable();
            $table->dateTime('completed_at')->nullable();
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
        Schema::dropIfExists('carts');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prepared_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->float('last_balance')->nullable();
            $table->float('current_balance')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->float('total_taxes')->nullable();
            $table->float('total_price')->nullable();
            $table->float('total')->nullable();
            $table->float('total_after_extra_discount')->nullable();
            $table->integer('extra_discount')->nullable();
            $table->tinyInteger('extra_discount_condition')->nullable();
            $table->integer('status')->default(0);
            $table->integer('order_number')->nullable();
            $table->integer('shipping_type')->default(0);
            $table->integer('returns')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('delivery_received_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
};

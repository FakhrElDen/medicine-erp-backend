<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Cart\Enums\CartSubBatchStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_sub_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('sub_batch_id')->constrained('sub_batches')->cascadeOnDelete();
            $table->foreignId('inventoried_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->integer('quantity');
            $table->float('total');
            $table->string('color')->nullable();
            $table->float('price');
            $table->integer('discount')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('status')->default(CartSubBatchStatus::IN_PROGRESS);
            $table->integer('returned_quantity')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('inventoried_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_sub_batch');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_purchases_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchases_return_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->nullable();
            $table->float('total')->nullable();
            $table->tinyInteger('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_purchases_returns');
    }
};

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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->nullable()->constrained(table:'orders')->cascadeOnDelete();
            $table->foreignId('pharmacy_id')->nullable()->constrained(table:'pharmacies')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained(table:'warehouses')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained(table:'users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};

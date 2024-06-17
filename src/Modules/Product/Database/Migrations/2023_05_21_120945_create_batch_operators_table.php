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
        // purchase_id, cart_purchase_id, supplier_id, supplied_at, reviewer_received_at and receiver_reviewer_id 
        // they are shouldn't be nullable 
        // but we make it nullable temporary for business logic 
        Schema::create('batch_operators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('storing_worker_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('sub_batch_id')->nullable()->constrained('sub_batches')->cascadeOnDelete();
            $table->foreignId('receiver_distributor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_reviewer_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('distributor_received_at')->nullable();
            $table->dateTime('reviewer_received_at')->nullable();
            $table->dateTime('stored_at')->nullable();
            $table->dateTime('supplied_at')->nullable();
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
        Schema::dropIfExists('batch_operators');
    }
};

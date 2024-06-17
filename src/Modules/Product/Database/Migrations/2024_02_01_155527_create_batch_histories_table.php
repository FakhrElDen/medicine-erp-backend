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
        Schema::create('batch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('second_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable();
            $table->integer('quantity_after');
            $table->integer('warehouse_product_quantity_after')->nullable();
            $table->integer('amount');
            $table->tinyInteger('type')->default(1)->index();
            $table->string('subject_type')->nullable();
            $table->index(['subject_type', 'subject_id']);
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
        Schema::dropIfExists('change_logs');
    }
};

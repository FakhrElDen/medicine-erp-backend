<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->json('name');
            $table->string('email')->unique()->nullable();
            $table->float('debt_limit', 16, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('doctor')->nullable();
            $table->integer('payment_type')->nullable();
            $table->integer('type')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->boolean('active')->default(1);
            $table->integer('extra_discount')->default(0);
            $table->integer('discount_slat')->default(0);
            $table->date('expiration_extra_discount')->nullable();
            $table->integer('minimum_for_extra_discount')->nullable();
            $table->integer('balance')->default(0);
            $table->string('commercial_registration_no')->unique()->nullable();
            $table->string('license_no')->unique()->nullable();
            $table->string('tax_card_no')->unique()->nullable();
            $table->integer('all')->default(1);
            $table->float('target', 16, 2)->default(0);
            $table->float('minimum_target', 16, 2)->default(0);
            $table->integer('status')->nullable();
            $table->integer('call_shift')->nullable();
            $table->integer('follow_up')->nullable();
            $table->integer('payment_period')->default(1);
            $table->integer('iterate_available_quantity')->default(1);
            $table->string('optional_phone_number')->nullable();
            $table->string('code')->unique();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_url')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('using_iterate_available_quantity_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('pharmacies');
    }
};

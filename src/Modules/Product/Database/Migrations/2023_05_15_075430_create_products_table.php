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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();
            $table->json('name');
            $table->longText('description');
            $table->string('sku');
            $table->string('barcode')->unique();
            $table->integer('total_quantity')->default(0);
            $table->integer('type')->default(0);
            $table->boolean('is_limited')->default(0);
            $table->integer('limited_quantity')->default(0);
            $table->float('price');
            $table->float('taxes');
            $table->float('normal_discount')->default(0);
            $table->integer('items_number_in_packet')->default(0);
            $table->integer('packets_number_in_package')->default(0);
            $table->integer('manufacturing_type')->default(0);
            $table->integer('selling_status')->nullable();
            $table->integer('buying_status')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};

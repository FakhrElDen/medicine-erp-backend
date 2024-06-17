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
        Schema::create('returnables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('returns_id')->constrained()->cascadeOnDelete();
            $table->morphs('returnable');

            $table->integer('quantity')->nullable();
            $table->float('price')->nullable();
            $table->float('discount')->nullable();
            $table->float('total')->nullable();
            $table->tinyInteger('reason')->nullable();
            $table->string('operating_number')->nullable();
            $table->date('expired_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returnables');
    }
};

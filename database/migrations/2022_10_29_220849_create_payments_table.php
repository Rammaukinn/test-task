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
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id')->nullable(false);
            $table->integer('payment_id')->nullable(false);
            $table->enum('status', ['created', 'pending', 'completed', 'expired', 'rejected'])->nullable(false);
            $table->bigInteger('amount')->nullable(false);
            $table->bigInteger('amount_paid')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

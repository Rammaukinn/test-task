<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id')->nullable(false);
            $table->string('merchant_key')->nullable(false);
            $table->integer('payment_gateway')->nullable(false);
        });

        DB::table('merchants')->insert([
            [
                'merchant_id' => 6,
                'merchant_key' => 'KaTf5tZYHx4v7pgZ',
                'payment_gateway' => 1
            ],
            [
                'merchant_id' => 816,
                'merchant_key' => 'rTaasVHeteGbhwBx',
                'payment_gateway' => 2
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};

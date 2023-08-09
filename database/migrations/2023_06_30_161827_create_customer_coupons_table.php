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
        Schema::create('customer_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('status')->enum('Claimed', 'Redeemed')->default('Claimed');
            $table->string('code')->unique();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->foreignId('customer_id')->constrained('customers', 'id');
            $table->foreignId('coupon_id')->constrained('coupons', 'id');
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
        Schema::dropIfExists('customer_coupons');
    }
};

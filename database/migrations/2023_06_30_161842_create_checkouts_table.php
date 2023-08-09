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
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->string('payment_method')->enum('Credit Card', 'Debit Card', 'Caash', 'E-wallet');
            $table->foreignId('customer_id')->constrained('customers', 'id');
            $table->integer('customer_coupon_id')->unsigned()->nullable();
            $table->foreign('customer_coupon_id')->references('id')->on('customer_coupons');        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkouts');
    }
};

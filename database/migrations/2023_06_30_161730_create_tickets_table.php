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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('query_type')->enum('Feedback', 'Complaint', 'Query', 'Issue');
            $table->text('message');
            $table->string('status')->enum('New', 'Open', 'Pending', 'Solved', 'Closed');
            $table->foreignId('support_staff_id')->constrained('support_staffs', 'id');
            $table->foreignId('customer_id')->constrained('customers', 'id');
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
        Schema::dropIfExists('tickets');
    }
};

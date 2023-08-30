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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('gender')->enum('Female', 'Male', '');
            $table->string('status')->enum('New', 'Contacted', 'Interested', 'Not interested');
            $table->text('activity')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('marketing_staff_id')->constrained('marketing_staffs', 'id');      
            $table->timestamp('activity_date')->nullable();  
            $table->timestamp('feedback_date')->nullable();  
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
        Schema::dropIfExists('leads');
    }
};
